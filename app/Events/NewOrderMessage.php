<?php

namespace App\Events;

use App\Models\OrderMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewOrderMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public OrderMessage $orderMessage;

    public function __construct(OrderMessage $orderMessage)
    {
        $this->orderMessage = $orderMessage->load('sender');
    }

    // Channel: order-chat.{order_id}
    public function broadcastOn(): Channel
    {
        return new Channel('order-chat.' . $this->orderMessage->order_id);
    }

    public function broadcastAs(): string
    {
        return 'new-message';
    }

    public function broadcastWith(): array
    {
        return [
            'id'          => $this->orderMessage->id,
            'order_id'    => $this->orderMessage->order_id,
            'sender_id'   => $this->orderMessage->sender_id,
            'sender_name' => $this->orderMessage->sender->name,
            'sender_role' => $this->orderMessage->sender_role,
            'message'     => $this->orderMessage->message,
            'time'        => $this->orderMessage->created_at->format('H:i'),
        ];
    }
}