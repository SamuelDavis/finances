@php
    use App\Csv;
    use App\Headers;
    use Illuminate\Support\Str;
    /** @var Csv $csv */
@endphp
@extends('layouts.default')
@section('main')
    <x-form hx-post="{{ route('headers') }}">
        @foreach (Headers::cases() as $case)
            <div>
                <label for="{{ $case->name }}">The {{ $case->name }} is in...</label>
                <select name="headers[{{ $case->name }}]" id="{{ $case->name }}" required>
                    @foreach ($csv->getHeaders() as $header)
                        <option value="{{ $header }}" @if (old("headers.$case->name", Str::contains($header, $case->name, true))) selected="selected" @endif>
                            ...the "{{ $header }}" column
                        </option>
                    @endforeach
                </select>
                @include('components.error', ['field' => "headers.$case->name"])
            </div>
        @endforeach
    </x-form>
@endsection
