<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'resep_id',
        'hari_ke',
        'tanggal',
        'waktu_minum',
        'img',
        'status',
    ];

    public function resep(){
        return $this->belongsTo(Resep::class, 'resep_id', 'id');
    }
}
