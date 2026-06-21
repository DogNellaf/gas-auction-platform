@extends('layouts.app')
@section('title', 'Личный кабинет')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-9">
        <div class="card mt-3">
            <div class="card-header">
                <div class="row align-items-center mb-2">
                    <div class="col">
                        <h4 class="mb-0">Личный кабинет</h4>
                    </div>
                    <div class="col-auto">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">Выход</button>
                        </form>
                    </div>
                </div>
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('home')) active @endif" href="{{ route('home') }}">Мои заявки</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('home.data.editor')) active @endif" href="{{ route('home.data.editor') }}">Профиль</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                @yield('card')
            </div>
        </div>
    </div>
</div>
@endsection
