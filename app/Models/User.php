<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'site_id',
        'email',
        'password',
        'role',
        'FaceDescription',
        'avatar_path', 
        'last_login_at', 
        'last_login_ip',
        'last_profile_update_at',
    ];

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
        'FaceDescription' => 'array',
        'last_login_at' => 'datetime',
        'last_profile_update_at' => 'datetime',
    ];

    /**
     * Role Authenticate
     */
    public function Admin(): bool
    {
        return $this->role === 'admin';
    }

    public function Humas(): bool
    {
        return $this->role === 'humas';
    }

    public function Media(): bool
    {
        return $this->role === 'media';
    }

    
    /**
     * 
     * JWT Function digunakan untuk inisial JWT
     * 
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'role' => $this->role,
            'name' => $this->name
        ];
    }

    /**
     * Relationship
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'Created');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'Created');
    }

    public function escalations()
    {
        return $this->hasMany(EscalationLog::class, 'Escalated');
    }

    /** 
     * Helper untuk avatar
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar_path 
            ? asset('storage/' . $this->avatar_path) 
            : 'https://ui-avatars.com/api/' . urlencode($this->name);
    }

    /** 
     * Helper untuk cek face registered
     */
    public function hasFace(): bool
    {
        return is_array($this->FaceDescription) && count($this->FaceDescription) > 0;
    }

    /** 
     * Helper untuk update last login
     */
    public function recordLogin($request)
    {
        $this->last_login_at = now();
        $this->last_login_ip = $request->ip();
        $this->save();
    }

    /** 
     * Helper untuk update last profile update
     */
    public function recordProfileUpdate()
    {
        $this->last_profile_update_at = now();
        $this->save();
    }
}
