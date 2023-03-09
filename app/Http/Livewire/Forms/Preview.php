<?php

namespace App\Http\Livewire\Forms;

use App\Models\Form;
use Livewire\Component;

/**
 * 
 * Componente encargado de visualizar el formulario creado,
 * este no guarda la informacion, solo valida los datos de entrada. 
 */
class Preview extends Component
{
    /**
     * Informacion del formulario
     * @var mixed 
     */
    public string $anexo_id;

    /**
     * Arreglo de valores que son rellenados en el formulario. 
     */
    public array $answers;

    /**
     * Porpiedad computada que regresa los tipos de entradas validas para el
     * formulario.
     * @return array 
     */
    public function getTypeElementsProperty(): array
    {
        return Form::TYPE_ELEMENTS;
    }

    /**
     * Funcion que regresa el formulario asociado a el `anexo_id`
     * - Se recomienda revisar la documentacion oficial para entender, los casos
     *	en los que se deben de utilizar las propiedades computadas `Computed Properties`.
     *
     * @return 
     * @see https://laravel-livewire.com/docs/2.x/properties#computed-properties
     */
    public function getFormProperty(): array
    {
        $annex = Form::find($this->anexo_id)->toArray();
        $annex['elementos'] = json_decode($annex['elementos'], true);
        return $annex;
    }

    protected function rules(): array
    {
        $positionsTexts = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['TEXT']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionsDates = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['DATE']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionsRadios = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['RADIO']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionCheck = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['CHECK']['title'],
            ])->map(fn ($element) => $element['position']);

        $positionGridMultiply = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['GRID_MULTIPLY']['title'],
            ])->map(fn ($element) => $element['position']);


        $positionGridVerify = collect($this->form['elementos'])
            ->whereIn('type', [
                $this->typeElements['GRID_VERIFY']['title'],
            ])->map(fn ($element) => $element['position']);

        $array = array_merge(
            ...Form::getRulesToText($positionsTexts->all()),
            ...Form::getRulesToDate($positionsDates->all()),
            ...Form::getRulesToRadio($positionsRadios->all()),
            ...Form::getRulesToCheck($positionCheck->all(), $this->form['elementos']),
            ...Form::getRulesToGridMultiply($positionGridMultiply->all(), $this->form['elementos']),
            ...Form::getRulesToGridVerify($positionGridVerify->all(), $this->form['elementos'])
        );

        return $array;
    }

    /**
     * Estabalece la estructura del arreglo de valores que deben de 
     * contener cada uno de los elementos del formulario
     * @param int $position Posicion dentro de los elementos de "form"
     * @return array Arreglo con la estructura de LLave valor para las grllas, 
     * un arreglo simple para los otros elementos.
     */
    public function getStructorValues(int $position): array
    {
        switch ($this->form['elementos'][$position]['type']) {
            case $this->typeElements['GRID_VERIFY']['title']:
                return array_fill(0, count($this->form['elementos'][$position]['values'][0]), null);
            case $this->typeElements['GRID_MULTIPLY']['title']:
                return array_fill(0, count($this->form['elementos'][$position]['values'][0]), null);
        }
        return [];
    }

    /**
     * Funcion ejecutada al intentar de enviar un anexos en modo de edicion.
     * - Realiza la validacion para cada una de las entrdas dadas.
     * @return void 
     */
    public function test(): void
    {
        $this->resetErrorBag();
        $this->validate();
    }

    /**
     * @param Form $form
     * @return void 
     * @see https://laravel-livewire.com/docs/2.x/properties#initializing-properties
     */
    public function mount(string $anexo_id): void
    {
        $this->fill(['anexo_id' => $anexo_id]);

        $this->fill(['answers' => array_map(fn ($element): array => [
            'label' => $element['title'],
            'values' => [...$this->getStructorValues($element['position'])]
        ], $this->form['elementos'])]);
    }


    /**
     * Renderiza el componente
     * @return void 
     * @see https://laravel-livewire.com/docs/2.x/rendering-components#render-method
     */
    public function render()
    {
        return view('livewire.forms.preview');
    }
}
