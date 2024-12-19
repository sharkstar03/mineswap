<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMiningListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mining_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('code',30);
            $table->decimal('minimum_amount',18,8)->default(0)->comment('Payout Limit');
            $table->decimal('maximum_amount',18,8)->default(0)->comment('Payout Limit');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('mining_lists');
    }
}
