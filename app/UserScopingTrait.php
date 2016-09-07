<?php

namespace App;

use Illuminate\Support\Facades\Log;

trait UserScopingTrait
{

    /**
     * Boot the soft deleting trait for a model.
     *
     * @return void
     */
    public static function bootUserScopingTrait()
    {
        static::addGlobalScope(new UserScope);
    }

}
