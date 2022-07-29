<?php

namespace App\Console\Commands;

use App\Models\EnvatoSetting;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HideCoreJobMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hide-cron-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hide cronjob message.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */

    public function handle()
    {
        $setting = EnvatoSetting::first();

        $setting->last_cron_run = Carbon::now();
        $setting->hide_cron_message = 1;
        $setting->save();
    }

}
