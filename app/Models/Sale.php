<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $dates = ['date'];

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function refunded()
    {
        $checkRefund = Sale::where('type', 'Sale Refund')->where('order_id', $this->order_id)->first();

        return (!is_null($checkRefund)) ? true : false;
    }

    public function reversed()
    {
        $checkReversal = Sale::where('type', 'Sale Reversal')->where('order_id', $this->order_id)->first();

        return (!is_null($checkReversal)) ? true : false;
    }

}
