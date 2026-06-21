@extends('layouts.home')
@section('title', 'Профиль')
@section('card')
    <h5>Персональные данные</h5>
    <form method="POST" action="{{ route('home.data.save') }}">
        @csrf
        @method('PATCH')

        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">Логин</label>
            <div class="col-md-6">
                <input id="name" type="text"
                    class="form-control @error('name') is-invalid @enderror"
                    name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
            <div class="col-md-6">
                <input id="email" type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="company_name" class="col-md-4 col-form-label text-md-end">Название компании</label>
            <div class="col-md-6">
                <input id="company_name" type="text"
                    class="form-control @error('company_name') is-invalid @enderror"
                    name="company_name" value="{{ old('company_name', $user->company_name) }}" required>
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="form_id" class="col-md-4 col-form-label text-md-end">ОПФ</label>
            <div class="col-md-6">
                <select id="form_id" class="form-control @error('form_id') is-invalid @enderror" name="form_id" required>
                    @foreach ($forms as $form)
                        <option value="{{ $form->id }}" @selected(old('form_id', $user->form_id) == $form->id)>
                            {{ $form->title }}
                        </option>
                    @endforeach
                </select>
                @error('form_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row mb-3">
            <label for="phone" class="col-md-4 col-form-label text-md-end">Телефон</label>
            <div class="col-md-6">
                <input id="phone" type="tel"
                    class="form-control @error('phone') is-invalid @enderror"
                    name="phone" value="{{ old('phone', $user->phone) }}" required>
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </div>
    </form>
@endsection
