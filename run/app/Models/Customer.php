<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Customer extends Model implements AuthorizableContract, AuthenticatableContract
{
    use HasRoles, Authorizable, Authenticatable;
    use SoftDeletes;
    use \Rutorika\Sortable\SortableTrait;

    protected $guard = 'customers';
    protected $stable = 'customers';
    //設定主鍵
    public $primaryKey = 'id';
    protected static $sortableField = 'item_date';
    protected $fillable = [
        'id',
        'lang',
        'uuid',
        'company_id',
        'account',
        'name',
        'description',
        'username',
        'email',
        'email_verified_at',
        'password',
        'roles',
        'item_date',
        'reason',
        'images',
        'files',
        'verify',
        'is_manager',
        'remember_token',
        'item_status',
        'has_deleted',
        'updated_at',
        'created_at'
    ];

    protected $dates = ['deleted_at'];

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

    public function setPasswordAttribute($pass)
    {

        $this->attributes['password'] = Hash::make($pass);

    }
}
