<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = DB::table("contents")->get();
        foreach ($contents as $value)
            \App\Article::create([
                "cat_id" => $value->cat_id,
                "title" => $value->title,
                "short_description" => $value->short_desc,
                "description" => $value->full_desc,
                "image" => "http://holydef.ir/appdata/media/images/" . $value->img,
                "type_image" => 2,
                "video" => "http://clips.vorwaerts-gmbh.de/VfE_html5.mp4",
                "type_video" => 2,
                "audio" => "https://www.irantunez.com/uploads/audios/1721f186dc9dc90fa4d972186bc636c7/dariush-shekanjeh-gar.mp3",
                "type_audio" => 2,
            ]);
//        $faker = Faker\Factory::create();
//        for ($i = 0; $i <= 100; $i++)
//            \App\Article::create([
//                "cat_id" => rand(1, 12),
//                "title" => $faker->jobTitle,
//                "short_description" => $faker->text(100),
//                "description" => $faker->text(200),
//                "image" => $faker->imageUrl(),
//                "type_image" => 2,
//                "video" => "http://clips.vorwaerts-gmbh.de/VfE_html5.mp4",
//                "type_video" => 2,
//                "audio" => "https://www.irantunez.com/uploads/audios/1721f186dc9dc90fa4d972186bc636c7/dariush-shekanjeh-gar.mp3",
//                "type_audio" => 2,
//            ]);
    }
}
