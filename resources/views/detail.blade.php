@extends('layouts.app')
@section('title', 'Аукцион #' . $auction->id)
@section('content')
    <h4 class="mt-3">Аукцион #{{ $auction->id }}</h4>

    <div class="mb-3">
        <label class="form-label">Описание</label>
        <textarea readonly class="form-control" rows="3">{{ $auction->description }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Стартовая цена</label>
        <input readonly class="form-control" type="text" value="{{ number_format($auction->start_price, 2, '.', ' ') }} ₽">
    </div>
    <div class="mb-3">
        <label class="form-label">Дата и время начала</label>
        <input readonly class="form-control" type="text" value="{{ $auction->created_at->format('d.m.Y H:i') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Дата и время окончания</label>
        <input readonly class="form-control" type="text" value="{{ $auction->end_at->format('d.m.Y H:i') }}">
    </div>
    <div class="mb-3">
        <p>Шаг цены: <strong>{{ $auction->price_step }}%</strong></p>
        <p>Текущая цена: <strong>{{ number_format($auction->currentPrice(), 2, '.', ' ') }} ₽</strong></p>
    </div>

    @auth
        @if ($auction->isOpened() && $auction->end_at->isFuture())
            <a href="{{ route('bid.create', ['auction' => $auction]) }}" class="btn btn-primary">
                Оставить заявку
            </a>
        @endif
    @else
        <a href="{{ route('login') }}" class="btn btn-outline-primary">Войдите, чтобы оставить заявку</a>
    @endauth
@endsection
