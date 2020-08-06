<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Nicolaslopezj\Searchable\SearchableTrait;

class SeoModule extends Model
{
    use SoftDeletes;
    use SearchableTrait;

    protected $guard_name = 'web';
    protected $stable = 'seo_modules';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'lang',
        'property_name',
        'property_value',
        'content',
        'sequence_number',
        'verify',
        'item_status',
        'updated_at',
        'created_at'
    ];

    protected $searchable = [
        'columns' => [
            'seo_modules.content' => 10
        ]
    ];


    protected $dates = ['deleted_at'];


}
