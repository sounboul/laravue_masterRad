<?php

namespace App\Laravue\Models;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function article()
    {
    	return $this->hasMany(Articles::class);
    }

    public function users()
    {
    	return $this->hasOne(User::class);
    }
}
