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
        DB::unprepared("DROP PROCEDURE IF EXISTS GET_ANTRIAN_SAAT_INI");

        $sql = "
        CREATE PROCEDURE GET_ANTRIAN_SAAT_INI()
        BEGIN
   DECLARE last_queue_id INT;
   DECLARE antrian_saat_ini INT;
   DECLARE doctor_fullname VARCHAR(255);

   -- Mendapatkan ID dari is_last_queue terakhir
   SELECT id INTO last_queue_id
   FROM queue_logs
   WHERE is_last_queue = true
   ORDER BY created_at DESC
   LIMIT 1;

   -- Antrian yang sedang ditangani dokter
   IF last_queue_id IS NOT NULL THEN
       SELECT IFNULL(
         (SELECT queue 
          FROM queue_logs 
          WHERE status = 'on process' AND id > last_queue_id 
          LIMIT 1),
         (SELECT queue 
          FROM queue_logs 
          WHERE status = 'done' AND id > last_queue_id 
          ORDER BY queue DESC 
          LIMIT 1)
        ) INTO antrian_saat_ini;
   ELSE
       SELECT IFNULL(
         (SELECT queue 
          FROM queue_logs 
          WHERE status = 'on process' 
          LIMIT 1),
         (SELECT queue 
          FROM queue_logs 
          WHERE status = 'done' 
          ORDER BY queue DESC 
          LIMIT 1)
        ) INTO antrian_saat_ini;
   END IF;

   -- Mendapatkan fullname dari dokter berdasarkan antrian yang ditemukan
   SELECT d.fullname INTO doctor_fullname
   FROM queue_logs q
   JOIN doctors d ON q.doctor_id = d.id
   WHERE q.id > last_queue_id AND q.queue = antrian_saat_ini
   LIMIT 1;

   -- Menampilkan antrian saat ini dan nama dokter
   SELECT antrian_saat_ini, doctor_fullname;

END;
    ";

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
