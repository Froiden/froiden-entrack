<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvatoSetting extends Model
{

    protected $dates = ['last_cron_run'];

    protected $appends = [
        'logo_url'
    ];

    public function getLogoUrlAttribute()
    {

        if (is_null($this->logo)) {
            return asset('envato.png');
        }

        return asset_url('app-logo/' . $this->logo);

    }

}
