<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    use HasFactory;

    public $incrementing = false;

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
        "code_uniq_users"
    ];

    public function user(){
        return $this->hasOne(User::class, 'code_uniq');
    }
}
