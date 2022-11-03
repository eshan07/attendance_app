@extends('main')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Role Management</h2>
            </div>
            <div class="pull-right">
                @can('role-create')
                    <a class="btn btn-success" href="{{ route('admin.roles.create') }}">New Role</a>
                @endcan
            </div>
        </div>
    </div>



    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($roles as $key => $role)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $role->name }}</td>
                <td class="d-flex">
                    <a class="btn text-info" href="{{ route('admin.roles.show',$role->id) }}"><i
                            class="fas fa-eye"></i></a>
                    @can('role-edit')
                    <a class="btn text-warning" href="{{ route('admin.roles.edit',$role->id) }}"><i
                            class="fas fa-edit"></i></a>
                    @endcan

                    @can('role-delete')
                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" onclick="return confirm('Are you sure?')"
                                    class="btn text-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    @endcan
                </td>
            </tr>
        @endforeach
    </table>


    {!! $roles->render() !!}
@endsection
