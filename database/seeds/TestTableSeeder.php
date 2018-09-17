<?php

use Illuminate\Database\Seeder;

class TestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        for ($i = 0; $i <= 10; $i++)
            \App\Category::create([
                "title" => $faker->title,
                "description" => $faker->text,
                "path" => $faker->imageUrl(),
                "type_path" => 2
            ]);
    }
}
