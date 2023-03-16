<?php

namespace Tests\Feature\Http\Livewire\Forms;

use App\Models\Form;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AnnexTest extends TestCase
{

    public User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::find(1);
        $this->actingAs($this->user);
    }

    /**
     * Verifica que el usuario pueda ingresar a la ruta de edicion para un `Anexo`.
     * - Para entender la forma en que se deben de realizar los `test`
     *	se recomienda revisar la documentacion oficial, para poder 
     *	revisar los métodos disponibles.
     * @return void
     * @see https://laravel-livewire.com/docs/2.x/testing
     */
    public function test_route_edit_return_view(): void
    {
        $annex = Form::inRandomOrder()
            ->first();
        $this->get(route('forms.edit',$annex->id))
            ->assertOk();
    }
}
