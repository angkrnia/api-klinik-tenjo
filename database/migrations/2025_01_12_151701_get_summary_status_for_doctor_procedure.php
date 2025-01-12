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
        DB::unprepared("DROP PROCEDURE IF EXISTS GET_SUMMARY_STATUS_FOR_DOCTOR");
        $sql = "
        CREATE PROCEDURE GET_SUMMARY_STATUS_FOR_DOCTOR()
        BEGIN
   DECLARE last_queue_id INT;
   DECLARE menunggu INT;
   DECLARE belum_vital_sign INT;
   DECLARE terlewat INT;
   DECLARE selesai INT;

   -- Mendapatkan ID dari is_last_queue terakhir
   SELECT id INTO last_queue_id
   FROM queue_logs
   WHERE is_last_queue = true
   ORDER BY created_at DESC
   LIMIT 1;

   IF last_queue_id IS NOT NULL THEN
      -- Menghitung jumlah antrian yang masih menunggu setelah antrian terakhir yang sedang ditangani dokter
      SELECT COUNT(*) INTO menunggu
      FROM queue_logs
      WHERE id > last_queue_id AND status = 'waiting' OR STATUS = 'on process';

      -- Menghitung jumlah antrian yang belum dilakukan vital sign
      SELECT COUNT(*) INTO belum_vital_sign
      FROM queue_logs
      WHERE id > last_queue_id AND status = 'on waiting';

      -- Menghitung jumlah antrian yang terlewat
      SELECT COUNT(*) INTO terlewat
      FROM queue_logs
      WHERE id > last_queue_id AND status = 'skiped';

      -- Menghitung jumlah antrian yang sudah selesai
      SELECT COUNT(*) INTO selesai
      FROM queue_logs
      WHERE id > last_queue_id AND status = 'done';
   END IF;

   -- Menampilkan hasil jumlah antrian berdasarkan status
   SELECT menunggu,
          belum_vital_sign,
          terlewat,
          selesai;

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
