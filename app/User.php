<?php

namespace App;

use App\Model\Archivo;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_pic'
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

    public function visible_files()
    {
       return $this->belongsToMany(Archivo::class, 'file_viewer');
    }
    
    public function mis_files()
    {
      return $this->hasMany(Archivo::class);
    }

    public function getProfilePicURL()
    {
      if($this->profile_pic) {
        return url('storage/'.$this->profile_pic);
      } else {
        return "/images/mandarina.svg";
      }
 
    }
}
