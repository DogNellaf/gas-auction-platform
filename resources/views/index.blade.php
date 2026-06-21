@extends('layouts.app')
@section('title', 'Аукционы')
@section('content')
    <h1 class="mt-2">Аукционы</h1>

    @forelse ($auctions as $auction)
        <div class="row align-items-center border rounded p-3 mt-3 own-ads">
            <div class="col-sm">
                <small class="text-muted">Номер</small><br>
                <strong>{{ $auction->id }}</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Стартовая цена</small><br>
                <strong>{{ number_format($auction->start_price, 2, '.', ' ') }} ₽</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Минимальный шаг</small><br>
                <strong>{{ $auction->price_step }}%</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Начался</small><br>
                <strong>{{ $auction->created_at->format('d.m.Y H:i') }}</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Закончится</small><br>
                <strong>{{ $auction->end_at->format('d.m.Y H:i') }}</strong>
            </div>
            <div class="col-sm-auto">
                <a class="btn btn-info btn-sm" href="{{ route('detail', ['auction' => $auction]) }}">Подробнее</a>
            </div>
        </div>
    @empty
        <p class="text-muted mt-4">Активных аукционов нет.</p>
    @endforelse

    <div class="mt-3">
        {{ $auctions->links('pagination::bootstrap-4') }}
    </div>
@endsection
