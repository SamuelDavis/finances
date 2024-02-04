@php use Illuminate\View\ComponentAttributeBag; @endphp
@php
    /** @var Illuminate\Contracts\Support\MessageBag $errors */
    /** @var ComponentAttributeBag $attributes */
@endphp
<form {{ $attributes }} hx-swap="outerHTML swap:500ms" hx-indicator="progress" hx-encoding="multipart/form-data">
    @csrf
    {{ $slot }}
    <input type="submit">
    <progress class="hx-indicator"></progress>
</form>
