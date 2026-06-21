@extends('layouts.admin')
@section('card')
    @forelse ($auctions as $auction)
        <div class="row align-items-center border rounded p-3 mb-2 own-ads">
            <div class="col-sm">
                <small class="text-muted">Номер</small><br>
                <strong>{{ $auction->id }}</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Статус</small><br>
                <strong>{{ $auction->status->label() }}</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Стартовая цена</small><br>
                <strong>{{ number_format($auction->start_price, 2, '.', ' ') }} ₽</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Шаг</small><br>
                <strong>{{ $auction->price_step }}%</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">{{ $auction->end_at->isFuture() ? 'Закончится' : 'Закончился' }}</small><br>
                <strong>{{ $auction->end_at->format('d.m.Y H:i') }}</strong>
            </div>
            <div class="col-sm-auto d-flex gap-2">
                <a class="btn btn-info btn-sm" href="{{ route('admin.auction.edit', $auction) }}">Редактировать</a>
                @unless ($auction->isClosed())
                    <form action="{{ route('admin.auction.end', $auction) }}" method="POST"
                          onsubmit="return confirm('Завершить аукцион #{{ $auction->id }}?')">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="id" value="{{ $auction->id }}">
                        <button type="submit" class="btn btn-danger btn-sm">Завершить</button>
                    </form>
                @endunless
            </div>
        </div>
    @empty
        <p class="text-muted">Аукционы не найдены. <a href="{{ route('admin.auction.create') }}">Создать?</a></p>
    @endforelse

    <div class="mt-3">
        {{ $auctions->links('pagination::bootstrap-4') }}
    </div>
@endsection
