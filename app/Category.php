<?php

namespace App;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Uuids;

    protected $fillable = ['name'];

    public function links()
    {
        return $this->hasMany(Link::class);
    }

    public function linksByTitle()
    {
        return $this->hasMany(Link::class)->orderBy('title');
    }

    public function linksByUrl()
    {
        return $this->hasMany(Link::class)->orderBy('url');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
