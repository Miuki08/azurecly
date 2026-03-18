<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'TicketId', 'ContactId', 'Channel', 'Message', 
        'Recipient', 'Status', 'response', 'SentDate', 'Escalated'
    ];

    protected $casts = [
        'SentDate' => 'datetime',
    ];
    
    /**
     * Foregn Key to ticket, contact, and user
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function escalator()
    {
        return $this->belongsTo(User::class, 'Escalated');
    }
}
