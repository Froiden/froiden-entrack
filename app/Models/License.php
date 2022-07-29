<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;
    
    protected $dates = ['purchased_on', 'supported_until'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function setAppUrlAttribute($value)
    {
        $this->attributes['app_url'] = $this->replace($value);
    }

    public function getAppUrlAttribute($value)
    {
        return $this->replace($value);
    }

    private function replace($value)
    {
        $value = str_replace('/verify-purchase', '', $value);
        $value = str_replace('/purchase-verified', '', $value);
        return $value;
    }

}
