@extends('layouts.admin')
@section('card')
    <h5>Аукцион #{{ $auction->id }}</h5>
    <form action="{{ route('admin.auction.update', $auction) }}" method="POST">
        @method('PATCH')
        @csrf
        <input type="hidden" name="id" value="{{ $auction->id }}">

        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea name="description" id="description" rows="3"
                class="form-control @error('description') is-invalid @enderror">{{ old('description', $auction->description) }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="start_price" class="form-label">Стартовая цена (₽)</label>
            <input name="start_price" id="start_price" type="number" step="0.01" min="0.01"
                class="form-control @error('start_price') is-invalid @enderror"
                value="{{ old('start_price', $auction->start_price) }}">
            @error('start_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="end_at" class="form-label">Дата и время окончания</label>
            <input name="end_at" id="end_at" type="datetime-local"
                class="form-control @error('end_at') is-invalid @enderror"
                value="{{ old('end_at', $auction->end_at->format('Y-m-d\TH:i')) }}">
            @error('end_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="price_step" class="form-label">Шаг цены: <span id="price_step_val">{{ old('price_step', $auction->price_step) }}%</span></label>
            <input name="price_step" id="price_step" type="range" min="1" max="100"
                class="form-range @error('price_step') is-invalid @enderror"
                value="{{ old('price_step', $auction->price_step) }}"
                oninput="document.getElementById('price_step_val').textContent = this.value + '%'">
            @error('price_step')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror" required>
                @foreach (\App\Enums\AuctionStatus::cases() as $s)
                    <option value="{{ $s->value }}" @selected(old('status', $auction->status->value) === $s->value)>
                        {{ $s->label() }}
                    </option>
                @endforeach
            </select>
            @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('admin.index') }}" class="btn btn-secondary ms-2">Назад</a>
    </form>
@endsection
