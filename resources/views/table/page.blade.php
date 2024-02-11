@php
    use App\Header;
    use Illuminate\Support\Str;
@endphp
@php
    /** @var string[] $headers */
    /** @var (string|float)[][] $rows */
@endphp
@extends('layouts.default')
@section('main')
    <header>
        <nav>
            <ul>
                <li>
                    <a href="" hx-delete="{{ route('upload', ['_token' => csrf_token()]) }}" hx-indicator="progress">
                        Reset
                    </a>
                </li>
            </ul>
            <ul>
                <li>
                    <strong>Results</strong>
                </li>
            </ul>
        </nav>
    </header>
    <table>
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($row as $i => $cell)
                        @if ($headers[$i] === Header::Description->name)
                            <td title="{{ $cell }}">{{ Str::limit($cell, 100) }}</td>
                        @else
                            <td>{{ $cell }}</td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
