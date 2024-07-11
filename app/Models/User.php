<?php

namespace App\Models;

use App\program_array;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }
    public static function has_access($privilege){
        try {
            $flag=false;
            if(strtolower($privilege)=="settings"){
                foreach (program_array::program_array()['settings'] as $key=>$val){
                    return $val;
                }
            }
            foreach (json_decode(Auth::user()->type) as $i=>$authority){
                if($privilege==$authority){
                    $flag=true;
                }
            }
            return $flag;
        }catch (\Exception $e){
            Log::error(__FUNCTION__ . " : " . __CLASS__ . ' : '.$e->getMessage());
        }
    }


}
