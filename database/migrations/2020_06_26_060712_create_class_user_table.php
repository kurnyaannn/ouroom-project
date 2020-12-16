<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_class_user', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('class_id')
                ->references('id')
                ->on('tbl_class')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('tbl_user')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_class_user');
    }
}
