<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Contact_us;

class ContactUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('contact_uses')->truncate();



        $limit = 20;
        for($i = 0; $i < $limit ;$i++) {
            Contact_us::create([
                'name' => 'Name-' . $i,
                'phone' => '0912' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'email' => 'player-' . $i . '@test.com',
                'gender' => 'M',
                'country' => '',
                'district' => '',
                'address' => '南京東路112號',
                'subject' => '我是主旨',
                'content' => '我是內容',
            ]);
        }
    }
}
