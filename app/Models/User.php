<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\CPU\Helpers;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $fillable = [
        'name',
        'email',
        'detail_id',
        'user_is',
        'password',
        'code_uniq'
    ];

    protected $appends = [
        'code_uniq_formatted'
    ];

    public function getCodeUniqFormattedAttribute()
    {
        return Helpers::generateCodeUniq($this->id, $this->user_is);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessFilament(): bool
    {
        // dd($this);
        // return true;
        return $this->hasRole(['admin', 'dokter']);
    }

    public function detailUser()
    {
        return $this->belongsTo(DetailUser::class, 'code_uniq', 'id');
    }

    public function detailDokter()
    {
        return $this->belongsTo(DetailDokter::class, 'code_uniq', 'id');
    }
}
