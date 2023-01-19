<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crashes', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));

            $table->foreignUuid('car_id')
                ->nullable(false)
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->dateTime('crashed_at');

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();

            $table->unique(['car_id', 'crashed_at'], 'crash_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crashes');
    }
};
