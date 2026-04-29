<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketImage extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'TicketId',
        'site_id',
        'Path',
        'Description',
        'Order',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'TicketId', 'id');
    }
}
