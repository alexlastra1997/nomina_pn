<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ServidoresImport;

class ServidorImportController extends Controller
{
    public function form()
    {
        return view('servidores.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'archivo' => ['required','file','mimes:xlsx,xls,csv','max:51200'], // 50MB
        ]);

        try {
            Excel::import(new ServidoresImport, $request->file('archivo'));
            return back()->with('success', 'Importación completada correctamente.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al importar: '.$e->getMessage());
        }
    }
}