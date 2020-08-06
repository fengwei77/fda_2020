<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Nicolaslopezj\Searchable\SearchableTrait;

class Newsletter_tester extends Model
{
    use HasRoles, Authorizable, Authenticatable;
    use SoftDeletes;
    use SearchableTrait;

    protected $guard_name = 'web';
    protected $stable = 'newsletter_testers';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'newsletter_id',
        'name',
        'email',
        'updated_at',
        'created_at'
    ];

    protected $dates = ['deleted_at'];

}
