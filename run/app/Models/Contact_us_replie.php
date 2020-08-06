<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;

class Contact_us_replie extends Model
{
    use HasRoles, Authorizable, Authenticatable;
    use SoftDeletes;
    protected $guard_name = 'web';
    protected $stable = 'contact_uses';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'cid',
        'title',
        'subject',
        'content',
        'result',
        'item_status',
        'updated_at',
        'created_at'
    ];

    protected $dates = ['deleted_at'];


}
