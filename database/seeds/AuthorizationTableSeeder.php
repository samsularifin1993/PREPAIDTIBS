<?php

use Illuminate\Database\Seeder;

class AuthorizationTableSeeder extends Seeder
{
    public function __construct(){
        date_default_timezone_set('Asia/Jakarta');
    }

    public function run()
    {
        DB::table('authorizations')->insert([
            'name' => 'Administrator',

            'role_r' => 'true',
            'role_i' => 'true',
            'role_u' => 'true',
            'role_d' => 'true',
            
            'user_r' => 'true',
            'user_i' => 'true',
            'user_u' => 'true',
            'user_d' => 'true',

            'channel_r' => 'true',
            'channel_i' => 'true',
            'channel_u' => 'true',
            'channel_d' => 'true',

            'organization_r' => 'true',
            'organization_i' => 'true',
            'organization_u' => 'true',
            'organization_d' => 'true',

            'payment_r' => 'true',
            'payment_i' => 'true',
            'payment_u' => 'true',
            'payment_d' => 'true',

            'product_family_r' => 'true',
            'product_family_i' => 'true',
            'product_family_u' => 'true',
            'product_family_d' => 'true',

            'product_r' => 'true',
            'product_i' => 'true',
            'product_u' => 'true',
            'product_d' => 'true',

            'v_dashboard_admin' => 'true',
            'v_dashboard_revenue' => 'true',
            'r_trx_success' => 'true',
            'r_trx_reject' => 'true',
            'r_revenue' => 'true',

            'created_by' => '1',
            'updated_by' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('authorizations')->insert([
            'name' => 'Finance',

            'role_r' => 'true',
            'role_i' => 'false',
            'role_u' => 'false',
            'role_d' => 'false',
            
            'user_r' => 'true',
            'user_i' => 'false',
            'user_u' => 'false',
            'user_d' => 'false',

            'channel_r' => 'true',
            'channel_i' => 'false',
            'channel_u' => 'false',
            'channel_d' => 'false',

            'organization_r' => 'true',
            'organization_i' => 'false',
            'organization_u' => 'false',
            'organization_d' => 'false',

            'payment_r' => 'true',
            'payment_i' => 'false',
            'payment_u' => 'false',
            'payment_d' => 'false',

            'product_family_r' => 'true',
            'product_family_i' => 'false',
            'product_family_u' => 'false',
            'product_family_d' => 'false',

            'product_r' => 'true',
            'product_i' => 'false',
            'product_u' => 'false',
            'product_d' => 'false',

            'v_dashboard_admin' => 'false',
            'v_dashboard_revenue' => 'true',
            'r_trx_success' => 'true',
            'r_trx_reject' => 'true',
            'r_revenue' => 'true',

            'created_by' => '1',
            'updated_by' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        DB::table('authorizations')->insert([
            'name' => 'Marketing',

            'role_r' => 'true',
            'role_i' => 'false',
            'role_u' => 'false',
            'role_d' => 'false',
            
            'user_r' => 'true',
            'user_i' => 'false',
            'user_u' => 'false',
            'user_d' => 'false',

            'channel_r' => 'true',
            'channel_i' => 'false',
            'channel_u' => 'false',
            'channel_d' => 'false',

            'organization_r' => 'true',
            'organization_i' => 'false',
            'organization_u' => 'false',
            'organization_d' => 'false',

            'payment_r' => 'true',
            'payment_i' => 'false',
            'payment_u' => 'false',
            'payment_d' => 'false',

            'product_family_r' => 'true',
            'product_family_i' => 'false',
            'product_family_u' => 'false',
            'product_family_d' => 'false',

            'product_r' => 'true',
            'product_i' => 'false',
            'product_u' => 'false',
            'product_d' => 'false',

            'v_dashboard_admin' => 'false',
            'v_dashboard_revenue' => 'true',
            'r_trx_success' => 'true',
            'r_trx_reject' => 'true',
            'r_revenue' => 'true',
            
            'created_by' => '1',
            'updated_by' => '1',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
