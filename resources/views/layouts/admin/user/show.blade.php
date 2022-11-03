@extends('main')
@section('content')
    <div class="card text-center">
        <div class="card-header">
            User Information
        </div>
        <div class="card-body">
            <div class="text-center">
                <img src="{{ asset('images/'.($user->avatar??'default-logo.png'))}}" class="rounded img-thumbnail" alt="user image" style="height: 200px; width: 200px">
            </div>
            <h5 class="card-title">{{$user->name}}</h5>
            <h6 class="card-title">{{$user->email}}</h6>
            <p class="card-footer">{{date('F')}} Month Attendance Log</p>
            <span class="h6">Leave: {{$user->leave_count}}</span> |
            <span class="h6">Absent: {{$user->absent_count}}</span> |
            <span class="h6">Present: {{$user->present_count}}</span>
            <br>
            <a href="{{route('admin.users.edit', encrypt($user->id))}}" class="btn btn-primary">Edit Information</a>
        </div>
        <div class="card-footer text-muted">
            {{$user->is_admin?'Admin':'Member'}} since: {{$user->created_at->format('d-M-Y')}}
        </div>
    </div>
@endsection
