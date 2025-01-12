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
        DB::unprepared("DROP PROCEDURE IF EXISTS GET_SISA_ANTRIAN_SAYA");
        $sql = "
        CREATE PROCEDURE GET_SISA_ANTRIAN_SAYA()
        BEGIN
	SELECT (queue - 13) AS sisa_antrian
	FROM queue_logs
	WHERE id > (
	    SELECT id
	    FROM queue_logs
	    WHERE is_last_queue = true
	    ORDER BY created_at DESC
	    LIMIT 1
	) AND patient_id = 5 AND (status = 'waiting' OR status = 'on waiting');
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
