<?php

namespace App\Http\Controllers;

use App\Models\Formulario;
use Illuminate\Http\Request;
use App\Exports\FormulariosExport;
use Maatwebsite\Excel\Facades\Excel;


class AdminFormularioController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $items = Formulario::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('cedula', 'like', "%{$q}%")
                      ->orWhere('nombres', 'like', "%{$q}%")
                      ->orWhere('apellido_paterno', 'like', "%{$q}%")
                      ->orWhere('apellido_materno', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.formularios.index', compact('items', 'q'));
    }

    public function exportarBase()
    {
        return Excel::download(new FormulariosExport, 'formularios.xlsx');
    }
}