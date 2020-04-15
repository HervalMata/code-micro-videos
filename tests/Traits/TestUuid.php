<?php
/**
 * Created by PhpStorm.
 * User: Herval
 * Date: 11/04/2020
 * Time: 16:15
 */

namespace Tests\Traits;


trait TestUuid
{
    protected function isUuid($uuid)
    {
        return is_string($uuid) && (bool)preg_match('/^[a-f0-9]{8,8}-(?:[a-f0-9]{4,4}-){3,3}[a-f0-9]{12,12}$/i', $uuid);
    }
}
