<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Role;
use App\Models\Fichero; // Cambiado a Fichero
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Obtiene todos los usuarios con sus roles
        $users = User::with('role')->get();

        // Obtiene todos los roles disponibles
        $roles = Role::all();

        // Obtiene todos los ficheros con sus usuarios propietarios
        $files = Fichero::with('user')->get();

        // Retorna la vista del panel de administración con los usuarios, roles y ficheros
        return view('admin.dashboard', compact('users', 'roles', 'files'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Usuario eliminado correctamente');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        // Valida los datos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
            'password' => 'nullable|min:6', // Valida la contraseña solo si es enviada
        ]);
      
        // Actualiza los datos del usuario
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
    
        // Si se proporciona una nueva contraseña, la ciframos antes de guardarla
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
    
        $user->save();
    
        return redirect()->route('admin.dashboard')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroyFile($id)
    {
        // Elimina un fichero por su ID
        $file = Fichero::findOrFail($id);
        $file->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Archivo eliminado correctamente');
    }
}
