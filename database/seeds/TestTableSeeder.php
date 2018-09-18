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
        for ($i = 1; $i <= 10; $i++)
            \App\Category::create([
                "title" => $faker->title,
                "description" => $faker->text,
                "path" => $faker->imageUrl(),
                "type_path" => 2
            ]);
        for ($i = 0; $i <= 100; $i++)
            \App\Article::create([
                "cat_id" => rand(1, 10),
                "title" => $faker->title,
                "short_description" => $faker->text,
                "description" => $faker->text,
                "image" => $faker->imageUrl(),
                "type_image" => 2,
                "video" => null,
                "type_video" => 0,
                "audio" => null,
                "type_audio" => 0,
            ]);


    }
}
