<?php

namespace App\Mail;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DailySalesEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $yesterdayFrom = now(envatoSetting()->timezone)->subDay()->startOfDay()->setTimezone('UTC')->toDateTimeString();
        $yesterdayTo = now(envatoSetting()->timezone)->subDay()->endOfDay()->setTimezone('UTC')->toDateTimeString();

        $yesterdaySales = Product::withCount(
            [
                'sales' => function ($q) use ($yesterdayFrom, $yesterdayTo) {
                        $q->whereBetween('sales.date', [$yesterdayFrom, $yesterdayTo]);

                    $q->where(
                        function ($query) {
                            return $query->where('sales.type', 'Sale');
                        }
                    );
                    $q->select(DB::raw('COUNT(DISTINCT(document))'), DB::raw('SUM(document) as earnings)'));
                }
            ]
        )
        ->with(['sales' => function ($q) use ($yesterdayFrom, $yesterdayTo) {
            $q->whereBetween('sales.date', [$yesterdayFrom, $yesterdayTo]);
        }])
            ->get();

        return $this->subject('Envato Sales on ' . now(envatoSetting()->timezone)->subDay()->format('d M, Y'))->markdown('mail.daily-sales-email', ['yesterdaySales' => $yesterdaySales]);
    }

}
