<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'Title', 'Description', 'Sentiment', 'Actor', 'Category', 
        'Priority', 'Tag', 'Region', 'Location', 'Latitude', 'Longitude',
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
        return $this->hasMany(EscalationLog::class, 'ticketId');
    }

    public function scopeSentiment($query, $Sentiment)
    {
        if ($Sentiment) {
            return $query->where('Sentiment', $Sentiment);
        }
        return $query;
    }

    public function scopePriority($query, $Priority)
    {
        if ($Priority) {
            return $query->where('Priority', $Priority);
        }
        return $query;
    }

    public function scopeCategory($query, $Category)
    {
        if ($Category) {
            return $query->where('Category', $Category);
        }
        return $query;
    }
        
    public function scopeHighPriorityNegative($query)
    {
        return $query->where('Priority', 'high')
                     ->where('Sentiment', 'negative');
    }

}
