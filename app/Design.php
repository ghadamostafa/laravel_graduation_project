<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Design extends Model
{

    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'title', 'price','category','source_file','designer_id','tag_id'
    ];

    //
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function scopeAccepted()
    {
        return $this->where('is_verified','=','accepted');
    }

    public function designer()
    {
        return $this->belongsTo(User::class,'designer_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class,'company_id');
    }

    public function comments()
    {
        return $this->hasMany(DesignComment::class,'design_id');
    }

    public function images()
    {
        return $this->hasMany(DesignImage::class,'design_id');
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class,'design_materials');
    }

    public function votes()
    {
        return $this->hasMany(DesignVote::class,'design_id');
    }
    
}
