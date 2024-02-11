<x-form :submit="'Ok'" hx-post="{{ route('upload') }}">
    <label>
        <span>File</span>
        <input type="file" name="file" value="{{ old('file') }}">
        <x-error field="file" />
    </label>
</x-form>
