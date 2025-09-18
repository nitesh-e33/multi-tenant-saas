<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    protected $fillable = ['name','email','password','active_company_id'];

    protected $hidden = ['password','remember_token'];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function activeCompany()
    {
        return $this->belongsTo(Company::class, 'active_company_id');
    }

    // Helper to return active company object or null
    public function currentCompany()
    {
        // Eager-loaded if possible
        return $this->activeCompany()->first();
    }
}
