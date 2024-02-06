@php
    use App\Headers;
    /** @var string[] $headers */
@endphp
@extends('layouts.default')
@section('main')
    <x-form hx-post="{{ route('headers') }}">
        @foreach (Headers::cases() as $case)
            <div>
                <label for="{{ $case->name }}">The {{ $case->name }} is in...</label>
                <select name="headers[{{ $case->name }}]" id="{{ $case->name }}" required>
                    @foreach ($headers as $header)
                        <option value="{{ $header }}" @if (old("headers.$case->name") === $header) selected="selected" @endif>
                            ...the "{{ $header }}" column
                        </option>
                    @endforeach
                </select>
                @include('components.error', ['field' => "headers.$case->name"])
            </div>
        @endforeach
    </x-form>
@endsection
