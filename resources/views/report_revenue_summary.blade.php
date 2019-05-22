@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<style>
.align-text{
    text-align:right;
}

.bg-card{
    background-color:#d9b3ff;
}
</style>
@endsection

@section('content')
<div class="container">
    <div class="row mt-3 mb-3">
        <div class="col-md-6">
            <h5>Summary Revenue</h5>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-4">
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
        <div class="col-md-4 mb-2">
            <div class="card bg-card">
                <div class="card-body">
                    <p><strong><h4>REVENUE</h4></strong></p>
                    <h4 class="text-right"><strong id="revenue"></strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card bg-card">
                <div class="card-body">
                    <p><strong><h4>PPN</h4></strong></p>
                    <h4 class="text-right"><strong id="ppn"></strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card bg-card">
                <div class="card-body">
                    <p><strong><h4>TRANS. COUNT</h4></strong></p>
                    <h4 class="text-right"><strong id="trans_count"></strong></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <table id="datatables" class="table table-hover" width="100%">
                <thead>
                    <tr>
                        <th>TREG</th>
                        <th>WITEL</th>
                        <th>DATEL</th>
                        <th>Transaction Count</th>
                        <th>Revenue</th>
                        <th>PPN</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
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

<script>
    var datatables;

    $(document).ready(function() {
        $.noConflict();
        datatables = $('#datatables').DataTable({
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
            ajax: {
                url: '{{ route('report.getAllSummary') }}',
            },
            columns: [
                { data: 'treg',name: 'treg'},
                { data: 'witel',name: 'witel'},
                { data: 'datel',name: 'datel'},
                { data: 'trans_count',name: 'trans_count'},
                { data: 'price',name: 'price',
                    render: function ( data, type, row, meta ) {
                        return formatRupiah(data, 'Rp. ');
                    }
                },
                { data: 'ppn',name: 'ppn',
                    render: function ( data, type, row, meta ) {
                        return formatRupiah(data, 'Rp. ');
                    }
                },
            ],
            columnDefs: [
                {
                    targets: 4,
                    className: 'align-text'
                },
                {
                    targets: 5,
                    className: 'align-text'
                }
            ]
        });

        getData($('#month').find(":selected").val(), $('#year').find(":selected").val());
    });

    function formatRupiah(angka, prefix){
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);
    
        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
    
        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    $('#month').on('change', function(){
        getData($(this).find(":selected").val(), $('#year').find(":selected").val());
    });

    $('#year').on('change', function(){
        if($(this).find(":selected").val() != ''){
            getData($('#month').find(":selected").val(), $(this).find(":selected").val());
        }
    });

    function getData(month, year){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type    : 'POST',
            url     : "{{ route('getSummaryRevenue') }}",
            data    : {_token:"{{ csrf_token() }}", year: year, month: month},
            dataType: 'json',
            beforeSend: function() {
            },
            succes  : function () {
                console.log('Sukses');
            },
            error   : function (xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            },
            complete : function (data) {
                var json = JSON.parse(data.responseText);

                $('#revenue').text(formatRupiah(json.price.toString(), 'Rp. '));
                $('#ppn').text(formatRupiah(json.ppn.toString(), 'Rp. '));
                $('#trans_count').text(json.trans_count);
            }
        });

        datatables.ajax.url('{{ route('report.getAllSummary') }}'+'?month='+month.toString()+'&year='+year.toString()).load();
    }
</script>
@endsection