<?php

namespace App\Laravue\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    protected $guarded = [];

    public function categories()
    {
    	return $this->belongsTo(Categories::class);
    }

    public function store()
    {
    	return $this->belongsToMany(Stores::class);
    }

    public function color()
    {
    	return $this->belongsToMany(Colors::class);
    }
}
