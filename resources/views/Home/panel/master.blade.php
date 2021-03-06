@extends('Home.master')

@section('content')

    <ul class="nav nav-tabs">
        <li class="{{\Illuminate\Support\Facades\Route::currentRouteName() == 'user.panel' ? 'active' : ''}}">
            <a href="{{route('user.panel')}}">اطلاعات کاربری</a></li>
        <li class="{{\Illuminate\Support\Facades\Route::currentRouteName() == 'user.panel.history' ? 'active' : ''}}">
            <a href="{{route('user.panel.history')}}">پرداخت های انجام شده</a></li>
        <li class="{{\Illuminate\Support\Facades\Route::currentRouteName() == 'user.panel.vip' ? 'active' : ''}}">
            <a href="{{route('user.panel.vip')}}">شارژ vip</a></li>
    </ul>

    <p>{{ $slot }}</p>
@endsection


