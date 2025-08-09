<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;

class GenericPolicy
{
    use HandlesAuthorization;


    public function create(User $user, $model)
    {
        return $user->hasPermissionTo('create ' . Str::kebab(class_basename($model)));
    }

    public function update(User $user, $model)
    {
        return $user->hasPermissionTo('update ' . Str::kebab(class_basename($model)));
    }

    public function delete(User $user, $model)
    {
        return $user->hasPermissionTo('delete ' . Str::kebab(class_basename($model)));
    }
}