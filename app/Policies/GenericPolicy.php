<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GenericPolicy
{
    use HandlesAuthorization;

    public function view(User $user, $model)
    {
        $permission = 'view ' . Str::kebab(class_basename($model));
        Log::info("Checking permission: " . $permission);
        return $user->hasPermissionTo($permission);
    }

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
