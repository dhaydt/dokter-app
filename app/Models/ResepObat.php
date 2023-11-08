<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResepObat extends Model
{
    use HasFactory;

    protected $fillable = ['resep_id', 'obat_id', 'tablet', 'code_uniq_resep', 'code_uniq_obat'];

    /**
     * Get the user that owns the ResepObat
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resep(): BelongsTo
    {
        return $this->belongsTo(Resep::class,'resep_id');
    }

    public function obat(){
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
