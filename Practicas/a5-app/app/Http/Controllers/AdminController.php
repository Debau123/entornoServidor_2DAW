<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Verifica que el usuario autenticado tiene permiso para acceder al panel de administración
        // if (Gate::denies('view-admin-dashboard')) {
        //     abort(403, 'Unauthorized action.');
        // }

        // Obtiene todos los usuarios con sus roles
        $users = User::with('role')->get();

        // Retorna la vista del panel de administración con los usuarios
        return view('admin.dashboard', compact('users'));
    }
}
