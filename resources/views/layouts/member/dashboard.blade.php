@extends('main')
@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between">
        <h1 class="mt-4">Dashboard</h1>
            @can('check-btn')
            @if(auth()->user()->today_attendance && $attendance = is_null(auth()->user()->today_attendance->check_out))
                <form action="{{ route('attendances.update', encrypt(auth()->user()->today_attendance->id)) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button class="btn btn-danger mt-2 btn-block" id="btnNavbarSearch" type="submit"><i class="fas fa-sign-out"></i> Check Out</button>
                </form>
            @else
                <form action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    <button class="btn btn-success mt-2 btn-block" id="btnNavbarSearch" type="submit"><i class="fas fa-sign-in"></i> Check In</button>
                </form>
            @endif
            @endcan
        </div>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">{{auth()->user()->roles[0]->name}}</li>
        </ol>
        <h2 class="text-center">{{date('a')=='am' ? 'Good Morning!' : 'Good Evening'}}</h2>
        <h3 class="text-center text-secondary">Today is {{date('l')}}, Keep Working!</h3>
    </div>
@endsection
