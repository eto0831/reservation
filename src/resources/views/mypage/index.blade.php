@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/shop-card.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    // メッセージ機能
</div>
<div class="user__name">
    <h2>{{ auth()->user()->name }}さん</h2>
</div>
<div class="mypage__content">
    <div class="reservation__container">
        <h2>予約一覧</h2>
        <div class="reservations__wrap">
            @foreach ($reservations as $reservation)
            <div class="reservation__contents">
                <div class="reservation__header">
                    <h3>予約 {{ $loop->iteration }}</h3>
                    <div class="reservation__menus">
                        <form action="/reservation/edit/{{ $reservation->id }}" class="reservation__edit" method="get">
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                            <button type="submit">目</button>
                        </form>
                        <form action="/reservation" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id}}">
                            <button type="submit">×</button>
                        </form>
                    </div>
                </div>
                <h4>{{ $reservation->shop->shop_name }}</h4>
                <p>Date: {{ $reservation->reserve_date }} </p>
                <p>Time: {{ \Carbon\Carbon::createFromFormat('H:i:s', $reservation->reserve_time)->format('H:i') }}
                </p>
                <p>Number: {{ $reservation->guest_count }}人</p>
                <div class="qr-code">
                    {!! QrCode::size(150)->generate(route('reservation.verify', $reservation->id)) !!}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="favorites__container">
        <h2>お気に入り一覧</h2>
        <div class="favorites__wrap">
            @foreach($favorites as $favorite)
            @include('components.shop-card', ['shop' => $favorite->shop])
            @endforeach
        </div>
    </div>
</div>
@endsection