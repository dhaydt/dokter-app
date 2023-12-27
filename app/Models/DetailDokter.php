<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailDokter extends Model
{
    use HasFactory;

    protected $fillable=[
        "izin_praktek",
        "phone",
        "alamat",
        "code_uniq_users"
    ];

    public $incrementing = false;

    /**
     * Get the user that owns the DetailDokter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'detail_id');
    }
}
