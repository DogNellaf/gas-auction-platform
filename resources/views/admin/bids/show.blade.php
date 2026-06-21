@extends('layouts.admin')
@section('card')
    <h5>Заявка #{{ $bid->id }} — Договор</h5>

    <div class="card mb-3">
        <div class="card-header">Данные победителя</div>
        <div class="card-body">
            <p><strong>Компания:</strong> {{ $bid->user->company_name }}</p>
            <p><strong>ОПФ:</strong> {{ $bid->user->legalForm->title ?? '—' }}</p>
            <p><strong>Контактное лицо:</strong> {{ $bid->user->name }}</p>
            <p><strong>Email:</strong> {{ $bid->user->email }}</p>
            <p><strong>Телефон:</strong> {{ $bid->user->phone }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Данные аукциона</div>
        <div class="card-body">
            <p><strong>Аукцион:</strong> #{{ $bid->auction->id }}</p>
            <p><strong>Описание:</strong> {{ $bid->auction->description }}</p>
            <p><strong>Выигрышная ставка:</strong> {{ number_format($bid->price, 2, '.', ' ') }} ₽</p>
            <p><strong>Дата завершения:</strong> {{ $bid->auction->end_at->format('d.m.Y H:i') }}</p>
        </div>
    </div>

    <a href="{{ route('admin.bids.index') }}" class="btn btn-secondary">Назад к заявкам</a>
@endsection
