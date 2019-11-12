@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <a href="{{ route('org.index') }}" class="btn btn-primary">К списку организаций</a>

        <form action="/org" method="POST">
            @csrf
            <div class="form-group">
                <label for="display_name">Наименование организации:</label>
                <input type="text" name="display_name" class="form-control" id="display_name" required>
            </div>
            <div class="form-group">
                <label for="ogrn">ОГРН:</label>
                <input type="text" name="ogrn" class="form-control" id="ogrn" required>
            </div>
            <div class="form-group">
                <label for="oktmo">ОКТМО:</label>
                <input type="text" name="oktmo" class="form-control" id="oktmo" required>
            </div>
            <div class="input-group-prepend">
                <input class="btn btn-primary" type="submit">
            </div>
        </form>
    </div>
@endsection