<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SpecialOffersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            DB::connection('mysql2')->table('special_offers')->insert([
                'name' => $faker->bs,
                'discount' => $faker->randomFloat(2, 0, 100) ,
                'created_at' => Carbon\Carbon::now()->toDateTimeString()
            ]);
        }
    }
}
