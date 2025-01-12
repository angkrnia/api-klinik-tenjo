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
        DB::unprepared("DROP PROCEDURE IF EXISTS GET_LIST_ANTRIAN");
        $sql = "
        CREATE PROCEDURE GET_LIST_ANTRIAN()
        BEGIN
   DECLARE last_queue_id INT;

   -- Mendapatkan ID dari is_last_queue terakhir
   SELECT id INTO last_queue_id
   FROM queue_logs
   WHERE is_last_queue = true
   ORDER BY created_at DESC
   LIMIT 1;

   IF last_queue_id IS NOT NULL THEN
       SELECT q.queue, q.status,
              p.fullname AS patient_name,
              d.fullname AS doctor_name, q.created_at
       FROM queue_logs AS q
       JOIN patients AS p ON q.patient_id = p.id
       JOIN doctors AS d ON q.doctor_id = d.id
       WHERE q.id > last_queue_id
         AND q.status NOT IN ('done', 'completed');
   ELSE
       SELECT 0;
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
