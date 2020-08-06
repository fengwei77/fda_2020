<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Spatie\Permission\Traits\HasRoles;

class Mailing_record extends Model
{
    use HasRoles, Authorizable, Authenticatable;

    protected $guard_name = 'web';
    protected $stable = 'mailing_records';
    //設定主鍵
    public $primaryKey = 'id';
    protected $fillable = [
        'id',
        'newsletter_id',
        'subscriber_id',
        'item_status',
        'updated_at',
        'created_at'
    ];

}
