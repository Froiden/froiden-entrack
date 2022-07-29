<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'product_name' => 'Worksuite Saas - Project Management System',
                'framework' => 'Laravel',
                'product_link' => 'https://codecanyon.net/item/worksuite-saas-project-management-system/23263417',
                'product_thumbnail' => 'https://s3.envato.com/files/259906155/thumbnail.png',
                'envato_id' => '23263417',
                'envato_site' => 'codecanyon.net',
                'rating' => '4.86',
                'rating_count' => '129',
                'number_of_sales' => 1073
            ],
            [
                'product_name' => 'Asset Management Module for Worksuite SAAS',
                'framework' => 'Laravel',
                'product_link' => 'https://codecanyon.net/item/asset-management-module-for-worksuite-saas-crm/25830090',
                'product_thumbnail' => 'https://s3.envato.com/files/299774143/thumbnail-plugin-assets.png',
                'envato_id' => '25830090',
                'envato_site' => 'codecanyon.net',
                'rating' => '5',
                'rating_count' => '13',
                'number_of_sales' => 144
            ],
            [
                'product_name' => 'Payroll Module For Worksuite CRM',
                'framework' => 'Laravel',
                'product_link' => 'https://codecanyon.net/item/payroll-module-for-worksuite-crm/25388620',
                'product_thumbnail' => 'https://s3.envato.com/files/281701971/thumbnail-PAYROLL.png',
                'envato_id' => '25388620',
                'envato_site' => 'codecanyon.net',
                'rating' => '5',
                'rating_count' => '4',
                'number_of_sales' => 173
            ],
            [
                'product_name' => 'WORKSUITE - HR, CRM and Project Management',
                'framework' => 'Laravel',
                'product_link' => 'https://codecanyon.net/item/worksuite-project-management-system/20052522',
                'product_thumbnail' => 'https://s3.envato.com/files/270398155/thumbnail.png',
                'envato_id' => '20052522',
                'envato_site' => 'codecanyon.net',
                'rating' => '5',
                'rating_count' => '13',
                'number_of_sales' => 144
            ],
            [
                'product_name' => 'REST API Module for Worksuite CRM',
                'framework' => 'Laravel',
                'product_link' => 'https://codecanyon.net/item/rest-api-module-for-worksuite-crm/25683880',
                'product_thumbnail' => 'https://s3.envato.com/files/281702001/thumbnail-rest-api.png',
                'envato_id' => '25683880',
                'envato_site' => 'codecanyon.net',
                'rating' => '5',
                'rating_count' => '3',
                'number_of_sales' => 157
            ]
        ]);
    }

}
