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
        DB::unprepared("DROP PROCEDURE IF EXISTS GET_SISA_ANTRIAN_ALL");
        $sql = "
        CREATE PROCEDURE GET_SISA_ANTRIAN_ALL()
        BEGIN
   DECLARE last_queue_id INT;

   -- Mendapatkan ID dari is_last_queue terakhir
   SELECT id INTO last_queue_id
   FROM queue_logs
   WHERE is_last_queue = true
   ORDER BY created_at DESC
   LIMIT 1;

   -- Menghitung sisa antrian berdasarkan statusnya 'waiting' atau 'on waiting'
   IF last_queue_id IS NOT NULL THEN
       SELECT COUNT(id) AS sisa_antrian
       FROM queue_logs
       WHERE (status = 'waiting' OR status = 'on waiting') AND id > last_queue_id;
   ELSE
       SELECT COUNT(id) AS sisa_antrian
       FROM queue_logs
       WHERE status = 'waiting' OR status = 'on waiting';
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
