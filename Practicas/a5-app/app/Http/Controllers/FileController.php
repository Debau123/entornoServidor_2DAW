<?php

namespace App\Http\Controllers;

use App\Models\Fichero;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FileController extends Controller
{
    // Ruta principal con búsqueda de archivos
    public function index(Request $request)
    {
        $search = $request->input('search');

        $ficheros = Fichero::whereNull('deleted_at')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->get();

        $archivosEliminados = Fichero::onlyTrashed()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->get();

        $usuarios = Auth::check() ? User::where('id', '!=', Auth::id())->get() : collect();

        $archivosCompartidos = DB::table('shared_files')
            ->join('ficheroes', 'shared_files.fichero_id', '=', 'ficheroes.id')
            ->join('users', 'ficheroes.user_id', '=', 'users.id')
            ->where('shared_files.user_id', Auth::id())
            ->select('ficheroes.*', 'shared_files.created_at as shared_at', 'users.name as owner_name')
            ->get();

        foreach ($ficheros as $fichero) {
            $fichero->sharedUsers = DB::table('shared_files')->where('fichero_id', $fichero->id)->pluck('user_id');
        }

        return view('welcome')->with([
            'ficheros' => $ficheros,
            'archivosEliminados' => $archivosEliminados,
            'archivosCompartidos' => $archivosCompartidos,
            'usuarios' => $usuarios,
            'search' => $search,
        ]);
    }

    // Para eliminar archivos compartidos
    public function deleteShared($id)
    {
        $sharedFile = DB::table('shared_files')
            ->where('fichero_id', $id)
            ->where('user_id', Auth::id());

        if ($sharedFile->exists()) {
            $sharedFile->delete();
            return redirect('/')->with('status', 'Archivo compartido eliminado.');
        }

        return redirect('/')->withErrors(['No se pudo eliminar el archivo compartido.']);
    }

    // Mostrar formulario de subida de archivos
    public function showUploadForm()
    {
        return view('upload');
    }

    // Subida de archivos
    public function upload(Request $request)
    {
        $file = $request->file('uploaded_file');
        $safeName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $file->getClientOriginalName());

        $fichero = new Fichero();
        $fichero->path = $file->storeAs('public', $safeName);
        $fichero->name = $safeName;
        $fichero->user_id = Auth::id();
        $fichero->privado = $request->has('privado');
        $fichero->save();

        return redirect('/')->with('status', 'Archivo subido correctamente.');
    }

    // Descarga de archivos
    public function download(Fichero $file)
    {
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
    }

    // Soft delete de archivos
    public function softDelete(Fichero $file)
    {
        $file->delete();
        return redirect('/')->with('status', 'Archivo eliminado temporalmente.');
    }

    // Restaurar archivo eliminado
    public function restore($id)
    {
        $fichero = Fichero::onlyTrashed()->findOrFail($id);
        $fichero->restore();
        return redirect('/')->with('status', 'Archivo restaurado correctamente.');
    }

    // Eliminación permanente de archivo
    public function forceDelete($id)
    {
        $fichero = Fichero::onlyTrashed()->findOrFail($id);

        if (Storage::exists($fichero->path)) {
            Storage::delete($fichero->path);
        }

        $fichero->forceDelete();
        return redirect('/')->with('status', 'Archivo eliminado permanentemente.');
    }

    // Mostrar archivo individual
    public function show($id)
    {
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
    }

    // Compartir archivo
    public function share(Request $request, $id)
    {
        $fichero = Fichero::findOrFail($id);
        $user = User::findOrFail($request->input('user_id'));

        if ($user->id == Auth::id()) {
            return redirect()->back()->withErrors(['No puedes compartir un archivo contigo mismo.']);
        }

        $fichero->sharedUsers()->attach($user->id);

        return redirect()->back()->with('status', 'Archivo compartido exitosamente.');
    }

    // Actualizar archivo
    public function update(Request $request, $id)
    {
        $fichero = Fichero::findOrFail($id);

        if (Str::endsWith($fichero->name, ['.txt', '.md', '.docx'])) {
            $tempPath = Storage::path($fichero->path);
            if (Str::endsWith($fichero->name, ['.txt', '.md'])) {
                Storage::put($fichero->path, $request->input('content'));
            } elseif (Str::endsWith($fichero->name, ['.docx'])) {
                $phpWord = new PhpWord();
                $section = $phpWord->addSection();
                $section->addText($request->input('content'));

                $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
                $objWriter->save($tempPath);
            }
        }

        return redirect()->back()->with('status', 'Archivo actualizado correctamente.');
    }

    // Ver PDF
    public function viewPdf($id)
    {
        $fichero = Fichero::findOrFail($id);

        if ($fichero->privado && $fichero->user_id != Auth::id()) {
            return redirect('/')->withErrors(['Archivo no encontrado o no tienes permisos para verlo.']);
        }

        $filePath = Storage::path($fichero->path);

        if (!Storage::exists($fichero->path)) {
            abort(404, 'El archivo no existe.');
        }

        return response()->file($filePath);
    }
}
