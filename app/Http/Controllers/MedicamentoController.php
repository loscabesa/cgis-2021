<?php

namespace App\Http\Controllers;

use App\Models\Medicamento;
use Illuminate\Http\Request;

class MedicamentoController extends Controller
{
    public function index()
    {
        $medicamentas = Medicamento::paginate(25);
        return view('/medicamentos/index', ['medicamentos' => $medicamentas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('medicamentos/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:255',
            'miligramos' => 'required|numeric'
        ]);
        $medicamento = new Medicamento($request->all());
        $medicamento->save();
        session()->flash('success', 'Medicamento creado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('medicamentos.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function show(Medicamento $medicamento)
    {
        //
    }

    public function edit(Medicamento $medicamento)
    {
        return view('medicamentos/edit', ['medicamento' => $medicamento]);
    }


    public function update(Request $request, Medicamento $medicamento)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:255',
            'miligramos' => 'required|numeric'
        ]);
        $medicamento->fill($request->all());
        $medicamento->save();
        session()->flash('success', 'Medicamento modificado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('medicamentos.index');
    }

    public function destroy(Medicamento $medicamento)
    {
        if($medicamento->delete()) {
            session()->flash('success', 'Medicamento borrado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        }
        else{
            session()->flash('warning', 'No pudo borrarse el Medicamento.');
        }
        return redirect()->route('medicamentos.index');
    }
}
