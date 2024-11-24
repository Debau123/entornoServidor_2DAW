<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    /**
     * Determina si el usuario tiene acceso al panel de administración.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function accessAdminPanel(User $user)
    {
        // Comprueba si el usuario tiene rol de administrador
        return $user->isAdmin();
    }

    /**
     * Determina si el usuario puede ver cualquier recurso en el panel de administración.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        // Comprueba si el usuario tiene rol de administrador
        return $user->isAdmin();
    }
}
