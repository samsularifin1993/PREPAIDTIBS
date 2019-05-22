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
            <h5>Payment</h5>
        </div>

        @if($permission['i'] === 'true')
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_modal">Add</button>
        </div>
        @endif

        <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="add" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="form_add" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title">Add</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label for="type">Type</label>
                                <input type="text" class="form-control field" id="type" name="type" aria-describedby="type" placeholder="Enter type"autocomplete="off" required>
                                <div id="error_type" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control field" name="description" id="" cols="30" rows="10" aria-describedby="description" placeholder="Enter description"autocomplete="off" required></textarea>
                                <div id="error_description" class="field_error"></div>
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

        <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
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
                            <div class="form-group">
                                <label for="type">Type</label>
                                <input type="text" class="form-control field" id="type_old" name="type_old" aria-describedby="type_old" placeholder="Enter type"autocomplete="off" required>
                                <div id="error_type_old" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control field" name="description_old" id="description_old" cols="30" rows="10" aria-describedby="description_old" placeholder="Enter description"autocomplete="off" required></textarea>
                                <div id="error_description_old" class="field_error"></div>
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
                        <th>Code</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Created</th>
                        <th>Updated</th>
                        <th>Pilih</th>
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
            ajax: '{{ route('payment.getAll') }}',
            columns: [
                { data: 'id',name: 'id'},
                { data: 'type',name: 'type'},
                { data: 'description',name: 'description'},
                { data: 'created',name: 'created'},
                { data: 'updated',name: 'updated'},
                { data: 'id',name: 'id', 
                    render: function ( data, type, row, meta ) {
                        var editHref = '{{ route("payment.edit") }}';
                        var deleteHref = '{{ route("payment.delete") }}';

                        var linkOption = '';

                        if("{{ $permission['u'] }}" === 'true'){
                            linkOption = linkOption+'<a href="#" id="edit" data-id="'+data+'" data-href="'+editHref+'" data-toggle="modal" data-target="#edit_modal">Edit</a>';
                        }

                        if("{{ $permission['d'] }}" === 'true'){
                            if(linkOption != ''){
                                linkOption = linkOption+' | ';
                            }

                            linkOption = linkOption+'<a href="#" id="delete" data-id="'+data+'" data-href="'+deleteHref+'">Delete</a>';
                        }
                        
                        return linkOption;
                    }
                },
            ]
        });
    });

    $('#add_modal').on('shown.bs.modal', function () {
        // refresh();
    });

    $('#add_modal').on('hide.bs.modal', function () {
        refresh();
    });

    $('#edit_modal').on('shown.bs.modal', function () {
        // refresh();
    });

    $('#edit_modal').on('hide.bs.modal', function () {
        refresh();
    });

    $(document).on("click", "#edit", function (e) {
        if("{{ $permission['u'] }}" === 'true'){
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

                    $('#id').val(json[0].id);
                    $('#type_old').val(json[0].type);
                    $('#description_old').val(json[0].description);
                }
            });
        }
    });

    $(document).on("click", "#delete", function (e) {
        if("{{ $permission['d'] }}" === 'true'){
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
                        $("button[data-dismiss='modal']").click();
                        $('#datatables').DataTable().ajax.reload();
                    }else{
                        alert('Failed !');
                    }
                }
            });
        }
    });

    $(document).on("click", "button[name='close']", function () {
        refresh();
    });

    $(document).on("click", "button[class='close']", function () {
        refresh();
    });

    $("#form_add").on('submit', function(e){
        if("{{ $permission['i'] }}" === 'true'){
            e.preventDefault();
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("payment.store") }}',
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

                    refresh();
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
                        $("button[data-dismiss='modal']").click();
                        $('#datatables').DataTable().ajax.reload();
                    }else{
                        if(json.result.type != null){
                            $('#type').addClass('is-invalid');
                            $('#error_type').html(json.result.type[0]);
                            $('#error_type').addClass('invalid-feedback');
                        }

                        if(json.result.description != null){
                            $('#description').addClass('is-invalid');
                            $('#error_description').html(json.result.description[0]);
                            $('#error_description').addClass('invalid-feedback');
                        }
                    }

                    $.preloader.stop();
                }
            });
        }
    });

    $("#form_edit").on('submit', function(e){
        if("{{ $permission['u'] }}" === 'true'){
            e.preventDefault();
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: '{{ route("payment.update") }}',
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

                    refresh();
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
                        $("button[data-dismiss='modal']").click();
                        $('#datatables').DataTable().ajax.reload();
                    }else{
                        if(json.result.type_old != null){
                            $('#type_old').addClass('is-invalid');
                            $('#error_type_old').html(json.result.type_old[0]);
                            $('#error_type_old').addClass('invalid-feedback');
                        }

                        if(json.result.description_old != null){
                            $('#description_old').addClass('is-invalid');
                            $('#error_description_old').html(json.result.description_old[0]);
                            $('#error_description_old').addClass('invalid-feedback');
                        }
                    }

                    $.preloader.stop();
                }
            });
        }
    });

    function refresh(){
        $('input').val('');
        $('textarea').val('');
        $('input[name="_token"]').prop('value', '{{ csrf_token() }}');

        $('.field').removeClass('is-invalid');
        $('.field_error').empty();
        $('.field_error').removeClass('invalid-feedback');
    }
</script>
@endif
@endsection