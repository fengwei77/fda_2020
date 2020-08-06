<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Subscriber;

class SubscriberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('subscribers')->truncate();



        $limit = 2;
        for($i = 0; $i < $limit ;$i++) {
            Subscriber::create([
                'name' => 'Name-' . $i,
                'email' => 'luke@pulsation.com.tw',
            ]);
        }
    }
}
