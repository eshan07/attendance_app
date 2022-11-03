@extends('main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card shadow-lg border-0 rounded-lg mt-5">
                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Edit {{$user->name}} Information</h3></div>
                    <div class="card-body">
                        <form action="{{route('admin.users.update', encrypt($user->id))}}"method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input name="name" value="{{$user->name}}" class="form-control" id="inputFirstName" type="text" placeholder="Enter your name"/>
                                        <label for="inputFirstName">Name</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input  name="avatar" class="form-control form-control-sm" id="formFileSm" type="file">
                                    </div>
                                </div>
                            </div>
                            <div class="form-floating mb-3">
                                <input name="email" value="{{$user->email}}"  class="form-control" id="inputEmail" type="email" placeholder="name@example.com" />
                                <label for="inputEmail">Email address</label>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <input name="password" class="form-control" id="inputPassword" type="password" placeholder="Create a password" />
                                        <label for="inputPassword">Password</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-floating mb-3 mb-md-0">
                                        <select class="form-select pt-0" aria-label="Default select" name="role">
                                            <option value="admin" {{$user->is_admin ? 'selected' : ''}}>Admin</option>
                                            <option value="member" {{$user->is_admin ? '' : 'selected'}}>Member</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 mb-0">
                                <div class="d-grid"><button type="submit" class="btn btn-primary btn-block">Update</button></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
