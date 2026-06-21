@extends('layouts.admin')
@section('card')
    @if ($requests->count() == 0)
        <div class="row">
            <div class="col">
                Заявки не найдены
            </div>
        </div>
    @else
        @foreach ($requests as $request)
            <div class="row own-ads">
                <div class="col">
                    <span class="bid">
                        Заявка<br><strong>{{$request->id}}</strong>
                    </span>
                </div>
                <div class="col">
                    <span class="bid">
                        Аукцион<br><strong>{{$request->auction->id}}</strong>
                    </span>
                </div>
                <div class="col">
                    <span class="bid">
                        Ставка<br><strong>{{$request->price}} Руб.</strong>
                    </span>
                </div>
                <div class="col">
                    <span class="ends">
                        Статус<br>
                        <strong>
                            @if ($request->status == "Waiting")
                            Ожидание завершения аукциона
                            @elseif ($request->status == "Win")
                            Победа
                            @elseif ($request->status == "Lose")
                            Проигрыш
                            @endif
                        </strong>
                    </span>
                </div>
                @if ($request->status == "Win")
                    <div class="col">
                        <a class="btn btn-info detail" href="#">Договор</a>
                    </div>
                @else
                    <div class="col">
                        
                    </div>
                @endif								
            </div>  
        @endforeach
        <div class="row">
            {{ $requests->links('pagination::bootstrap-4') }}
        </div>
    @endif
@endsection('content')