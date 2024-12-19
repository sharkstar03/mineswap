<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_plans', function (Blueprint $table) {
            $table->id();
            $table->integer('mining_id');
            $table->string('name',50);
            $table->decimal('price',11,2)->default(0);
            $table->integer('hash_rate_speed')->nullable();
            $table->string('hash_rate_unit',50)->nullable();
            $table->decimal('minimum_profit',22,16)->default(0)->comment('Per day');
            $table->decimal('maximum_profit',22,16)->default(0)->comment('Per day');
            $table->integer('duration');
            $table->text('period');
            $table->integer('referral_percent')->default(0);
            $table->string('image',50)->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('featured')->default(0);
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
        Schema::dropIfExists('mining_plans');
    }
}
