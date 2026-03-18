<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'Name', 'Email', 'Phone', 'Position', 
        'Institution', 'Category', 'Favorite', 'Notes', 'Created'
    ];

    protected $casts = [
        'Favorite' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'Created');
    }

    public function escalations()
    {
        return $this->hasMany(EscalationLog::class);
    }
}
