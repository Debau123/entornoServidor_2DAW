<?php

use App\Http\Requests\RegisterRequest;
use App\Models\Fichero;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Ruta principal con búsqueda de archivos
Route::get('/', function (Request $request) {
    $search = $request->input('search');

    // Consulta para archivos activos
    $ficheros = Fichero::whereNull('deleted_at')
        ->when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })
        ->get();

    // Consulta para archivos eliminados (Soft Deletes)
    $archivosEliminados = Fichero::onlyTrashed()
        ->when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', '%' . $search . '%');
        })
        ->get();

    return view('welcome')->with([
        'ficheros' => $ficheros,
        'archivosEliminados' => $archivosEliminados,
        'search' => $search
    ]);
});

// Login
Route::get('/login', function () {
    return view('login');
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->intended('/');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ])->onlyInput('email');
});

// Logout
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
});

// Register
Route::get('/register', function () {
    return view('register');
});

Route::post('/register', function (RegisterRequest $request) {
    $data = $request->validated();
    $user = new User();
    $user->name = $data['name'];
    $user->email = $data['email'];
    $user->password = Hash::make($data['password']);
    $user->save();
    return redirect('/')->with('status', 'Registro exitoso. Ahora puedes iniciar sesión.');
});

// Subida de archivos
Route::get('/subir-archivos', function () {
    return view('upload');
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::post('/upload', function (Request $request) {
        $file = $request->file('uploaded_file');
        $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());

        $fichero = new Fichero();
        $fichero->path = $file->storeAs('public', $safeName);
        $fichero->name = $safeName;
        $fichero->user_id = Auth::id();
        $fichero->privado = $request->has('privado');
        $fichero->save();

        return redirect('/')->with('status', 'Archivo subido correctamente.');
    });

    // Descargas y registro
    Route::get('/download/{file}', function (Fichero $file) {
        if (!Storage::exists($file->path)) {
            return redirect('/')->withErrors(['El archivo no está disponible físicamente.']);
        }

        $file->increment('descargas');

        DB::table('descargas')->insert([
            'fichero_id' => $file->id,
            'fecha' => now()->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return Storage::download($file->path, $file->name);
    })->name('download');

    // Soft delete
    Route::get('/delete/{file}', function (Fichero $file) {
        $file->delete(); // Solo marca el archivo como eliminado (Soft Delete)
        return redirect('/')->with('status', 'Archivo eliminado temporalmente.');
    });

    // Actualización de archivos
    Route::put('/archivo/{id}', function (Request $request, $id) {
        $fichero = Fichero::findOrFail($id);

        if (Str::endsWith($fichero->name, ['.txt', '.md'])) {
            Storage::put($fichero->path, $request->input('content'));
        } elseif (Str::endsWith($fichero->name, ['.docx'])) {
            $tempPath = Storage::path($fichero->path);
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addText($request->input('content'));

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($tempPath);
        }

        return redirect()->back()->with('status', 'Archivo actualizado correctamente.');
    })->name('archivo.update');
});

// Vista individual del archivo
Route::get('/archivo/{id}', function ($id) {
    $fichero = Fichero::findOrFail($id);

    if ($fichero->privado && $fichero->user_id != Auth::id()) {
        return redirect('/')->withErrors(['Archivo no encontrado o no tienes permisos para verlo.']);
    }

    $descargasPorDia = DB::table('descargas')
        ->select(DB::raw('fecha, COUNT(*) as total'))
        ->where('fichero_id', $fichero->id)
        ->groupBy('fecha')
        ->orderBy('fecha')
        ->get();

    return view('archivo', [
        'fichero' => $fichero,
        'descargasPorDia' => $descargasPorDia,
    ]);
})->name('archivo.view');

// Generar QR de descarga
Route::get('/qr/download/{id}', function ($id) {
    $fichero = Fichero::findOrFail($id);
    if (!Storage::exists($fichero->path)) {
        return redirect('/')->withErrors(['El archivo no está disponible para generar el QR.']);
    }
    $url = route('download', ['file' => $fichero->id]);
    return QrCode::size(300)->generate($url);
})->name('qr.download');

// Restaurar archivo eliminado
Route::get('/restore/{id}', function ($id) {
    $fichero = Fichero::onlyTrashed()->findOrFail($id);
    $fichero->restore(); // Restaura solo en la base de datos
    return redirect('/')->with('status', 'Archivo restaurado correctamente.');
})->name('restore');

// Eliminar permanentemente archivo
Route::get('/force-delete/{id}', function ($id) {
    $fichero = Fichero::onlyTrashed()->findOrFail($id);

    // Elimina el archivo físicamente si existe
    if (Storage::exists($fichero->path)) {
        Storage::delete($fichero->path);
    }

    $fichero->forceDelete(); // Elimina permanentemente la entrada en la base de datos
    return redirect('/')->with('status', 'Archivo eliminado permanentemente.');
})->name('force-delete');
// Ver PDF
Route::get('/ver-pdf/{id}', function ($id) {
    $fichero = Fichero::findOrFail($id);

    if ($fichero->privado && $fichero->user_id != Auth::id()) {
        return redirect('/')->withErrors(['Archivo no encontrado o no tienes permisos para verlo.']);
    }

    $filePath = Storage::path($fichero->path);

    if (!Storage::exists($fichero->path)) {
        abort(404, 'El archivo no existe.');
    }

    return response()->file($filePath);
})->name('ver.pdf');
code .