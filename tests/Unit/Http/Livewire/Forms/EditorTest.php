<?php

namespace Tests\Feature\Http\Livewire\Forms;

use App\Http\Livewire\Forms\Editor;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * @coversDefaultClass \Http\Livewire\Forms\Editor
 */
class EditorTest extends TestCase
{
    use WithFaker;

    /**
     *@var mixed|User $user
     */
    public User $user;

    /**
     *
     * @return void
     * @see https://docs.phpunit.de/en/9.6/fixtures.html?highlight=setUp#more-setup-than-teardown
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::find(1);
    }

    /**
     * Verifica que el componente `Editor` responda correctamente
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     */
    public function test_can_render_editor_component(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $componente = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $componente->assertStatus(200);
    }

    /**
     * Verifica que a el `anexo` se le puede agregar los titulo y la descripción
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     */
    public function test_can_add_title_and_descriptions(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $props = [
            'form.titulo_corto' => $this->faker->words(5, true),
            'form.titulo_largo' => $this->faker->words(10, true),
            'form.descripcion' => $this->faker->sentence(20),
        ];

        foreach ($props as $key => $prop) {
            $component->set($key, $prop);
            $component->assertSet($key, $prop);
        }
    }

    /**
     * Verifica que se pueden agregar elementos a el componente de editor `Editor`
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::addElement
     */
    public function test_can_add_elments_to_editor_component(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $countElements = count(json_decode($annex->elementos, true));

        $component->call('addElement');
        $this->assertEquals(count($component->get('form.elementos')), $countElements + 1);
    }

    /**
     * Verifica que se puede eliminar un elemento del arreglo de "elementos" de un `Anexo`
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::remove
     */
    public function test_can_delete_element_in_editor_component(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $countElements = count(json_decode($annex->elementos, true));
        $component->call('remove', 0);
        $this->assertEquals(count($component->get('form.elementos')), $countElements - 1);
    }

    /**
     * Verifica que se puede establecer un nuevo "tipo" para un elemento del `Anexo`
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::setType
     */
    public function test_can_set_type_element(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $component->call('setType', 0, Form::TYPE_ELEMENTS['TEXT']['title']);
        $this->assertEquals($component->get('form.elementos.0.type'), Form::TYPE_ELEMENTS['TEXT']['title']);

        $component->call('setType', 0, Form::TYPE_ELEMENTS['RADIO']['title']);
        $this->assertEquals($component->get('form.elementos.0.type'), Form::TYPE_ELEMENTS['RADIO']['title']);
        $this->assertEquals(count($component->get('form.elementos.0.values')), 1);

        $component->call('setType', 0, Form::TYPE_ELEMENTS['GRID_VERIFY']['title']);
        $this->assertEquals($component->get('form.elementos.0.type'), Form::TYPE_ELEMENTS['GRID_VERIFY']['title']);
        $this->assertIsArray($component->get('form.elementos.0.values'));
        $this->assertIsArray($component->get('form.elementos.0.values.0'));
        $this->assertIsArray($component->get('form.elementos.0.values.1'));
    }

    /**
     * Verifica que se pueden agregar valores a los elementos de tipo "CHECK" y "RADIO" 
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::addElement
     * @covers ::setType
     * @covers ::addValues
     */
    public function test_can_add_values_in_check_and_radio(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $radiosOrChecks = collect($component->get('form.elementos'))
            ->whereIn('type', [
                Form::TYPE_ELEMENTS['RADIO']['title'],
                Form::TYPE_ELEMENTS['CHECK']['title'],
            ])
            ->map(fn ($element) => $element['position']);

        $numberValues = count($component->get("form.elementos.$radiosOrChecks[1].values"));
        $component->call('addValues', $radiosOrChecks[1]);
        $this->assertEquals(
            count($component->get("form.elementos.$radiosOrChecks[1].values")),
            $numberValues + 1
        );
    }

    /**
     * Verifica que se pueden remover valores dentro de los elementos de tipo "check" o "radio"
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::addElement
     * @covers ::setType
     * @covers ::addValues
     * @covers ::removeValue
     */
    public function test_can_remove_values_in_check_or_radio(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $radiosOrChecks = collect($component->get('form.elementos'))
            ->whereIn('type', [
                Form::TYPE_ELEMENTS['RADIO']['title'],
                Form::TYPE_ELEMENTS['CHECK']['title'],
            ])
            ->map(fn ($element) => $element['position']);

        $numberValues = count($component->get("form.elementos.$radiosOrChecks[1].values"));
        $component->call('removeValue', $radiosOrChecks[1], 0);
        $this->assertEquals(
            count($component->get("form.elementos.$radiosOrChecks[1].values")),
            $numberValues - 1
        );
    }

    /**
     * Verifica que se pueden agregar columnas y filas a los elemento de 
     * "GRID_VERIFY" y "GRID_MULTIPLY"
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::addElement
     * @covers ::setType
     * @covers ::addRowToGrid
     * @covers ::addColToGrid
     */
    public function test_can_add_row_and_columns_in_grids(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $component->call('addElement');
        $postionLastElement = count($component->get('form.elementos')) - 1;
        $component->call('setType', $postionLastElement, Form::TYPE_ELEMENTS['GRID_VERIFY']['title']);
        #$component->set("form.elementos.$postionLastElement.values.0", 'xxx x x x x x x');

        $component->call('addRowToGrid', $postionLastElement);
        $rows = $component->get("form.elementos.$postionLastElement.values.0");
        $this->assertEquals(count($rows), 1);

        #$component->set("form.elementos.$postionLastElement.values.1", 'xxx x x x x x x');
        $component->call('addColToGrid', $postionLastElement);
        $columns = $component->get("form.elementos.$postionLastElement.values.1");
        $this->assertEquals(count($columns), 1);

        #cuando pasas de elmentos de tipo "CEHCK" o "Radios" a grids.
        #filas
        // $component->call('setType', $postionLastElement, Form::TYPE_ELEMENTS['CHECK']['title']);
        // $component->call('addValues', $postionLastElement);
        // $component->call('addRowToGrid', $postionLastElement);
        // $rows = $component->get("form.elementos.$postionLastElement.values.0");
        // $this->assertEquals(count($rows), 1);
    }

    /**
     * Verifica que se pueden remover columnas y filas a los elemento de 
     * "GRID_VERIFY" y "GRID_MULTIPLY"
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::addElement
     * @covers ::setType
     * @covers ::addRowToGrid
     * @covers ::addColToGrid
     * @covers ::removeColToGrid
     * @covers ::removeRowToGrid
     */
    public function test_can_remove_row_and_columns_in_grids(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $component->call('addElement');
        $postionLastElement = count($component->get('form.elementos')) - 1;
        $component->call('setType', $postionLastElement, Form::TYPE_ELEMENTS['GRID_VERIFY']['title']);

        $component->call('addRowToGrid', $postionLastElement);
        $rows = $component->get("form.elementos.$postionLastElement.values.0");
        $component->call('removeRowToGrid', $postionLastElement, 0);
        $this->assertEquals(count($rows) - 1, 0);

        $component->call('addColToGrid', $postionLastElement);
        $columns = $component->get("form.elementos.$postionLastElement.values.1");
        $component->call('removeColToGrid', $postionLastElement, 0);
        $this->assertEquals(count($columns) - 1, 0);
    }

    /**
     * Verifica que el elemento de la posicion dada requiera o no de una validacion especial.
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::addElement
     * @covers ::requiredValidation
     */
    public function test_requiered_validation(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $editor = new Editor();
        $editor->mount($annex);
        $editor->addElement();
        $postionLastElement = count($editor->form['elementos']) - 1;
        $isRequerible = $editor->requiredValidation($postionLastElement);
        $this->assertEquals($isRequerible, false);
    }


    /**
     * Verifica que se puede establecer la validacion a los "elementos" del `anexo` que lo requieran 
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::addElement
     * @covers ::setType
     * @covers ::setValidations
     */
    public function test_set_validation_to_elements(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $datesOrParagraphs = collect($component->get('form.elementos'))
            ->whereIn('type', [
                Form::TYPE_ELEMENTS['PARAGRAPHS']['title'],
                Form::TYPE_ELEMENTS['DATE']['title'],
            ])
            ->map(fn ($element) => $element['position']);
        $component->call('setValidations', $datesOrParagraphs[0]);
        $this->assertIsArray($component->get("form.elementos.$datesOrParagraphs[0].validation"));

        $component->call('setValidations', $datesOrParagraphs[0]);
        $this->assertNull($component->get("form.elementos.$datesOrParagraphs[0].validation"));
    }

    /**
     * Verifica que se puede guardar correctamente el anexo/Form
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::submit
     */
    public function test_can_save_annex(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $component->call('submit');
        $component->assertHasNoErrors();
    }

    /**
     * Verifica que se puede establecer un nuevo order de los elementos de un `anexo`
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::setNewOrder
     */
    public function test_can_set_new_elements_order(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Editor::class, [
                'formModel' => $annex
            ]);
        $order = collect(json_decode($annex->elementos, true))
            ->map(fn ($element): int => $element['position']);
        $component->call('setNewOrder', $order->all());

        foreach (json_decode($annex->elementos, true) as $index => $element) {
            $this->assertEquals($component->get("form.elementos.$index.position"), $element['position']);
        }
    }

    /**
     * Verifica que se puede obtener la clave de referencia para un error producido
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     * @covers ::mount
     * @covers ::getErrorStringKeyToValidations
     */
    public function test_can_get_error_key(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $editor = new Editor();
        $editor->mount($annex);
        $errorKey = $editor->getErrorStringKeyToValidations('type', 0);
        $this->assertEquals('form.elementos.0.validation.type', $errorKey);
    }
}
