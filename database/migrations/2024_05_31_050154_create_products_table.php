<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('id', length: 100)->unique();
            $table->string('name', length: 100);
            $table->tinyText('description');
            $table->text('directions');
            $table->decimal('price', total: 8, places: 2);
            $table->integer('in_stock')->default(0); 
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');  
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
