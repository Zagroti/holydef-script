<?php

use App\Inside\Constants;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArticleFavouriteTableMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Constants::ARTICLE_FAVOURITE_DB, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger("article_id");
            $table->bigInteger('user_id');
            $table->integer('created_at');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Constants::ARTICLE_FAVOURITE_DB);
    }
}
