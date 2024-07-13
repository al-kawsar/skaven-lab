<?php

namespace App\Traits;

use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


trait HasRole
{

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function assignRole(string $role): void
    {
        $role = Role::where('name', $role)->firstOrFail();

        $this->role()->associate($role);
        $this->save();
    }

    public function hasRole(string $role): bool
    {
        return $this->role->name === $role;
    }

    public function can($permission, $arguments = [])
    {
        return $this->role->permissions->contains('name', $permission);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role->name, ['superadmin', 'admin']);
    }
}
