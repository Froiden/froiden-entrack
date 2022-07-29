<?php

namespace Database\Factories;

use App\Models\License;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LicenseFactory extends Factory
{

    protected $model = License::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $productIds = Product::get()->pluck('id')->toArray();
        $licenseTypes = ['Regular License', 'Extended License'];

        return [
            'app_url' => 'https://'.$this->faker->domainName,
            'purchase_code' => Str::lower(Str::random(8).'-'.Str::random(4).'-'.Str::random(4).'-'.Str::random(4).'-'.Str::random(12)), /* @phpstan-ignore-line */
            'purchased_on' => now()->toDateTimeString(),
            'supported_until' => now()->addMonth(6)->toDateTimeString(),
            'license_type' => $licenseTypes[array_rand($licenseTypes)],
            'buyer_username' => $this->faker->userName,
            'earning' => $this->faker->numberBetween(1, 99),
            'product_id' => $productIds[array_rand($productIds)]
        ];
    }

}
