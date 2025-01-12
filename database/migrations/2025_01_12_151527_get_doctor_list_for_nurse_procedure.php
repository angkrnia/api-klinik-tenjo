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
        DB::unprepared("DROP PROCEDURE IF EXISTS GET_DOCTOR_LIST_FOR__NURSE");
        $sql = "
        CREATE PROCEDURE GET_DOCTOR_LIST_FOR__NURSE()
        BEGIN
    SELECT 
        d.id AS id, 
        d.fullname AS fullname,
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM doctor_schedules s 
                WHERE s.doctor_id = d.id 
                  AND s.day = UPPER(day) 
                  AND current_time BETWEEN s.start_time AND s.end_time 
                  AND s.status = 1
            ) THEN true 
            ELSE false 
        END AS is_on_duty
    FROM 
        doctors d;
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
