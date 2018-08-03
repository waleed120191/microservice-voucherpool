<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
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
            DB::connection('mysql')->table('users')->insert([
                'name' => $faker->firstName . ' ' . $faker->lastName,
                'email' => $faker->email,
                'created_at' => Carbon\Carbon::now()->toDateTimeString()
            ]);
        }
    }
}
