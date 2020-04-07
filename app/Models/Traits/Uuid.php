<?php
/**
 * Created by PhpStorm.
 * User: Herval
 * Date: 06/04/2020
 * Time: 23:34
 */

namespace App\Models\Traits;

use Ramsey\Uuid\Uuid as RamseyUuid;

trait Uuid
{
    public static function boot()
    {
        parent::boot();
        static::creating(function ($obj) {
            $obj->id = RamseyUuid::uuid4();
        });
    }
}
