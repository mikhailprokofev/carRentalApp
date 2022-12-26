<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('color')->change()->nullable();
            $table->string('type')->change()->nullable();
            $table->unique('number_plate');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
//            $table->string('color')->change()->nullable(false);
//            $table->string('type')->change()->nullable(false);
            $table->dropUnique('cars_number_plate_unique');
        });
    }
};
