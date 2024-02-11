@php use Illuminate\View\ComponentAttributeBag; @endphp
@php
    /** @var Illuminate\Contracts\Support\MessageBag $errors */
    /** @var ComponentAttributeBag $attributes */
@endphp
@props(['submit' => 'Submit'])
<form hx-encoding="multipart/form-data" hx-indicator="progress" {{ $attributes }}>
    @csrf
    {{ $slot }}
    <input type="submit" value="{{ $submit }}">
</form>
