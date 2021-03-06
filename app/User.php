<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use Billable;

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.' . $this->id;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','address','phone','image','role',
        'provider_id', 'provider','access_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function designs()
    {
        return $this->hasMany(Design::class,'designer_id');
    }

    public function company_designs()
    {
        return $this->hasMany(CompanyDesign::class,'company_id');
    }

    public function purchased_designs()
    {
        return $this->hasMany(Design::class,'company_id');
    }
    
    public function designer_rates()
    {
        return $this->hasMany(DesignerRate::class,'designer_id');
    }

    public function my_likes()
    {
        return $this->hasMany(DesignerRate::class,'liker_id');
    }
    
    public function profile()
    {
        return $this->hasOne(Profile::class,'user_id');
    }
    public function balance()
    {
        return $this->hasOne(DesignersBalance::class,'designer_id');
    }

    public function withdraw_requests()
    {
        return $this->hasMany(WithdrawRequest::class,'designer_id');
    }

    public function withdraw_requests_by_state($state)
    {
        return $this->withdraw_requests->where('state','=',$state);
    }

    public function last_withdraw_request()
    {
        return $this->withdraw_requests()->latest()->first();
    }
    

    public function my_votes()
    {
        return $this->hasMany(DesignVote::class,'user_id');
    }
    public static function boot() {
        parent::boot();

        static::deleting(function($user) { // before delete() method call this
             $user->profile()->delete();
        });
    }
    

}
