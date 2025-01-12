<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS GET_CHECK_IS_ALL_DONE");

        $sql = "
        CREATE PROCEDURE GET_CHECK_IS_ALL_DONE()
        BEGIN
DECLARE last_queue_id INT;
DECLARE result BOOLEAN;

    -- Mendapatkan ID dari is_last_queue terakhir
    SELECT id INTO last_queue_id
    FROM queue_logs
    WHERE is_last_queue = TRUE
    ORDER BY created_at DESC
    LIMIT 1;

    -- Memeriksa status setelah last_queue_id
    IF last_queue_id IS NOT NULL THEN
        IF (SELECT COUNT(*)
            FROM queue_logs
            WHERE id > last_queue_id
            AND status NOT IN ('done', 'completed')) > 0 THEN
            SET result = FALSE; 
        ELSE
            SET result = TRUE;
        END IF;
    ELSE
        SET result = TRUE;  -- Jika tidak ada queue terakhir, anggap TRUE
    END IF;
    
    SELECT result;
END";

        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
