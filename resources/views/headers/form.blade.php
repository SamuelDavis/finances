@php
    use App\Header;
    /** @var string[] $headers */
@endphp
<x-form :submit="'Looks good.'" hx-post="{{ route('headers') }}">
    @foreach (Header::names() as $name)
        <div>
            <label for="headers[{{ $name }}]">The {{ $name }} is found in...</label>
            <select aria-invalid="{{ view()->invalid("headers.$name") }}" name="headers[{{ $name }}]"
                id="headers[{{ $name }}]">
                <option value="">...A column...</option>
                @foreach ($headers as $header)
                    <option value="{{ $header }}"
                        {{ view()->old("headers.$name", $header, stristr($name, $header)) ? 'selected' : '' }}>
                        ...the {{ $header }} column.
                    </option>
                @endforeach
            </select>
            <x-error field="headers.{{ $name }}" />
        </div>
    @endforeach
</x-form>
