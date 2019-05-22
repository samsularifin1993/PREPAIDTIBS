@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row mt-3 mb-3">
        <div class="col-md-6">
            <h5>Test</h5>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add_modal">Add</button>
        </div>

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
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" aria-describedby="name" placeholder="Enter name" required>
                                <div id="error_name" class=""></div>
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
                                <label for="name_old">Name</label>
                                <input type="text" class="form-control" id="name_old" name="name_old" aria-describedby="name_old" placeholder="Enter name" required>
                                <div id="error_name_old" class=""></div>
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
                        <th>Name</th>
                        <th>Pilih</th>
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
    var edit_id = '';
    var edit_href = '';

    $(document).ready(function() {
        $.noConflict();
        $('#datatables').DataTable({
            processing: true,
            serverSide: true,
            dom: 'Bflrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            ajax: '{{ route('test.getAll') }}',
            columns: [
                { data: 'name',name: 'name'},
                { data: 'id',name: 'id', 
                    render: function ( data, type, row, meta ) {
                        var editHref = '{{ route("test.edit") }}';
                        var deleteHref = '{{ route("test.delete") }}';

                        return '<a href="#" id="edit" data-id="'+data+'" data-href="'+editHref+'" data-toggle="modal" data-target="#edit_modal">Edit</a> | <a href="#" id="delete" data-id="'+data+'" data-href="'+deleteHref+'">Delete</a>';
                    }
                },
            ]
        });
    });

    $('#add_modal').on('shown.bs.modal', function () {
        refresh();
    });

    $('#add_modal').on('hide.bs.modal', function () {
        refresh();
    });

    $('#edit_modal').on('shown.bs.modal', function () {
        refresh();
    });

    $('#edit_modal').on('hide.bs.modal', function () {
        refresh();
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
                $('#name_old').val(json[0].name);
            }
        });
    });

    $(document).on("click", "#delete", function (e) {
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
                    $("button[name='close']").click();
                    $('#datatables').DataTable().ajax.reload();
                }else{
                    alert('Failed !');
                }
            }
        });
    });

    $(document).on("click", "button[name='close']", function () {
        $('input').val('');
        refresh();
    });

    $(document).on("click", "button[class='close']", function () {
        $('input').val('');
        refresh();
    });

    $("#form_add").on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("test.store") }}',
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
                    $("button[name='close']").click();
                    $('#datatables').DataTable().ajax.reload();
                }else{
                    if(json.result.name != null){
                        $('#name').addClass('is-invalid');
                        $('#error_name').html(json.result.name[0]);
                        $('#error_name').addClass('invalid-feedback');
                    }
                }

                $.preloader.stop();
            }
        });
    });

    $("#form_edit").on('submit', function(e){
        e.preventDefault();
        
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("test.update") }}',
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
                    $("button[name='close']").click();
                    $('#datatables').DataTable().ajax.reload();
                }else{
                    if(json.result.name_old != null){
                        $('#name_old').addClass('is-invalid');
                        $('#error_name_old').html(json.result.name_old[0]);
                        $('#error_name_old').addClass('invalid-feedback');
                    }
                }

                $.preloader.stop();
            }
        });
    });

    function refresh(){
        $('input[name="_token"]').prop('value', '{{ csrf_token() }}');

        $('#name').removeClass('is-invalid');
        $('#error_name').empty();
        $('#error_name').removeClass('invalid-feedback');

        $('#name_old').removeClass('is-invalid');
        $('#error_name_old').empty();
        $('#error_name_old').removeClass('invalid-feedback');
    }
</script>
@endsection