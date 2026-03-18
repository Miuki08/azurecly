<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'Title', 'Description', 'Sentiment', 'Actor', 'Category', 
        'Priority', 'Tag', 'Location', 'Latitude', 'Longitude',
        'ViewCount', 'PublishedDate', 'escalatedDate', 'Created'
    ];

    protected $casts = [
        'PublishedDate' => 'datetime',
        'EscalatedDate' => 'datetime',
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
