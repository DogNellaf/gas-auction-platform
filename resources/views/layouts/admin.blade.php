@extends('layouts.app')
@section('title', 'Панель администратора')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-11 mt-3">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center mb-2">
                    <div class="col">
                        <h4 class="mb-0">Панель администратора</h4>
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
                        <a class="nav-link @if(request()->routeIs('admin.index')) active @endif"
                           href="{{ route('admin.index') }}">Аукционы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('admin.bids.*')) active @endif"
                           href="{{ route('admin.bids.index') }}">Заявки</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('admin.auction.create')) active @endif"
                           href="{{ route('admin.auction.create') }}">Добавить аукцион</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('admin.users.*')) active @endif"
                           href="{{ route('admin.users.index') }}">Пользователи</a>
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
