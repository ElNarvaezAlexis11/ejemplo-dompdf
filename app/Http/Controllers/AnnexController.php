<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\Request;

class AnnexController extends Controller
{
      /**
     * Display the specified resource.
     */
    public function show(Form $annex)
    {
        return view('users.form.annex', [
            'form' => $annex
        ]);
    }
}
