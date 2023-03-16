<?php

namespace Tests\Feature\Http\Livewire\Forms;

use App\Http\Livewire\Forms\Preview;
use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class PreviewTest extends TestCase
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
     * Verifica que el componente `Preview` se puede renderizar
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     */
    public function test_can_render_preview_component(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Preview::class, [
                'anexo_id' =>  $annex->id
            ]);
        $component->assertStatus(200);
    }


    /**
     * Verifica que se generan error si el `anexo` no es requisitado correctamente
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     */
    public function test_errors_in_preview_component(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $component = Livewire::actingAs($this->user)
            ->test(Preview::class, [
                'anexo_id' =>  $annex->id
            ]);
        $component->call('test');
        $component->assertHasErrors();
    }
}
