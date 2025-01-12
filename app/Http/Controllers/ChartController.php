<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\History;
use App\Models\Patient;
use App\Models\Queue;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function summaryData(Request $request)
    {
        $totalPatient = Patient::count();
        $totalQueue = Queue::count();
        $totalDoctor = Doctor::count();
        $totalUsers = User::count();

        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => [
                'total_patients' => $totalPatient,
                'total_queues' => $totalQueue,
                'total_doctors' => $totalDoctor,
                'total_users' => $totalUsers
            ]
        ]);
    }

    public function pasienByDate(Request $request)
    {
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        // Ambil jumlah pasien dari tabel Patient dalam rentang waktu 30 hari terakhir
        $data = Patient::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', now()->subDays(30)) // Filter data pasien hanya dalam 30 hari terakhir
            ->groupBy(DB::raw('DATE(created_at)')) // Grouping berdasarkan tanggal
            ->orderBy(DB::raw('DATE(created_at)'))
            ->get()
            ->keyBy('date'); // Buat key berdasarkan tanggal

        // Pastikan semua tanggal tetap ada dalam hasil (meskipun jumlahnya 0)
        $result = $dates->map(function ($date) use ($data) {
            return [
                'date' => Carbon::parse($date)->format('j M y'), // Format tanggal menjadi "1 Dec 24", dll
                'total' => $data[$date]->total ?? 0, // Jika tidak ada data, totalnya 0
            ];
        });

        // Kirim sebagai response JSON
        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => $result,
        ]);
    }

    public function kunjunganByDate(Request $request)
    {
        $dates = collect();
        for ($i = 29; $i >= 0; $i--) {
            $dates->push(now()->subDays($i)->format('Y-m-d'));
        }

        // Ambil data dari model Queue
        $data = Queue::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)', 'asc'))
            ->get()
            ->keyBy('date');

        // Gabungkan semua tanggal dengan data Queue
        $history = $dates->map(function ($date) use ($data) {
            return [
                'date' => Carbon::parse($date)->format('j M y'), // Format tanggal
                'total' => $data[$date]->total ?? 0,            // Ambil total, default 0 jika tidak ada data
            ];
        });

        // Return response sebagai JSON
        return response()->json([
            'code' => 200,
            'status' => true,
            'data' => $history,
        ]);
    }
}
