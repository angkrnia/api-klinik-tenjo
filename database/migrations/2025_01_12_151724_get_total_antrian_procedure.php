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
        DB::unprepared("DROP PROCEDURE IF EXISTS GET_TOTAL_ANTRIAN");
        $sql = "
        CREATE PROCEDURE GET_TOTAL_ANTRIAN()
        BEGIN
   DECLARE last_queue_id INT;

   -- Mendapatkan ID dari is_last_queue terakhir
   SELECT id INTO last_queue_id
   FROM queue_logs
   WHERE is_last_queue = true
   ORDER BY created_at DESC
   LIMIT 1;

   -- Menghitung jumlah antrian setelah is_last_queue terakhir
   IF last_queue_id IS NOT NULL THEN
       SELECT COUNT(id) AS total_antrian
       FROM queue_logs
       WHERE id > last_queue_id;
   ELSE
       SELECT COUNT(id) AS total_antrian
       FROM queue_logs;
   END IF;
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
