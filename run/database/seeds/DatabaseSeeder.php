<?php

use App\Models\Model;
use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
//        $this->call(ContactUsSeeder::class);
//        $this->call(SubscriberSeeder::class);
        $this->call(PlayerSeeder::class);
        $this->call(InvoiceSeeder::class);
        Model::reguard();

    }
}
