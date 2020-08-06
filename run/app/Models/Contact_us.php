<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;

class Contact_us extends Model
{
    use HasRoles, Authorizable, Authenticatable;
    use SoftDeletes;
    protected $guard_name = 'web';
    protected $stable = 'contact_uses';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'phone',
        'email',
        'gender',
        'country',
        'district',
        'address',
        'company',
        'item_date',
        'question_id',
        'subject',
        'content',
        'reply_member',
        'reply_content',
        'reply_date',
        'updated_at',
        'created_at'
    ];

    protected $dates = ['deleted_at'];


}
