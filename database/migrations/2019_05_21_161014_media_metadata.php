<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MediaMetadata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media_metadata', function (Blueprint $table) {
            $table->bigInteger('media_id', 20 )->nullable()->autoIncrement();
            $table->tinyInteger('media_type_id', 3)->nullable()->autoIncrement();
            $table->mediumInteger('batch_id', 8)->default(0)->nullable();
            $table->text('metadata')->default(0);
            $table->charset = 'utf8';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_metadata');
    }
}
