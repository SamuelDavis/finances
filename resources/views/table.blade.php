@extends('layouts.default')
@section('main')
    <table>
        <thead>
            <tr>
                @foreach ($headers as $name)
                    <th>{{ $name }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    @foreach ($headers as $name)
                        <td>{{ $row[$name] }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
