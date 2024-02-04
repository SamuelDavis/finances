@extends('layouts.default')
@section('main')
    <x-form hx-post="{{ route('upload') }}">
        <div>
            <label for="file">Parse financial data from...</label>
            <input id="file" type="file" name="file" aria-invalid="{{ $errors->has('file') ? 'true' : '' }}"
                accept="text/csv" required>
            @include('components.error', ['field' => 'file'])
        </div>
    </x-form>
@endsection
