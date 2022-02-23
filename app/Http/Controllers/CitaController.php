<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Medicamento;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CitaController extends Controller
{

    public function __construct()
    {
        $a = "lab 5";
        $b = "lab 5 otra vé";
        $c = "Lab 5 A QUE NO TE LO ESPERABAAA";
        $d = "No hay cuart";
        $this->authorizeResource(Cita::class, 'cita');
    }

    public function index()
    {
        $citas = Cita::orderBy('fecha_hora', 'desc')->paginate(25);
        if(Auth::user()->tipo_usuario_id == 1){
            $citas = Auth::user()->medico->citas()->orderBy('fecha_hora', 'desc')->paginate(25);
        }
        elseif(Auth::user()->tipo_usuario_id == 2){
            $citas = Auth::user()->paciente->citas()->orderBy('fecha_hora', 'desc')->paginate(25);
        }
        $a="a";
        return view('/citas/index', ['citas' => $citas]);
    }

    public function create()
    {
        $medicos = Medico::all();
        $pacientes = Paciente::all();
        if(Auth::user()->tipo_usuario_id == 1){
            return view('citas/create', ['medico' => Auth::user()->medico, 'pacientes' => $pacientes]);
        }
        elseif(Auth::user()->tipo_usuario_id == 2) {
            return view('citas/create', ['paciente' => Auth::user()->paciente, 'medicos' => $medicos]);
        }
        return view('citas/create', ['pacientes' => $pacientes, 'medicos' => $medicos]);
    }

    public function store(Request $request)
    {
        $reglas = [
            'fecha_hora' => 'required|date|after:yesterday',
            'medico_id' => 'required|exists:medicos,id',
        ];
        if(Auth::user()->tipo_usuario_id == 2){
            $reglas_paciente = ['paciente_id' => ['required', 'exists:pacientes,id', Rule::in(Auth::user()->paciente->id)]];
            $reglas = array_merge($reglas_paciente, $reglas);
        }
        else{
            $reglas_generales = ['paciente_id' => ['required', 'exists:pacientes,id']];
            $reglas = array_merge($reglas_generales, $reglas);
        }
        $this->validate($request, $reglas);
        $cita = new Cita($request->all());
        $cita->save();
        session()->flash('success', 'Cita creada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('citas.index');
    }

    public function show(Cita $cita)
    {
        return view('citas/show', ['cita' => $cita]);
    }

    public function edit(Cita $cita)
    {
        //Le paso a la vista los medicamentos porque permito añadir una prescripción desde esa misma vista
        $medicamentos = Medicamento::all();
        $medicos = Medico::all();
        $pacientes = Paciente::all();
        if(Auth::user()->tipo_usuario_id == 1){
            return view('citas/edit', ['cita' => $cita, 'medico' => Auth::user()->medico, 'pacientes' => $pacientes, 'medicamentos' => $medicamentos]);
        }
        elseif(Auth::user()->tipo_usuario_id == 2) {
            return view('citas/edit', ['cita' => $cita, 'paciente' => Auth::user()->paciente, 'medicos' => $medicos, 'medicamentos' => $medicamentos]);
        }
        return view('citas/edit', ['cita' => $cita, 'pacientes' => $pacientes, 'medicos' => $medicos, 'medicamentos' => $medicamentos]);
    }

    public function update(Request $request, Cita $cita)
    {
        $reglas = [
            'fecha_hora' => 'required|date|after:yesterday',
            'medico_id' => 'required|exists:medicos,id',
        ];
        if(Auth::user()->tipo_usuario_id == 2){
            $reglas_paciente = ['paciente_id' => ['required', 'exists:pacientes,id', Rule::in(Auth::user()->paciente->id)]];
            $reglas = array_merge($reglas_paciente, $reglas);
        }
        else{
            $reglas_generales = ['paciente_id' => ['required', 'exists:pacientes,id']];
            $reglas = array_merge($reglas_generales, $reglas);
        }
        $this->validate($request, $reglas);
        $cita->fill($request->all());
        $cita->save();
        session()->flash('success', 'Cita modificada correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        return redirect()->route('citas.index');
    }

    public function destroy(Cita $cita)
    {
        if($cita->delete()) {
            session()->flash('success', 'Cita borrado correctamente. Si nos da tiempo haremos este mensaje internacionalizable y parametrizable');
        }
        else{
            session()->flash('warning', 'La cita no pudo borrarse. Es probable que se deba a que tenga asociada información como citas que dependen de él.');
        }
        return redirect()->route('citas.index');
    }

    public function attach_medicamento(Request $request, Cita $cita)
    {
        $this->validateWithBag('attach',$request, [
            'medicamento_id' => 'required|exists:medicos,id',
            'inicio' => 'required|date',
            'fin' => 'required|date|after:inicio',
            'comentarios' => 'nullable|string',
            'tomas_dia' => 'required|numeric|min:0',
        ]);
        $cita->medicamentos()->attach($request->medicamento_id, [
            'inicio' => $request->inicio,
            'fin' => $request->fin,
            'comentarios' => $request->comentarios,
            'tomas_dia' => $request->tomas_dia
        ]);
        return redirect()->route('citas.edit', $cita->id);
    }

    public function detach_medicamento(Cita $cita, Medicamento $medicamento)
    {
        $cita->medicamentos()->detach($medicamento->id);
        return redirect()->route('citas.edit', $cita->id);
    }
}
