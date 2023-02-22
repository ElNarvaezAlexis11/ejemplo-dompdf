<?php

namespace App\Http\Livewire\Users\Forms;

use App\Models\Form;
use Livewire\Component;

/**
 * @author Narvaez A. 
 */
class Editor extends Component
{

    /**
     * Arreglo de datos para guardar los en la base de datos.
     * 
     * @var array form
     */
    public array $editor;

    protected function rules(): array
    {
        return [
            'editor.id' => 'required|exists:forms,id',
            'editor.titulo_corto' => 'required',
            'editor.titulo_largo' => 'required',
            'editor.descripcion' => 'required',
            'editor.status' => '',
            'editor.elementos' => 'json',
        ];
    }

    /**
     * Funcion encargada de validar y guardar la informacion del formula
     *  
     * 
     * @return void  
     */
    public function submit(): void
    {
        $this->validate();
    }

    /**
     * Regresa un arreglo con los valores vacios.
     * 
     * @return array  
     */
    public function arrayInformation(): array
    {
        return [
            'titulo_corto' => '',
            'titulo_largo' => '',
            'descripcion' => '',
            'status' => '',
            'elementos' => '',
        ];
    }

    public function mount(Form $editor_id): void
    {
        $this->editor = $editor_id->toArray();
    }

    public function render()
    {
        $errors = $this->getErrorBag();

        return view('livewire.users.forms.editor', [
            'errors' => $errors,
        ]);
    }
}
