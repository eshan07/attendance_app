@extends('main')
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-table me-1"></i>
                User List</span>
                <span>{{substr($month_year, 0, -5) ?? date('F')}} Month Working Days: {{$total_working_days}}</span>
                <a href="{{route('admin.users.create')}}" class="btn btn-sm btn-primary">Add new</a>
            </div>
            <div class='input-group date datepicker align-self-center' id='table' style="width: 40% !important;">
                <input type='text' name="tgl" class="form-control" value="{{$month_year ?? 'Select month'}}"
                       id="calender"/>
                <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                  </span>
            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Total Days Present</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Total Days Present</th>
                        <th>Action</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td><span><img src="{{ asset('images/'.($user->avatar??'default-logo.png'))}}"
                                           class="rounded px-1" alt="Cinque Terre"
                                           style="width: 50px !important; "></span>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->present_count}}</td>
                            <td class="d-flex">
                                <a class="btn text-info" href="{{route('admin.users.show', encrypt($user->id))}}"><i
                                        class="fas fa-eye"></i></a>
                                <a class="btn text-warning" href="{{route('admin.users.edit', encrypt($user->id))}}"><i
                                        class="fas fa-edit"></i></a>
                                {{--                            <a class="btn text-danger" href="{{route('admin.users.destroy', encrypt($user->id))}}"  title="Delete" onclick="return confirm('Are you sure to delete this ?');"><i class="fas fa-trash"></i></a>--}}
                                <form action="{{ route('admin.users.destroy', encrypt($user->id)) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" onclick="return confirm('Are you sure?')"
                                            class="btn text-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{-- Pagination --}}
                <div class="d-flex justify-content-center">
                    {!! $users->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('#table').datepicker({
            format: 'MM/yyyy',
            icons: {
                time: 'fa fa-time',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            },
            startView: "months",
            minViewMode: "months"
        });
        $("#table").on("change", function () {
            //document.location.href = url;
            document.location = "{{ route('admin.users.index')}}" + "?month=" + $("#calender").val();
            {{--$.ajax({--}}
            {{--    url: "{{route('admin.users.index')}}?month=" + $("#calender").val(),--}}
            {{--    method: 'GET',--}}
            {{--    headers: {--}}
            {{--        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')--}}
            {{--    },--}}
            {{--    success : function(result){ // Has to be there !--}}

            {{--    },--}}

            {{--    error : function(result, error){ // Handle errors--}}

            {{--    }--}}

            {{--});--}}
        });
    </script>
@endpush
