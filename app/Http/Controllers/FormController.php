<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formularios = Form::all();

        return view('users.form.index', [
            'formularios' => $formularios
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.form.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request)
    {
        $editor = new Form();
        $editor->save();

        return redirect()->route('forms.edit', $editor->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Form $form)
    {
        return view('users.form.edit', [
            'form' => $form
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form)
    {
        $validate = validator($request->all(), [
            'titulo_corto' => 'required',
            'titulo_largo' => 'required',
            'descripcion' => 'required',
            'elementos' => 'required|json',
            'elementos.*.title' => 'required|max:100',
            'elementos.*.required' => 'boolean',
            'elementos.*.position' => 'required|min:0',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'errors' => json_encode($validate->errors())
            ]);
        }
        return response()->json([
            'data' => $request->all()
        ]);
    }

    /**
     * Validaciones para guardar los elementos del formulario 
     */
    public function getValidate(array $element, int $position): array
    {
        $array = [];
        switch ($element['type']) {
            case 'text':
                return $array;
            case 'paragraphs':
                

                return $array;
            case 'radio':
                # code...
                return $array;
            case 'grid-verify':
                # code...
                return $array;
            case 'grid-multiply':
                # code...
                return $array;
            case 'date':
                # code...
                return $array;
            case 'hour':
                # code...
                return $array;

            default:
                # code...
                return $array;
        }

        return [];
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form)
    {
        //
    }
}
