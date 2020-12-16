<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTugasTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tbl_tugas', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->string('file')->nullable();
            $table->integer('nilai')->nullable();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('feed_id');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('siswa_id')
                ->references('id')
                ->on('tbl_user')
                ->onDelete('cascade');
            $table->foreign('class_id')
                ->references('id')
                ->on('tbl_class')
                ->onDelete('cascade');
            $table->foreign('feed_id')
                ->references('id')
                ->on('tbl_feed')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('tbl_class');
    }
}
