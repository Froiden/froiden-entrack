<?php

namespace App\Console\Commands;

use App\Mail\DailySalesEmail;
use App\Models\EnvatoSetting;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendDailyEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-daily-sales-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email with daily sales summary.';

    private $setting;

        /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        try {
            $this->setting = EnvatoSetting::first();
            Config::set('app.envato_key', $this->setting->envato_api_key);
        } catch (\Throwable $th) {
            logger($th);
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->setting->daily_email) {
            $users = User::all();

            foreach ($users as $key => $value) {
                Mail::to($value->email)->send(new DailySalesEmail());
            }
        }

    }

}
