<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'product_link',
        'envato_id',
        'product_thumbnail',
        'framework',
        'is_plugin',
        'parent_product_id',
        'summary',
        'fetched',
        'last_updated_at',
        'rating_count',
        'rating',
        'envato_site',
        'number_of_sales',
    ];
    protected $appends = [
        'earning'
    ];

    protected $hidden = ['earning'];

    public function parent()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }

    public function roadmaps()
    {
        return $this->hasMany(Roadmap::class, 'product_id');
    }

    public function getEarningAttribute()
    {
        $fromDateFilter = request()->input('from_date');
        $toDateFilter = request()->input('to_date');

        $earning = Sale::where('product_id', $this->id);

        if ($fromDateFilter != '') {
            $earning = $earning->whereDate('date', '>=', $fromDateFilter);
        }

        if ($toDateFilter != '') {
            $earning = $earning->whereDate('date', '<=', $toDateFilter);
        }

        $earning = $earning->sum('amount');
        return round($earning, 2);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id')->where('sales.type', 'Sale');
    }

    public function salesTotal()
    {
        return $this->hasMany(Sale::class, 'product_id')->sum('amount');
    }

    public function refunds()
    {
        return $this->hasMany(Sale::class, 'product_id')->where('sales.type', 'Sale Refund');
    }

    public function latestVersion()
    {
        return $this->hasOne(Version::class)->orderBy('version', 'desc');
    }

}
