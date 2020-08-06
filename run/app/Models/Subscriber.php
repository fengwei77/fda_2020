<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Nicolaslopezj\Searchable\SearchableTrait;

class Subscriber extends Model
{
    use HasRoles, Authorizable, Authenticatable;
    use SoftDeletes;
    use SearchableTrait;

    protected $guard_name = 'web';
    protected $stable = 'subscribers';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'name',
        'email',
        'item_status',
        'updated_at',
        'created_at'
    ];

    protected $searchable = [
        'columns' => [
            'subscribers.email' => 10
        ]
    ];


    protected $dates = ['deleted_at'];


}
