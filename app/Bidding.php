<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bidding extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'object',
        'date_open',
        'source'
    ];

    /**
     * Get the files for the bidding.
     */
    public function files()
    {
        return $this->hasMany('App\File');
    }
    
}
