<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Module\Car\Enum as Enums;

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
            $table->integer('manufacture_date')->nullable();
            $table->integer('mileage')->nullable();
            $table->enum('drive', Enums\Drive::toArray())->nullable();
            $table->enum('control',Enums\Control::toArray())->nullable();
            $table->enum('body_type',Enums\BodyType::toArray())->nullable();
            $table->enum('transmission', Enums\Transmission::toArray())->nullable();
            $table->enum('insurance', Enums\Insurance::toArray())->nullable();
            $table->enum('class', Enums\Type::toArray())->nullable();
            $table->dropColumn('type');
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
                    'control',
                    'body_type',
                    'transmission',
                    'insurance',
                    'class',
                ]
            );
            $table->string('type');
        });
    }
};
