<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::raw(
            'CREATE OR REPLACE FUNCTION trigger_set_timestamp()
            RETURNS TRIGGER AS $$
            BEGIN
              NEW.updated_at = NOW();
              RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;'
        );

        DB::raw(
            'CREATE TRIGGER set_timestamp
            BEFORE UPDATE ON todos
            FOR EACH ROW
            EXECUTE PROCEDURE trigger_set_timestamp();'
        );
    }

    public function down(): void
    {
        // TODO: how to delete procedure and trigger
    }
};
