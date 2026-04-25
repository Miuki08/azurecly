<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    
    protected $fillable = ['Name', 'Code', 'Type', 'site_id'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'RegionId');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function scopeForSite($query, Site|int $site)
    {
        $siteId = $site instanceof Site ? $site->id : $site;
        return $query->where('site_id', $siteId);
    }
    
}
