<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(AdminsTableSeeder::class);
        $this->call(ChannelsTableSeeder::class);
        $this->call(OrgRegionalTableSeeder::class);
        $this->call(OrgWitelTableSeeder::class);
        $this->call(OrgDatelTableSeeder::class);
        $this->call(PaymentTableSeeder::class);
        $this->call(ProductFamilyTableSeeder::class);
        $this->call(ProductTableSeeder::class);
        $this->call(ErrorCodeTableSeeder::class);
        $this->call(TransactionSuccessTableSeeder::class);
        $this->call(TransactionRejectedTableSeeder::class);
        $this->call(AuthorizationTableSeeder::class);
    }
}
