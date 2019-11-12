@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('org.show', ['org' => $user->org_id]) }}" class="btn btn-primary">К организации</a>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="card" style="margin-top: 16px;">
            <div class="card-header">{{ $user->last_name . ' ' . $user->name . ' ' . $user->middle_name }}</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Дата рождения: {{ $user->birthday }}</li>
                <li class="list-group-item">ИНН: {{ $user->inn }}</li>
                <li class="list-group-item">СНИЛС: {{ $user->snils }}</li>
            </ul>
            <div class="card-footer">
                <form action="{{ route('user.destroy', ['user' => $user->id]) }}" method="post" onsubmit="if(confirm('Удалить пользователя?')) {return true} else {return false}">
                    @method('DELETE')
                    @csrf
                    <input type="submit" class="btn btn-danger" value="Удалить">
                </form>
            </div>
        </div>
    </div>
@endsection