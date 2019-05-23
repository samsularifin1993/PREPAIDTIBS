@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel='stylesheet' href='https://use.fontawesome.com/releases/v5.7.0/css/all.css' integrity='sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ' crossorigin='anonymous'>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<style media="screen" type="text/css">
    th, td {
        max-width: 10000px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .input-width {
        max-width: 10000px;
    }

    .full_modal-dialog {
        width: 98% !important;
        height: 92% !important;
        min-width: 98% !important;
        min-height: 92% !important;
        max-width: 98% !important;
        max-height: 92% !important;
        padding: 0 !important;
    }

    .full_modal-content {
        height: 99% !important;
        min-height: 99% !important;
        max-height: 99% !important;
    }

    .tablex {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
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

        <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
            <div class="modal-dialog full_modal-dialog" role="document">
                <div class="modal-content full_modal-content">
                    <form id="form_edit" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="id" name="id">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf

                            <table id="data_edit" class="table table-hover tablex">
                                <thead>
                                    <tr>
                                        <th></th>
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
                                        <th>Payment</th>
                                        <th>Prov Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><div class="text-secondary"><strong>Old</strong></div></td>
                                        <td><div id="transidmerchant_old"></div></td>
                                        <td><div id="channel_old"></div></td>
                                        <td><div id="item_id_old"></div></td>
                                        <td><div id="product_family_old"></div></td>
                                        <td><div id="product_old"></div></td>
                                        <td><div id="nd_old"></div></td>
                                        <td><div id="duration_old"></div></td>
                                        <td><div id="price_old"></div></td>
                                        <td><div id="ppn_old"></div></td>
                                        <td><div id="treg_old"></div></td>
                                        <td><div id="witel_old"></div></td>
                                        <td><div id="datel_old"></div></td>
                                        <td><div id="payment_dtm_old"></div></td>
                                        <td><div id="request_dtm_old"></div></td>
                                        <td><div id="start_dtm_old"></div></td>
                                        <td><div id="end_dtm_old"></div></td>
                                        <td><div id="payment_type_old"></div></td>
                                        <td><div id="prov_status_old"></div></td>
                                    </tr>
                                    <tr>
                                        <td><div class="text-primary"><strong>New</strong></div></td>
                                        <td><input type="text" id="transidmerchant" name="transidmerchant"></div></td>
                                        <td><input type="text" id="channel" name="channel"></div></td>
                                        <td><input type="text" id="item_id" name="item_id"></div></td>
                                        <td><input type="text" id="product_family" name="product_family"></div></td>
                                        <td><input type="text" id="product" name="product"></div></td>
                                        <td><input type="text" id="nd" name="nd"></div></td>
                                        <td><input type="text" id="duration" name="duration"></div></td>
                                        <td><input type="text" id="price" name="price"></div></td>
                                        <td><input type="text" id="ppn" name="ppn"></div></td>
                                        <td><input type="text" id="treg" name="treg"></div></td>
                                        <td><input type="text" id="witel" name="witel"></div></td>
                                        <td><input type="text" id="datel" name="datel"></div></td>
                                        <td><input type="text" id="payment_dtm" name="payment_dtm"></div></td>
                                        <td><input type="text" id="request_dtm" name="request_dtm"></div></td>
                                        <td><input type="text" id="start_dtm" name="start_dtm"></div></td>
                                        <td><input type="text" id="end_dtm" name="end_dtm"></div></td>
                                        <td><input type="text" id="payment_type" name="payment_type"></div></td>
                                        <td><input type="text" id="prov_status" name="prov_status"></div></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row mt-3 mb-3">
                                <div class="col-md-12">
                                    <div class="card alert alert-warning">
                                        <div class="card-body">
                                            <ul>
                                                <li>Masukkan data dengan valid</li>
                                                <li>Format datetime <strong>YYYY-MM-DD HH24:MI:SS</strong> ex. <i>(2019-11-24 09:11:45)</i></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" name="close" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
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
                        <th>Edit</th>
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
                { data: 'transidmerchant',name: 'transidmerchant'},
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
                        var editHref = '{{ route("transactionRejected.edit") }}';

                        return '<a href="#" id="edit" data-id="'+data+'" data-href="'+editHref+'" data-toggle="modal" data-target="#edit_modal"><i class="fa fa-edit" style="color:#f4a742"></i></a>';
                    }
                },
                { data: 'id',name: 'id', 
                    render: function ( data, type, row, meta ) {
                        var editHref = '{{ route("transactionRejected.retry") }}';

                        return '<a href="#" id="retry" data-id="'+data+'" data-href="'+editHref+'"><i class="fa fa-redo" style="color:#4165f4"></i></a>';
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

    $(document).on("click", "#edit", function (e) {
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
                $.preloader.start({
                    modal: true,
                    src : 'sprites.png'
                });
            },
            succes  : function () {
            },
            error   : function (xhr, status, error) {
            },
            complete : function (data) {
                var json = JSON.parse(data.responseText);

                if(json.result == 'success'){
                    $('#id').val(json.data.id);

                    $('#transidmerchant_old').html(json.data.transidmerchant);
                    $('#channel_old').html(json.data.channel);
                    $('#item_id_old').html(json.data.item_id);
                    $('#product_family_old').html(json.data.product_family);
                    $('#product_old').html(json.data.product);
                    $('#nd_old').html(json.data.nd);
                    $('#price_old').html(json.data.price);
                    $('#ppn_old').html(json.data.ppn);
                    $('#treg_old').html(json.data.treg);
                    $('#witel_old').html(json.data.witel);
                    $('#datel_old').html(json.data.datel);
                    $('#payment_dtm_old').html(json.data.payment_dtm);
                    $('#request_dtm_old').html(json.data.request_dtm);
                    $('#start_dtm_old').html(json.data.start_dtm);
                    $('#end_dtm_old').html(json.data.end_dtm);
                    $('#payment_type_old').html(json.data.payment_type);
                    $('#prov_status_old').html(json.data.prov_status);

                    $('#transidmerchant').val(json.data.transidmerchant);
                    $('#channel').val(json.data.channel);
                    $('#item_id').val(json.data.item_id);
                    $('#product_family').val(json.data.product_family);
                    $('#product').val(json.data.product);
                    $('#nd').val(json.data.nd);
                    $('#price').val(json.data.price);
                    $('#ppn').val(json.data.ppn);
                    $('#treg').val(json.data.treg);
                    $('#witel').val(json.data.witel);
                    $('#datel').val(json.data.datel);
                    $('#payment_dtm').val(json.data.payment_dtm);
                    $('#request_dtm').val(json.data.request_dtm);
                    $('#start_dtm').val(json.data.start_dtm);
                    $('#end_dtm').val(json.data.end_dtm);
                    $('#payment_type').val(json.data.payment_type);
                    $('#prov_status').val(json.data.prov_status);
                }

                $.preloader.stop();
            }
        });
    });

    $(document).on("click", "button[class='close']", function () {
        $('input').val('');
    });

    $("#form_edit").on('submit', function(e){
        e.preventDefault();
            
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("transactionRejected.update") }}',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend: function(){
                $.preloader.start({
                    modal: true,
                    src : 'sprites.png'
                });
            },
            succes  : function () {
            },
            error   : function (xhr, status, error) {
            },
            complete : function (data) {
                var json = JSON.parse(data.responseText);

                if(json.error == false){
                    alert('Update success');
                    $("button[data-dismiss='modal']").click();
                    $('#datatables').DataTable().ajax.reload();
                }

                $.preloader.stop();
            }
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