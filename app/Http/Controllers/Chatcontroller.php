<?php

namespace App\Http\Controllers;

use App\Events\NewOrderMessage;
use App\Models\Order;
use App\Models\OrderMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller

{
    
    // ── Ambil pesan (AJAX) ─────────────────────────────────────
    
    public function messages(Order $order)
    {
        date_default_timezone_set('Asia/Jakarta');
        // Pastikan hanya customer atau mitra yang terlibat
        $user = Auth::user();
        abort_if(
            $order->customer_id !== $user->id && $order->mitra_id !== $user->id,
            403
        );

        $messages = OrderMessage::where('order_id', $order->id)
            ->with('sender:id,name')
            ->orderBy('created_at')
            ->get()
            ->map(fn($m) => [
                'id'          => $m->id,
                'sender_id'   => $m->sender_id,
                'sender_name' => $m->sender->name,
                'sender_role' => $m->sender_role,
                'message'     => $m->message,
                'time'        => $m->created_at->format('H:i'),
                'is_mine'     => $m->sender_id === $user->id,
            ]);

        // Tandai pesan lawan baca
        OrderMessage::where('order_id', $order->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    // ── Kirim pesan (AJAX) ─────────────────────────────────────
    public function send(Request $request, Order $order)
    {
         date_default_timezone_set('Asia/Jakarta');
        $user = Auth::user();
        abort_if(
            $order->customer_id !== $user->id && $order->mitra_id !== $user->id,
            403
        );

        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $msg = OrderMessage::create([
            'order_id'    => $order->id,
            'sender_id'   => $user->id,
            'sender_role' => $user->role,
            'message'     => $request->message,
            'is_read'     => false,
        ]);

        // Broadcast ke Pusher
        broadcast(new NewOrderMessage($msg))->toOthers();

        return response()->json([
            'id'          => $msg->id,
            'sender_id'   => $msg->sender_id,
            'sender_name' => $user->name,
            'sender_role' => $msg->sender_role,
            'message'     => $msg->message,
            'time'        => $msg->created_at->format('H:i'),
            'is_mine'     => true,
        ]);
    }

    // ── Jumlah pesan belum dibaca (AJAX) ───────────────────────
    public function unread(Order $order)
    {
         date_default_timezone_set('Asia/Jakarta');
        $user  = Auth::user();
        $count = OrderMessage::where('order_id', $order->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread' => $count]);
    }

    public function unreadAll()
    {
  $user = Auth::user();

    // Untuk Customer: hitung pesan dari semua order-nya yang belum dibaca
    // Untuk Mitra: sama, dari order yang ia tangani
    $count = OrderMessage::whereHas('order', function ($q) use ($user) {
            $q->where('customer_id', $user->id)
              ->orWhere('mitra_id', $user->id);
        })
        ->where('sender_id', '!=', $user->id)
        ->where('is_read', false)
        ->count();

    return response()->json(['unread' => $count]);
    }


    public function unreadPreview()
{
    $user = Auth::user();

    // Ambil order yang punya pesan unread
    $orders = \App\Models\Order::where(function ($q) use ($user) {
            $q->where('customer_id', $user->id)
              ->orWhere('mitra_id', $user->id);
        })
        ->whereHas('messages', function ($q) use ($user) {
            $q->where('sender_id', '!=', $user->id)
              ->where('is_read', false);
        })
        ->with(['messages' => function ($q) use ($user) {
            $q->where('sender_id', '!=', $user->id)
              ->where('is_read', false)
              ->with('sender:id,name')
              ->latest();
        }])
        ->limit(5)
        ->get();

    $previews = $orders->map(function ($order) use ($user) {
        $latest = $order->messages->first();
        return [
            'order_id'      => $order->id,
            'sender_name'   => $latest->sender->name ?? 'Unknown',
            'last_message'  => \Str::limit($latest->message, 40),
            'time'          => $latest->created_at->format('H:i'),
            'unread_count'  => $order->messages->count(),
        ];
    });

    $totalUnread = \App\Models\OrderMessage::whereHas('order', function ($q) use ($user) {
            $q->where('customer_id', $user->id)
              ->orWhere('mitra_id', $user->id);
        })
        ->where('sender_id', '!=', $user->id)
        ->where('is_read', false)
        ->count();

    return response()->json([
        'previews'      => $previews,
        'total_unread'  => $totalUnread,
    ]);
}

 public function unreadCount(Order $order)
    {
        $user = Auth::user();
        abort_if(
            $order->customer_id !== $user->id && $order->mitra_id !== $user->id,
            403
        );

        $count = OrderMessage::where('order_id', $order->id)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->count();

        return response()->json(['unread' => $count]);
    }


    public function partner(Order $order)
{
    $user = Auth::user();

    abort_if(
        $order->customer_id !== $user->id && $order->mitra_id !== $user->id,
        403
    );

    // Tentukan lawan chat
    if ($user->id === $order->customer_id) {
        $partner = \App\Models\User::find($order->mitra_id);
    } else {
        $partner = \App\Models\User::find($order->customer_id);
    }

    return response()->json([
        'name'  => $partner->name ?? 'User',
        'photo' => $partner->photo 
                    ? asset('storage/' . $partner->photo)
                    : null,
    ]);
}

}
