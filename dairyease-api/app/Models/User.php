<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use HasFactory,Notifiable, HasApiTokens;
    protected $table ="users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','contact', 'password','address','profile_photo'
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
    public function milkDetails()
    {
        return $this->hasMany(MilkDetail::class);
    }
    public function expensesDetails()
    {
        return $this->hasMany(expensesDetail::class);
    }

 

    
    public function getProfileImageUrlAttribute(){
        if($this->profile_photo){
            return asset('/uploads/profile_images/'.$this-> profile_photo);
        }
        else{
            return 'https://ui-avatars.com/api/?background=random&name='.urlencode($this->name);
        }
    }

    public $appends=[
        'profile_image_url',
    ];
}
