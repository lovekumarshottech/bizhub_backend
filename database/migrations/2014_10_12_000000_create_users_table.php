<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',150);
            $table->string('last_name',150);
            $table->string('image', 500)->nullable();
            $table->string('email',150)->unique();
            $table->string('phone',25)->unique();
            $table->string('password',500);
            $table->text('device_id')->nullable();
            $table->text('device_type')->nullable();
            $table->string('token',500)->nullable();
            //$table->string('verification_code')->nullable();
            $table->string('forgot_token')->nullable();
            $table->enum('is_active', array('0','1'))->default('0')->comment('0 for Inactive, 1 for active');
            $table->enum('is_account_created', array('0','1'))->default('0')->comment('0 for No, 1 for yes');
            $table->enum('is_verified', array('0','1'))->default('0')->comment('0 for Not Verified, 1 for Verified');
            $table->enum('is_admin', array('0','1'))->default('0')->comment('0 for User, 1 for Admin');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
