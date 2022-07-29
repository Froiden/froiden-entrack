<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserBadgeSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_badges')->insert([
            [
                'name' => 'elite',
                'label' => 'Elite Author',
                'image' => 'https://public-assets.envato-static.com/assets/badges/elite-d41954e54b009732125beec497f8e4e837aabac1fb59883dc88f167d61fe06ae.svg'
            ],
            [
                'name' => 'was_weekly_top_seller',
                'label' => 'Weekly Top Seller',
                'image' => 'https://public-assets.envato-static.com/assets/badges/was_weekly_top_seller-dde99217d9005ef700a7a22d3860f45ab4a7400515ceced979050ea19566f191.svg'
            ],
            [
                'name' => 'one_billion_milestone',
                'label' => 'Milestone Member',
                'image' => 'https://public-assets.envato-static.com/assets/badges/one_billion_milestone-6bafd9c1c66e21701fa25158e42e4f28a3ca101552f952c9680b10822f2c147e.svg'
            ],
            [
                'name' => 'author_level_9',
                'label' => 'Author Level 9',
                'image' => 'https://public-assets.envato-static.com/assets/badges/author_level_9-92a8e95528677da723ac0995bf748fc80088cbf135c8372b2b95d52bb63413b8.svg'
            ],
            [
                'name' => 'veteran_level_8',
                'label' => '8 Years of Membership',
                'image' => 'https://public-assets.envato-static.com/assets/badges/veteran_level_8-b23a94ace4eb9279895cdd2e80e7be64d7903ddb5cb41656a58ad2ba6c972ddc.svg'
            ],
            [
                'name' => 'exclusive',
                'label' => 'Exclusive Author',
                'image' => 'https://public-assets.envato-static.com/assets/badges/exclusive-c46b38381f19512d29603bebe1a1fae3a44a4147dddef99185ee228667c17a66.svg'
            ],
            [
                'name' => 'affiliate_level_2',
                'label' => 'Affiliate Level 2',
                'image' => 'https://public-assets.envato-static.com/assets/badges/affiliate_level_2-75a92b0c30ab087303a378e5a1a7ccdc31c0db7936c6f0968b130e3547b94a58.svg'
            ],
            [
                'name' => 'collector_level_2',
                'label' => 'Collector Level 2',
                'image' => 'https://public-assets.envato-static.com/assets/badges/collector_level_2-3c965fe571f6b45ce222862ed5ee1312a9dca9993a7ae7a8f22ce17a2b42cf4d.svg'
            ],
            [
                'name' => 'had_trending_item',
                'label' => 'Trendsetter',
                'image' => 'https://public-assets.envato-static.com/assets/badges/had_trending_item-b7a0c2c3954e4704f760d9ac81594f71cef9da4f00a873ce6ab5e000af1b1d6a.svg'
            ],
        ]);
    }

}
