<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesignComment extends Model
{
    //
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body', 'design_id', 'user_id'
    ];

    public function design()
    {
        return $this->belongsTo(Design::class,'design_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function replies()
    {
        return $this->hasMany(CommentReply::class,'comment_id');
    }

}
