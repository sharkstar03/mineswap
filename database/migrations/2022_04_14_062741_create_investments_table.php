<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->text('plan_info')->nullable();
            $table->integer('user_id');
            $table->integer('plan_id');
            $table->string('code',30);
            $table->decimal('price',11,2)->default(0);
            $table->integer('profitable_cycle');
            $table->integer('remaining_cycle');
            $table->string('transaction',30);
            $table->decimal('minimum_profit',22,16)->default(0)->comment('Per day');
            $table->decimal('maximum_profit',22,16)->default(0)->comment('Per day');
            $table->boolean('status')->default(0)->comment(' 1=> running, 2=> complete');
            $table->dateTime('formerly')->useCurrent();
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
        Schema::dropIfExists('investments');
    }
}
