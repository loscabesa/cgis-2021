<?php

namespace App\Http\Controllers;

use App\Models\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    public function index()
    {
        $especialidades = Especialidad::paginate(25);
        return view('/especialidades/index33', ['especialidades' => $especialidades]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('especialidades/create');
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
            /*'descripcion' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'amortizacion' => 'required|numeric',
            'reparaciones' => 'required|numeric',
            'lote_id' => 'required|max:255|exists:lotes,id',*/
        ], [
            /*'referencia.required' => 'El campo Referencia es obligatorio',
            'descripcion.required' => 'El campo Descripción es obligatorio',
            'precio.required' => 'El campo Precio es obligatorio',
            'precio.numeric' => 'El campo Precio debe ser un número',
            'amortizacion.required' => 'El campo Amortización es obligatorio',
            'amortizacion.numeric' => 'El campo Amortización debe ser un número',
            'reparaciones.required' => 'El campo Reparaciones es obligatorio',
            'reparaciones.numeric' => 'El campo Reparaciones debe ser un número',
            'lote_id.required' => 'El campo Lote es obligatorio',*/
        ]);
        $especialidad = new Especialidad($request->all());
        $especialidad->save();
        session()->flash('success', 'Especialidad creada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('especialidads.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function show(Especialidad $especialidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function edit(Especialidad $especialidad)
    {
        return view('especialidades/edit', ['especialidad' => $especialidad]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Especialidad $especialidad)
    {
        $this->validate($request, [
            'nombre' => 'required|string|max:255',
            // 'descripcion' => 'required|string|max:255',
            // 'precio' => 'required|numeric',
            // 'amortizacion' => 'required|numeric',
            // 'reparaciones' => 'required|numeric',
            // 'lote_id' => 'required|max:255|exists:lotes,id',
        ], [
            // 'referencia.required' => 'El campo Referencia es obligatorio',
            // 'descripcion.required' => 'El campo Descripción es obligatorio',
            // 'precio.required' => 'El campo Precio es obligatorio',
            // 'precio.numeric' => 'El campo Precio debe ser un número',
            // 'amortizacion.required' => 'El campo Amortización es obligatorio',
            // 'amortizacion.numeric' => 'El campo Amortización debe ser un número',
            // 'reparaciones.required' => 'El campo Reparaciones es obligatorio',
            // 'reparaciones.numeric' => 'El campo Reparaciones debe ser un número',
            // 'lote_id.required' => 'El campo Lote es obligatorio',
        ]);
        $especialidad->fill($request->all());
        $especialidad->save();
        session()->flash('success', 'Especialidad modificada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('especialidads.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Equipo  $equipo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Especialidad $especialidad)
    {
        if($especialidad->delete()) {
            session()->flash('success', 'Especialidad borrada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        }
        else{
            session()->flash('warning', 'No pudo borrarse la especialidad.');
        }
        return redirect()->route('especialidads.index');

    }
}
