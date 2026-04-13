<?php namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class MitraEarning extends Model {
    protected $fillable = ['mitra_id','order_id','type','gross_amount','platform_fee','net_amount','bonus','earned_date','is_disbursed','disbursed_at','period','note'];
    protected $casts    = ['earned_date'=>'date','is_disbursed'=>'boolean','disbursed_at'=>'datetime'];

    public function mitra() { return $this->belongsTo(User::class, 'mitra_id'); }
    public function order() { return $this->belongsTo(Order::class); }

    public function scopeToday($q)       { return $q->whereDate('earned_date', today()); }
    public function scopeThisWeek($q)    { return $q->whereBetween('earned_date', [now()->startOfWeek(), now()->endOfWeek()]); }
    public function scopeThisMonth($q)   { return $q->whereMonth('earned_date', now()->month); }
}