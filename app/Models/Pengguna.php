<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    use HasFactory;

    protected $table = 'pengguna';
    protected $primaryKey = 'id_user';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'nama_opd',
        'email',
        'password',
        'role',
        'created_by',
        'unit_kerja',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = true;

    /**
     * Cek apakah user adalah Inspektorat
     */
    public function isInspektorat()
    {
        return $this->unit_kerja === 'Inspektorat' || 
               str_contains(strtolower($this->unit_kerja), 'inspektorat');
    }

    /**
     * Cek apakah user bisa mengedit data
     */
    public function canEditData()
    {
        // Semua OPD bisa edit kecuali Inspektorat
        return !$this->isInspektorat();
    }

    /**
     * Cek apakah user bisa menambah data
     */
    public function canAddData()
    {
        // Semua OPD bisa tambah kecuali Inspektorat
        return !$this->isInspektorat();
    }

    /**
     * Cek apakah user bisa menghapus data
     */
    public function canDeleteData()
    {
        // Semua OPD bisa hapus kecuali Inspektorat
        return !$this->isInspektorat();
    }
}