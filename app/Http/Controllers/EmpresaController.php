<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Services\AuditoriaService;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::orderBy('nombre')->get();
        return view('empresa.index', compact('empresas'));
    }

    public function create()
    {
        return view('empresa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'limite_usuarios' => 'required|integer|min:1',
            'jornada_diaria_minutos' => 'required|integer|min:1',
            'max_pausa_no_contabilizada' => 'required|integer|min:0',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $logoPath = null;
        $logoThumb = null;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');

            //  Guardar original
            $logoPath = $file->store('logos', 'public');

            // Crear thumbnail con Intervention v3
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getPathname());
            $image->scale(width: 150);

            $thumbBinary = $image->toJpeg(80);

            $thumbName = 'logos/thumb_' . uniqid() . '.jpg';
            Storage::disk('public')->put($thumbName, $thumbBinary);

            $logoThumb = $thumbName;
        }

        $empresa = Empresa::create([
            'nombre' => strip_tags($request->nombre),
            'limite_usuarios' => $request->limite_usuarios,
            'jornada_diaria_minutos' => $request->jornada_diaria_minutos,
            'max_pausa_no_contabilizada' => $request->max_pausa_no_contabilizada,
            'logo_path' => $logoPath,
            'logo_thumb' => $logoThumb,
        ]);

        AuditoriaService::log(
            'crear_empresa',
            'Empresa',
            $empresa->id_empresa,
            null,
            $empresa->toArray(),
            'Empresa creada'
        );

        return redirect()
            ->route('empresa.index')
            ->with('success', 'Empresa creada correctamente.');
    }

    public function edit(Empresa $empresa)
    {
        return view('empresa.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'limite_usuarios' => 'required|integer|min:1',
            'jornada_diaria_minutos' => 'required|integer|min:1',
            'max_pausa_no_contabilizada' => 'required|integer|min:0',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $antes = $empresa->toArray();

        $datos = [
            'nombre' => strip_tags($request->nombre),
            'limite_usuarios' => $request->limite_usuarios,
            'jornada_diaria_minutos' => $request->jornada_diaria_minutos,
            'max_pausa_no_contabilizada' => $request->max_pausa_no_contabilizada,
        ];

        //  Si llega nuevo logo
        if ($request->hasFile('logo')) {

            //  Borrar logos antiguos de forma segura
            if ($empresa->logo_path && Storage::disk('public')->exists($empresa->logo_path)) {
                Storage::disk('public')->delete($empresa->logo_path);
            }

            if ($empresa->logo_thumb && Storage::disk('public')->exists($empresa->logo_thumb)) {
                Storage::disk('public')->delete($empresa->logo_thumb);
            }

            $file = $request->file('logo');

            //  Guardar nuevo original
            $datos['logo_path'] = $file->store('logos', 'public');

            //  Nuevo thumbnail (Intervention v3)
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getPathname());
            $image->scale(width: 150);

            $thumbBinary = $image->toJpeg(80);
            $thumbName = 'logos/thumb_' . uniqid() . '.jpg';

            Storage::disk('public')->put($thumbName, $thumbBinary);

            $datos['logo_thumb'] = $thumbName;
        }

        //  Actualizar
        $empresa->update($datos);

        AuditoriaService::log(
            'editar_empresa',
            'Empresa',
            $empresa->id_empresa,
            $antes,
            $empresa->toArray(),
            'Empresa actualizada'
        );

        return redirect()
            ->route('empresa.index')
            ->with('success', 'Empresa actualizada correctamente.');
    }

    public function destroy(Empresa $empresa)
    {
        if ($empresa->usuarios()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una empresa con usuarios asociados.');
        }

        //  Borrar logos físicos
        if ($empresa->logo_path) {
            Storage::disk('public')->delete($empresa->logo_path);
        }

        if ($empresa->logo_thumb) {
            Storage::disk('public')->delete($empresa->logo_thumb);
        }

        AuditoriaService::log(
            'eliminar_empresa',
            'Empresa',
            $empresa->id_empresa,
            $empresa->toArray(),
            null,
            'Empresa eliminada'
        );

        $empresa->delete();

        return redirect()
            ->route('empresa.index')
            ->with('success', 'Empresa eliminada.');
    }
}
