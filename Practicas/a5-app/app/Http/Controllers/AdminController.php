<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Obtiene todos los usuarios con sus roles
        $users = User::with('role')->get();

        // Retorna la vista del panel de administración con los usuarios
        return view('admin.dashboard', compact('users'));
    }
}
