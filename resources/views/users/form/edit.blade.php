@extends('users.form.main')
@section('slot')
<livewire:forms.editor :formModel="$form->id" />
@endsection