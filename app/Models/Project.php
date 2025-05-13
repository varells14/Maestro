<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Nama tabel (jika tabel tidak mengikuti konvensi jamak Laravel, misalnya 'projects')
    protected $table = 'project';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'project',
        'lokasi',
        'date_start',
    ];

    // Konversi kolom tanggal ke instance Carbon
    protected $dates = ['date_start'];

    // Nonaktifkan penggunaan kolom updated_at
    public $timestamps = false;
}
