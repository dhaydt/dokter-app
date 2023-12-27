<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resep extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'dokter_id',
        'tgl_mulai',
        'tgl_selesai',
        'dosis',
        'perhari',
        'note',
        'status_pengobatan',
        'status',
        'code_uniq',
        'code_uniq_dokter',
        'code_uniq_user',
    ];

    /**
     * Get all of the comments for the Resep
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public $incrementing = false;

    public function resep_obat(): HasMany
    {
        return $this->hasMany(ResepObat::class, 'code_uniq_resep', 'code_uniq');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'code_uniq_obat', 'code_uniq');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'code_uniq_user', 'code_uniq');
    }
    public function dokter()
    {
        return $this->belongsTo(User::class, 'code_uniq_dokter', 'code_uniq');
    }

    public function history(){
        return $this->hasMany(History::class);
    }
}
