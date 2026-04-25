<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $fillable = ['Name', 'Slug', 'Description', 'site_id'];

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'CategoryId');
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
