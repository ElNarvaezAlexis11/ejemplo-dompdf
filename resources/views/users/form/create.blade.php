@extends('users.form.main')
@section('slot')
<livewire:users.forms.editor :editor_id="$form->id" />
@endsection