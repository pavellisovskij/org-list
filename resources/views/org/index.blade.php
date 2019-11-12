@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card" style="margin-bottom: 16px">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <a href="{{ route('org.create') }}" class="btn btn-primary">Добавить организацию</a>
                </li>
                <li class="list-group-item">
                    <form action="{{ route('xml.upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <input type="file" name="xml_file" accept="text/xml" required>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Отправить">
                    </form>
                </li>
            </ul>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (Session::has('org_errors'))
            @foreach(explode('|', session('org_errors')) as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach
        @endif

        @if (Session::has('user_errors'))
            @foreach(explode('|', session::get('user_errors')) as $error)
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endforeach
        @endif

        @if (Session::has('query_result'))
            @foreach(session::get('query_result') as $warning)
                <div class="alert alert-warning">
                    {{ $warning }}
                </div>
            @endforeach
        @endif

        @if($orgs->count() == 0)
            <div class="alert alert-info" role="alert">
                Пока нет сведений об организациях :(
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Наименование</th>
                        <th scope="col">ОГРН</th>
                        <th scope="col">ОКТМО</th>
                        <th scope="col">Количество пользователей</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orgs as $org)
                        <tr
                            onclick="document.location = '{{ route('org.show', ['org' => $org->id]) }}'"
                            class="tr-link"
                        >
                            <td></td>
                            <td>{{ $org->display_name }}</td>
                            <td>{{ $org->ogrn }}</td>
                            <td>{{ $org->oktmo }}</td>
                            <td>{{ $org->users()->count() }}</td>
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