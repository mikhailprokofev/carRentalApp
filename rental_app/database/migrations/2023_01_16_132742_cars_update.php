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
        Schema::table('cars', function (Blueprint $table) {
            $table->string('country')->nullable();
            $table->string('brand')->nullable();
            $table->date('manufacture_date')->nullable();
            $table->integer('mileage')->nullable();
            $table->boolean('drive')->nullable();
            $table->boolean('is_right_hand')->nullable();
            $table->string('body_type')->nullable();
            $table->string('transmission')->nullable();
            $table->string('insurance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(
                [
                    'country',
                    'brand',
                    'manufacture_date',
                    'mileage',
                    'drive',
                    'is_right_hand',
                    'body_type',
                    'transmission',
                    'insurance',
                ]
            );
        });
    }
};
