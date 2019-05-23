@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<style media="screen" type="text/css">
    th, td {
        max-width: 10000px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
@endsection

@section('content')
<div class="container">
@if(Auth::guard('user')->check())
    <div class="row mt-3 mb-3">
        <div class="col-md-6">
            <h5>Log</h5>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-4">
                    <select class="form-control" name="day" id="day">
                        <option value="">All</option>
                        @for($i=1;$i<=31;$i++)
                        <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" name="month" id="month">
                        <option value="">All</option>
                        <option value="1">Jan</option>
                        <option value="2">Feb</option>
                        <option value="3">Mar</option>
                        <option value="4">Apr</option>
                        <option value="5">Mei</option>
                        <option value="6">Jun</option>
                        <option value="7">Jul</option>
                        <option value="8">Aug</option>
                        <option value="9">Sep</option>
                        <option value="10">Oct</option>
                        <option value="11">Nov</option>
                        <option value="12">Dec</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select class="form-control" name="year" id="year">
                        <option value="2017">2017</option>
                        <option value="2018">2018</option>
                        <option value="2019" selected>2019</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <table id="datatables" class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>IP</th>
                        <th>Location</th>
                        <th>Latitude, Longitude</th>
                        <th>Date Time</th>
                        <th>Created At</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

@section('js.bottom')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type="text/javascript"></script>

@if(Auth::guard('user')->check())
<script>
    var datatables;

    $(document).ready(function() {
        $.noConflict();
        datatables = $('#datatables').DataTable({
            order: [[ 5, "desc" ]],
            processing: true,
            serverSide: true,
            dom: 'Bflrtip',
            buttons: {
                buttons: [
                    { extend: 'copyHtml5', className: 'btn btn-sm btn-secondary' },
                    { extend: 'excelHtml5', className: 'btn btn-sm btn-success' },
                    { extend: 'csvHtml5', className: 'btn btn-sm btn-primary' },
                    { extend: 'pdfHtml5', className: 'btn btn-sm btn-danger' },
                ]
            },
            ajax: '{{ route('log.getAll') }}',
            columns: [
                { data: 'activity',name: 'activity'},
                { data: 'ip',name: 'ip'},
                { data: 'location',name: 'location'},
                { data: 'longlat',name: 'longlat'},
                { data: 'datetime',name: 'datetime'},
                { data: 'created_at',name: 'created_at'},
            ],
            columnDefs: [
                {
                    targets: [0],
                    orderable: false
                },
                {
                    targets: [5],
                    visible: false,
                    searchable: false,
                    orderable: false
                },
            ],
            scrollX: true
        });
    });

    $('#day').on('change', function(){
        getData($(this).find(":selected").val(), $('#month').find(":selected").val(), $('#year').find(":selected").val());
    });

    $('#month').on('change', function(){
        getData($('#day').find(":selected").val(), $(this).find(":selected").val(), $('#year').find(":selected").val());
    });
    
    $('#year').on('change', function(){
        getData($('#day').find(":selected").val(), $('#month').find(":selected").val(), $(this).find(":selected").val());
    });

    function getData(day, month, year){
        datatables.ajax.url('{{ route('log.getAll') }}'+'?day='+day.toString()+'&month='+month.toString()+'&year='+year.toString()).load();
    }
</script>
@endif

@endsection