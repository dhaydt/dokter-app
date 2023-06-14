<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    use HasFactory;

    protected $fillable = [
        "nik",
        "ttl",
        "umur",
        "alamat",
        "kelamin",
        "phone",
        "berat",
        "tinggi",
        "alergi",
    ];

    public function user(){
        return $this->hasOne(User::class, 'detail_id');
    }
}
