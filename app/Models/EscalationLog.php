<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EscalationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'TicketId',
        'site_id',
        'ContactId',
        'Channel',
        'Message',
        'Recipient',
        'Status',
        'Response',  
        'SentDate',
        'Escalated',
    ];

    protected $casts = [
        'SentDate' => 'datetime',
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'TicketId');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'ContactId');
    }

    public function escalator()
    {
        return $this->belongsTo(User::class, 'Escalated');
    }
}
