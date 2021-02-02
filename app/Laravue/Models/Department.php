<?php

namespace App\Laravue\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    public $guarded = [];
    protected $table = "department";

    public function stores()
    {
    	return $this->belongsToMany(Stores::class);
    }

    public function user()
    {
    	return $this->hasMany(User::class);
    }
}
