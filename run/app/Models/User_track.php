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

class User_track extends Model
{
    use SoftDeletes;
    use \Rutorika\Sortable\SortableTrait;

    protected $stable = 'user_tracks';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'ip',
        'long_ip',
        'ref_uuid',
        'tags',
        'sort_number',
        'item_status',
        'has_deleted',
        'updated_at',
        'created_at',
    ];

    protected $dates = ['deleted_at'];

}
