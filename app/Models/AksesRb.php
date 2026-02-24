<?php
// app/Models/AksesRb.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AksesRb extends Model
{
    use HasFactory;

    protected $table = 'akses_rb';

    protected $fillable = [
        'jenis_rb',
        'status',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'status' => 'string',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Accessor untuk kompatibilitas dengan view lama yang masih pakai is_open
     */
    public function getIsOpenAttribute()
    {
        return $this->status === 'Dibuka';
    }

    /**
     * Cek apakah akses sedang terbuka
     */
    public function isAccessible()
    {
        if ($this->status !== 'Dibuka') {
            return false;
        }

        $now = now()->startOfDay();
        
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Dapatkan status akses dalam format teks (untuk tampilan)
     */
    public function getStatusTextAttribute()
    {
        if ($this->status !== 'Dibuka') {
            return 'Tertutup';
        }

        $now = now()->startOfDay();

        if ($this->start_date && $now->lt($this->start_date)) {
            return 'Akan Dibuka';
        }

        if ($this->end_date && $now->gt($this->end_date)) {
            return 'Telah Ditutup';
        }

        return 'Terbuka';
    }

    /**
     * Dapatkan badge untuk status
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status === 'Dibuka'
            ? '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Dibuka</span>'
            : '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Ditutup</span>';
    }
}