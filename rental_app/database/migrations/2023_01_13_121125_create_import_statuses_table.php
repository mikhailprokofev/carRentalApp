<?php

use App\Module\Import\Enum\ImportStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('uuid_generate_v4()'));
            $table->enum('status', ImportStatusEnum::toArray())->default(ImportStatusEnum::BEGIN->value);
            $table->unsignedInteger('duplicated_rows')->default(0);
            $table->unsignedInteger('validated_rows')->default(0);
            $table->unsignedInteger('read_rows')->default(0);
            $table->unsignedInteger('inserted_rows')->default(0);
            $table->string('filename', 256);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->unique('filename');
        });
    }

    public function down(): void
    {
        Schema::table('import_statuses', function (Blueprint $table) {
            $table->dropUnique('import_statuses_filename_unique');
        });
        Schema::dropIfExists('import_statuses');
    }
};
