<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Mahasiswa;
use App\Models\Jadwal;

class Matakuliah extends Model
{
    use HasFactory;

    protected $table = 'matakuliah';
    protected $fillable = ['nama_matkul','sks','jam'];
    
    public function mahasiswa(){
        return $this->belongsToMany(Mahasiswa::class)->withPivot('nilai');
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }
}
