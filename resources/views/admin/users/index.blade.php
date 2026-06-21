@extends('layouts.admin')
@section('card')
    <h5 class="mb-3">Пользователи на рассмотрении</h5>

    @forelse ($pendingUsers as $user)
        <div class="row align-items-center border rounded p-3 mb-2">
            <div class="col-sm">
                <small class="text-muted">Логин</small><br>
                <strong>{{ $user->name }}</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Email</small><br>{{ $user->email }}
            </div>
            <div class="col-sm">
                <small class="text-muted">Компания</small><br>{{ $user->company_name }}
            </div>
            <div class="col-sm">
                <small class="text-muted">ОПФ</small><br>{{ $user->legalForm->title ?? '—' }}
            </div>
            <div class="col-sm">
                <small class="text-muted">Телефон</small><br>{{ $user->phone }}
            </div>
            <div class="col-sm-auto d-flex gap-2">
                <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success btn-sm">Одобрить</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-muted">Нет пользователей, ожидающих подтверждения.</p>
    @endforelse

    <div class="mt-2">{{ $pendingUsers->links('pagination::bootstrap-4') }}</div>

    <h5 class="mt-4 mb-3">Активные пользователи</h5>

    @forelse ($approvedUsers as $user)
        <div class="row align-items-center border rounded p-3 mb-2">
            <div class="col-sm">
                <small class="text-muted">Логин</small><br>
                <strong>{{ $user->name }}</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Email</small><br>{{ $user->email }}
            </div>
            <div class="col-sm">
                <small class="text-muted">Компания</small><br>{{ $user->company_name }}
            </div>
            <div class="col-sm">
                <small class="text-muted">ОПФ</small><br>{{ $user->legalForm->title ?? '—' }}
            </div>
            <div class="col-sm-auto">
                <form action="{{ route('admin.users.reject', $user) }}" method="POST"
                      onsubmit="return confirm('Заблокировать пользователя {{ $user->name }}?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline-danger btn-sm">Заблокировать</button>
                </form>
            </div>
        </div>
    @empty
        <p class="text-muted">Нет активных пользователей.</p>
    @endforelse

    <div class="mt-2">{{ $approvedUsers->links('pagination::bootstrap-4') }}</div>
@endsection
