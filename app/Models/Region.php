<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    
    protected $fillable = ['Name', 'Code', 'Type'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'RegionId');
    }
    
}
