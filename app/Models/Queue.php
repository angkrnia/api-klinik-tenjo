<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    const SELESAI = 'done';
    const BATAL = 'canceled';
    const OBSERVATION = 'observation';

    protected $table = 'queue_logs';
    protected $fillable = [
        'queue',
        'patient_id',
        'doctor_id',
        'status',
        'is_last_queue',
    ];

    public function history()
    {
        return $this->hasOne(History::class);
    }

    public function observation()
    {
        return $this->hasOne(Observation::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function scopeKeywordSearch(Builder $query, string $searchKeyword): Builder
    {
        return $query->where(function ($query) use ($searchKeyword) {
            // Cari di kolom-kolom tabel Queue
            foreach ($this->getFillable() as $column) {
                $query->orWhere($this->getTable() . '.' . $column, 'LIKE', "%$searchKeyword%");
            }
    
            // Cari di kolom-kolom tabel Patient
            $query->orWhereHas('patient', function ($query) use ($searchKeyword) {
                $query->where(function ($query) use ($searchKeyword) {
                    $columns = (new Patient)->getFillable();
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'LIKE', "%$searchKeyword%");
                    }
                });
            });
    
            // Cari di kolom-kolom tabel History
            $query->orWhereHas('history', function ($query) use ($searchKeyword) {
                $query->where(function ($query) use ($searchKeyword) {
                    $columns = (new History)->getFillable();
                    foreach ($columns as $column) {
                        $query->orWhere($column, 'LIKE', "%$searchKeyword%");
                    }
                });
            });
        });
    }
}
