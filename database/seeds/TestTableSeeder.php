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
        for ($i = 0; $i <= 100; $i++)
            \App\Article::create([
                "cat_id" => rand(1, 12),
                "title" => $faker->jobTitle,
                "short_description" => $faker->text(100),
                "description" => $faker->text(200),
                "image" => $faker->imageUrl(),
                "type_image" => 2,
                "video" => null,
                "type_video" => 0,
                "audio" => null,
                "type_audio" => 0,
            ]);


    }
}
