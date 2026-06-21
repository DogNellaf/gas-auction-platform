@extends('layouts.app')
@section('title', 'Регистрация')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card mt-5">
            <div class="card-header">Регистрация</div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Логин</label>
                        <input id="name" type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="company_name" class="form-label">Название компании</label>
                        <input id="company_name" type="text"
                            class="form-control @error('company_name') is-invalid @enderror"
                            name="company_name" value="{{ old('company_name') }}" required>
                        @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="form_id" class="form-label">ОПФ</label>
                        <select id="form_id" class="form-control @error('form_id') is-invalid @enderror"
                            name="form_id" required>
                            <option value="">— выберите —</option>
                            @foreach ($forms as $form)
                                <option value="{{ $form->id }}" @selected(old('form_id') == $form->id)>
                                    {{ $form->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('form_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input id="phone" type="tel"
                            class="form-control @error('phone') is-invalid @enderror"
                            name="phone" value="{{ old('phone') }}" required placeholder="+7XXXXXXXXXX">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password" required>
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">Подтвердите пароль</label>
                        <input id="password-confirm" type="password" class="form-control"
                            name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
