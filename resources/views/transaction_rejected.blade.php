@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<style media="screen" type="text/css">
    th, td {
        max-width: 1000px;
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
            <h5>Transaction Rejected</h5>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <table id="datatables" class="table table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox"  id="bulkRetry" /> <button id="retryAll" class="btn btn-sm btn-primary">Reprocess</button></th>
                        <th>Trans ID Merchant</th>
                        <th>Channel</th>
                        <th>Item ID</th>
                        <th>Product Family</th>
                        <th>Product</th>
                        <th>ND</th>
                        <th>Duration</th>
                        <th>Price</th>
                        <th>PPN</th>
                        <th>TREG</th>
                        <th>WITEL</th>
                        <th>DATEL</th>
                        <th>Payment Date</th>
                        <th>Request Date</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Created Date</th>
                        <th>Payment</th>
                        <th>Prov Status</th>
                        <th>Error Code</th>
                        <th>Error Description</th>
                        <th>Reprocess</th>
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
    var edit_id = '';
    var edit_href = '';

    $(document).ready(function() {
        $.noConflict();
        $('#datatables').DataTable({
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
            ajax: '{{ route('transactionRejected.getAll') }}',
            columns: [
                { data: 'id',name: 'id', render: function ( data, type, row ) {
                        // if ( type === 'display' ) {
                        //     return '<input type="checkbox" class="">';
                        // }
                        // return data;
                        return '<input type="checkbox" data-id="'+data+'" class="checkRetry">';
                    }
                },
                { data: 'trans_id_merchant',name: 'trans_id_merchant'},
                { data: 'channel',name: 'channel'},
                { data: 'item_id',name: 'item_id'},
                { data: 'product_family',name: 'product_family'},
                { data: 'product',name: 'product'},
                { data: 'nd',name: 'nd'},
                { data: 'duration',name: 'duration'},
                { data: 'price',name: 'price'},
                { data: 'ppn',name: 'ppn'},
                { data: 'treg',name: 'treg'},
                { data: 'witel',name: 'witel'},
                { data: 'datel',name: 'datel'},
                { data: 'payment_dtm',name: 'payment_dtm'},
                { data: 'request_dtm',name: 'request_dtm'},
                { data: 'start_dtm',name: 'start_dtm'},
                { data: 'end_dtm',name: 'end_dtm'},
                { data: 'created_dtm',name: 'created_dtm'},
                { data: 'payment',name: 'payment'},
                { data: 'prov_status',name: 'prov_status'},
                { data: 'error_code',name: 'error_code'},
                { data: 'error_desc',name: 'error_desc'},
                { data: 'id',name: 'id', 
                    render: function ( data, type, row, meta ) {
                        var editHref = '{{ route("transactionRejected.retry") }}';

                        return '<a href="#" id="retry" data-id="'+data+'" data-href="'+editHref+'"><i class="fa fa-redo" style="color:blue"></i></a>';
                    }
                },
            ],
            columnDefs: [{
                targets: 0,
                orderable: false,
                sortable: false,
                searchable: false
            }],
            scrollX: true
        });
    });

    $(document).on("click", "#retry", function (e) {
        e.preventDefault();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type    : 'POST',
            url     : $(this).data('href'),
            data    : {_token:"{{ csrf_token() }}", id: $(this).data('id')},
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

                if(json.error == false){
                    $('#datatables').DataTable().ajax.reload();
                }else{
                    alert('Failed !');
                }
            }
        });
    });

    $("#bulkRetry").on('click',function() {
        var status = this.checked;
        $(".checkRetry").each( function() {
            $(this).prop("checked",status);
        });
    });

    $('#retryAll').on("click", function(event){
        if( $('.checkRetry:checked').length > 0 ){
            var ids = [];

            $('.checkRetry').each(function(){
                if($(this).is(':checked')) {
                    ids.push($(this).data("id"));
                }
            });

            var dataBulk = ids.join(',');

            // console.log(dataBulk);
            // var ids_string = ids.toString(); 
            // $.ajax({
            //     type: "POST",
            //     url: "employee-delete.php",
            //     data: {data_ids:ids_string},
            //     success: function(result) {
            //         dataTable.draw();
            //     },
            //     async:false
            // });


            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type    : 'POST',
                url     : '{{ route("transactionRejected.retryBulk") }}',
                data    : {_token:"{{ csrf_token() }}", id: dataBulk},
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

                    if(json.error == false){
                        $('#datatables').DataTable().ajax.reload();
                        if($('#bulkRetry').is(':checked')) {
                            $('#bulkRetry').click();
                        }
                    }else{
                        alert('Failed !');
                    }
                }
            });
        }
    });
</script>
@endif

@endsection