@extends('layouts.default')
@section('main')
    <header>
        <nav>
            <ul>
                <li>
                    <a href="{{ route('upload') }}">Back</a>
                </li>
            </ul>
        </nav>
    </header>
    @include('headers.form')
@endsection
