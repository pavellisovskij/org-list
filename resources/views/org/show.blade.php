@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('org.index') }}" class="btn btn-primary">К списку организации</a>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card" style="margin-top: 16px; margin-bottom: 16px;">
            <div class="card-header">{{ $org->display_name }}</div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">ОГРН: {{ $org->ogrn }}</li>
                <li class="list-group-item">ОКТМО: {{ $org->oktmo }}</li>
                <li class="list-group-item">Количество пользователей: {{ $org->users()->count() }}</li>
            </ul>
            <div class="card-footer">
                <form action="{{ route('org.destroy', ['org' => $org->id]) }}" method="post" onsubmit="if(confirm('Удалить организацию и данные пользователей?')) {return true} else {return false}">
                    @method('DELETE')
                    @csrf
                    <input type="submit" class="btn btn-danger" value="Удалить">
                </form>
            </div>
        </div>

        <div class="card" style="margin-bottom: 16px;">
            <div class="card-header">Новый пользователь</div>
            <div class="card-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ '/user/store/org/' . $org->id }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="last_name">Фамилия:</label>
                        <input type="text" name="last_name" class="form-control" id="last_name" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Имя:</label>
                        <input type="text" name="name" class="form-control" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="middle_name">Отчество:</label>
                        <input type="text" name="middle_name" class="form-control" id="middle_name" required>
                    </div>
                    <div class="form-group">
                        <label for="birthday">Дата рождения:</label>
                        <input type="date" name="birthday" class="form-control" id="birthday" required>
                    </div>
                    <div class="form-group">
                        <label for="inn">ИНН:</label>
                        <input type="text" name="inn" class="form-control" id="inn" required>
                    </div>
                    <div class="form-group">
                        <label for="snils">СНИЛС:</label>
                        <input type="text" name="snils" class="form-control" id="snils" required>
                    </div>
                    <div class="input-group-prepend">
                        <input class="btn btn-primary" type="submit" value="Добавить">
                    </div>
                </form>
            </div>
        </div>

        @if($org->users()->count() == 0)
            <h3 class="text-center">В этой организации нет пользователей =(</h3>
        @else
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">ФИО</th>
                    <th scope="col">Дата рождения</th>
                    <th scope="col">ИНН</th>
                    <th scope="col">СНИЛС</th>
                </tr>
                </thead>
                <tbody>
                @foreach($org->users as $user)
                    <tr
                            onclick="document.location = '{{ route('user.show', ['user' => $user->id]) }}'"
                            class="tr-link"
                    >
                        <td></td>
                        <td>{{ $user->name . ' ' . $user->middle_name . ' ' . $user->last_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($user->birthday)->format('d.m.Y') }}</td>
                        <td>{{ $user->inn }}</td>
                        <td>{{ $user->snils }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @push('counter')
                <script src="{{ asset('js/counter.js') }}"></script>
            @endpush
        @endif
    </div>
@endsection