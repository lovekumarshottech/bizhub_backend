<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->enum('type', ['0', '1'])->comment('0 = job provider, 1 = job seeker');
            $table->string('image', 2000)->nullable();
            $table->string('title', 500)->nullable();
            $table->longText('description')->nullable();
            $table->decimal('amount', 8, 2);
            $table->enum('is_negotiable', array('0', '1'))->default('0')->comment('0 for Not Negotiable, 1 for Negotiable');
            $table->timestamp('start_date')->nullable();
            $table->unsignedTinyInteger('no_of_ppl')->nullable();
            $table->string('address', 1000)->nullable();
            $table->string('latitude', 500)->nullable();
            $table->string('longitude', 500)->nullable();
            $table->enum('status', array('0', '1', '2'))->default('0')->comment('0 for Active, 1 for Completed, 2 for Expired');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
