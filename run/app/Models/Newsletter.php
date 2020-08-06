<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Nicolaslopezj\Searchable\SearchableTrait;

class Newsletter extends Model
{
    use HasRoles, Authorizable, Authenticatable;
    use SoftDeletes;
    use SearchableTrait;

    protected $guard_name = 'web';
    protected $stable = 'contact_us_replies';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'title',
        'subject',
        'content',
        'attachment',
        'scheduled_time',
        'result',
        'item_status',
        'updated_at',
        'created_at'
    ];

    protected $dates = ['deleted_at'];


}
