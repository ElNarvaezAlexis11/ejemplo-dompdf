@extends('users.form.main')
@section('slot')
<livewire:forms.preview :anexo_id="$form->id" />
@endsection