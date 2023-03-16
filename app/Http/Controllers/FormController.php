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
        return view('users.form.show', [
            'form' => $form
        ]);
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
}
