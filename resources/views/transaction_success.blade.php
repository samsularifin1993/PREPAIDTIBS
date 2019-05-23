@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<style media="screen" type="text/css">
    th, td {
        max-width: 500px;
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
            <h5>Transaction Success</h5>
        </div>
    </div>

    <div class="text-right">
        <select class="form-control-sm" id="filter_order">
            <option value="0">All</option>
            <option value="1">Channel</option>
            <option value="3">Product</option>
            <option value="12">Payment</option>
            <option value="13">Organization</option>
            <option value="15">Prov Status</option>
        </select>
        
        <select class="form-control-sm" id="filter_sort">
            <option value="asc">ASC</option>
            <option value="desc">DESC</option>
        </select>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <table id="datatables" class="table table-hover">
                <thead>
                    <tr>
                        <th>Trans ID Merchant</th>
                        <th>Channel</th>
                        <th>Item ID</th>
                        <th>Product</th>
                        <th>ND</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>PPN</th>
                        <th>Payment Date</th>
                        <th>Request Date</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Payment</th>
                        <th>Organization</th>
                        <th>Date Month</th>
                        <th>Prov Status</th>
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
    var filter_order = 0;
    var filter_sort = "asc";

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
                url: '{{ route('transaction.getAllSuccess') }}',
                type: "GET",
            },
            columns: [
                { data: 'transidmerchant',name: 'transidmerchant'},
                { data: 'channel',name: 'channel'},
                { data: 'item_id',name: 'item_id'},
                { data: 'product',name: 'product'},
                { data: 'nd',name: 'nd'},
                { data: 'duration',name: 'duration'},
                { data: 'price',name: 'price'},
                { data: 'ppn',name: 'ppn'},
                { data: 'payment_dtm',name: 'payment_dtm'},
                { data: 'request_dtm',name: 'request_dtm'},
                { data: 'start_dtm',name: 'start_dtm'},
                { data: 'end_dtm',name: 'end_dtm'},
                { data: 'payment',name: 'payment'},
                { data: 'org',name: 'org'},
                { data: 'datemonth',name: 'datemonth'},
                { data: 'prov_status',name: 'prov_status'},
            ],
            scrollX: true,
            order: [[ filter_order, filter_sort ]],

            /*initComplete: function () {
                this.api().columns().every( function () {
                    var column = this;
                    var select = $('<select><option value=""></option></select>')
                        .appendTo( $(column.footer()).empty() )
                        .on( 'change', function () {
                            var val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );
    
                            column
                                .search( val ? '^'+val+'$' : '', true, false )
                                .draw();
                        } );
    
                    column.data().unique().sort().each( function ( d, j ) {
                        select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                } );
            }*/
        });
    });

    $('#filter_order').on('change', function(){
        filter_order = $(this).val();
        datatables.order( [ filter_order, filter_sort ] ).draw();
    });

    $('#filter_sort').on('change', function(){
        filter_sort = $(this).val();
        datatables.order( [ filter_order, filter_sort ] ).draw();
    });
</script>
@endif

@endsection