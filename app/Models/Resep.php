<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resep extends Model
{
    use HasFactory;

    protected $fillable = [
        'obat_id',
        'user_id',
        'dokter_id',
        'tgl_mulai',
        'tgl_selesai',
        'dosis',
        'perhari',
        'note',
        'status_pengobatan',
        'status'
    ];

    /**
     * Get all of the comments for the Resep
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resep_obat(): HasMany
    {
        return $this->hasMany(ResepObat::class, 'resep_id', 'id');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function history(){
        return $this->hasMany(History::class);
    }
}
