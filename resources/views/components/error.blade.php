@php
    /** Illuminate\Support\ViewErrorBag $errors */
@endphp
@props(['field'])
@if ($errors->has($field))
    @foreach ($errors->get($field) as $message)
        <small role="alert">{{ $message }}</small>
    @endforeach
@endif
