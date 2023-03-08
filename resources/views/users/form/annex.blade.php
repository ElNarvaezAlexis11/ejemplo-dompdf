@extends('users.form.main')
@section('slot')
<livewire:forms.annex :annex_id="$form->id" />
@endsection