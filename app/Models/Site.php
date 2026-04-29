<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo_path',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function escalationLogs()
    {
        return $this->hasMany(EscalationLog::class);
    }
    
}
