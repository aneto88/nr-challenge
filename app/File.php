<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'file',
        'type',
        'bidding_id'
    ];

    /**
     * Get the bidding that owns the file.
     */
    public function bidding()
    {
        return $this->belongsTo('App\Bidding');
    }
}
