@extends('layouts.admin')
@section('card')
    @forelse ($bids as $bid)
        <div class="row align-items-center border rounded p-3 mb-2">
            <div class="col-sm">
                <small class="text-muted">Заявка</small><br>
                <strong>{{ $bid->id }}</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Аукцион</small><br>
                <strong>#{{ $bid->auction->id }}</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Компания</small><br>
                <strong>{{ $bid->user->company_name }}</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Ставка</small><br>
                <strong>{{ number_format($bid->price, 2, '.', ' ') }} ₽</strong>
            </div>
            <div class="col-sm">
                <small class="text-muted">Статус</small><br>
                <strong class="
                    @if ($bid->isWin()) text-success
                    @elseif ($bid->isLose()) text-danger
                    @else text-secondary
                    @endif">
                    {{ $bid->status->label() }}
                </strong>
            </div>
            @if ($bid->isWin())
                <div class="col-sm-auto">
                    <a class="btn btn-success btn-sm" href="{{ route('admin.bids.show', $bid) }}">Договор</a>
                </div>
            @endif
        </div>
    @empty
        <p class="text-muted">Заявок нет.</p>
    @endforelse

    <div class="mt-3">
        {{ $bids->links('pagination::bootstrap-4') }}
    </div>
@endsection
