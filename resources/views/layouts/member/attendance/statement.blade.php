@extends('main')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer=""></script>
@section('content')
    <div class="container-fluid px-4">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <span><i class="fas fa-table me-1"></i>
                Attendance Log</span>
            </div>
            <form action="{{route('attendances.statement')}}" method="POST" class="d-flex justify-content-center">
                @csrf
            <div class='input-group align-self-center' style="width: 40% !important;">
                <input type="text" name="daterange" value="{{$daterange ?? '11/01/2022 - 11/30/2022'}}"  id="daterange"/>
                <span><button type="submit" class="btn btn-primary">Find</button></span>
            </div>
            </form>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Working Hours</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th>Date</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Working Hours</th>
                    </tr>
                    </tfoot>
                    <tbody>
                        @foreach($attendance as $att)
                            <tr>
                                <td>{{$att->attendance_date}}</td>
                                <td>{{$att->check_in}}</td>
                                <td>{{$att->check_out}}</td>
                                <td>{{$att->working_hours}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- Pagination --}}
                <div class="d-flex justify-content-center">
{{--                    {!! $users->links() !!}--}}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            $('input[name="daterange"]').daterangepicker({
                opens: 'left',
                autoApply:true
            }, function(start, end, label) {
                console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
            });
        });
    </script>
@endpush
