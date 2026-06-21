@extends('layouts.app')
@section('title', 'Подать заявку')
@section('content')
    <h4 class="mt-3">Заявка на аукцион #{{ $auction->id }}</h4>

    <div class="mb-3 p-3 bg-light rounded">
        <p class="mb-1">Текущая цена: <strong>{{ number_format($auction->currentPrice(), 2, '.', ' ') }} ₽</strong></p>
        <p class="mb-1">Минимальный шаг: <strong>{{ $auction->price_step }}%</strong></p>
        <p class="mb-0">Минимальная ставка: <strong>{{ number_format($auction->minimumNextBid(), 2, '.', ' ') }} ₽</strong></p>
    </div>

    <form action="{{ route('bid.store', ['auction' => $auction]) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="price" class="form-label">Ваша ставка (₽)</label>
            <input
                name="price"
                id="price"
                class="form-control @error('price') is-invalid @enderror"
                type="number"
                step="0.01"
                min="{{ ceil($auction->minimumNextBid()) }}"
                value="{{ old('price', ceil($auction->minimumNextBid())) }}"
                required
            >
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Подать заявку</button>
        <a href="{{ route('detail', $auction) }}" class="btn btn-secondary ms-2">Назад</a>
    </form>
@endsection
