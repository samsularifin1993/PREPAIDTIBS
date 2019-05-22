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
            <h5>User</h5>
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
                                <label for="name">Name</label>
                                <input type="text" class="form-control field" id="name" name="name" aria-describedby="name" placeholder="Enter name"autocomplete="off" required>
                                <div id="error_name" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="nik">NIK</label>
                                <input type="nik" class="form-control field" id="nik" name="nik" aria-describedby="nik" placeholder="Enter NIK"autocomplete="off" required>
                                <div id="error_nik" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control field" id="password" name="password" aria-describedby="password" placeholder="Enter password"autocomplete="off" required>
                                <div id="error_password" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" class="form-control field" id="password_confirm" name="password_confirm" aria-describedby="password_confirm" placeholder="Enter password confirmation"autocomplete="off" required>
                                <div id="error_password_confirm" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control field" name="role" id="role" aria-describedby="role" required>
                                    <option value="">- Pilih -</option>
                                </select>
                                <div id="error_role" class="field_error"></div>
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
                                <label for="name">Name</label>
                                <input type="text" class="form-control field" id="name_old" name="name_old" aria-describedby="name" placeholder="Enter name"autocomplete="off" required>
                                <div id="error_name_old" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="nik">Email</label>
                                <input type="nik" class="form-control field" id="nik_old" name="nik_old" aria-describedby="nik" placeholder="Enter NIK"autocomplete="off" required>
                                <div id="error_nik_old" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control field" name="role_old" id="role_old" aria-describedby="role_old" required>
                                    <option value="">- Pilih -</option>
                                </select>
                                <div id="error_role_old" class="field_error"></div>
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
                        <th>NIK</th>
                        <th>Role</th>
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
            buttons: {
                buttons: [
                    { extend: 'copyHtml5', className: 'btn btn-sm btn-secondary' },
                    { extend: 'excelHtml5', className: 'btn btn-sm btn-success' },
                    { extend: 'csvHtml5', className: 'btn btn-sm btn-primary' },
                    { extend: 'pdfHtml5', className: 'btn btn-sm btn-danger' },
                ]
            },
            ajax: '{{ route('user2.getAll') }}',
            columns: [
                { data: 'name',name: 'name'},
                { data: 'nik',name: 'nik'},
                { data: 'role',name: 'role'},
                { data: 'id',name: 'id', 
                    render: function ( data, type, row, meta ) {
                        var editHref = '{{ route("user2.edit") }}';
                        var deleteHref = '{{ route("user2.delete") }}';

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

        listRole();
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
                    $('#name_old').val(json[0].name);
                    $('#nik_old').val(json[0].nik);
                    $('#role_old').val(json[0].role);
                }
            });
        }
    });

    $(document).on("click", "#delete", function (e) {
        if("{{ $permission['d'] }}" === 'true'){
            e.preventDefault();

            if (window.confirm('Data akan di hapus ?'))
            {
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
                url: '{{ route("user2.store") }}',
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

                    if(json.error === true){
                        if(typeof json.result.name != 'undefined' && json.result.name[0] != null){
                            $('#name').addClass('is-invalid');
                            $('#error_name').html(json.result.name[0]);
                            $('#error_name').addClass('invalid-feedback');
                        }
                        if(typeof json.result.nik != 'undefined' && json.result.nik[0] != null){
                            $('#nik').addClass('is-invalid');
                            $('#error_nik').html(json.result.nik[0]);
                            $('#error_nik').addClass('invalid-feedback');
                        }
                        if(typeof json.result.password != 'undefined' && json.result.password[0] != null){
                            $('#password').addClass('is-invalid');
                            $('#error_password').html(json.result.password[0]);
                            $('#error_password').addClass('invalid-feedback');
                        }
                        if(typeof json.result.role != 'undefined' && json.result.role[0] != null){
                            $('#role').addClass('is-invalid');
                            $('#error_role').html(json.result.role[0]);
                            $('#error_role').addClass('invalid-feedback');
                        }
                    }else{
                        $("button[data-dismiss='modal']").click();
                        $('#datatables').DataTable().ajax.reload();
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
                url: '{{ route("user2.update") }}',
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
                        if(typeof json.errors.name_old != 'undefined' && json.errors.name_old[0] != null){
                            $('#name_old').addClass('is-invalid');
                            $('#error_name_old').html(json.errors.name_old[0]);
                            $('#error_name').addClass('invalid-feedback');
                        }

                        if(typeof json.errors.nik_old != 'undefined' && json.errors.nik_old[0] != null){
                            $('#nik_old').addClass('is-invalid');
                            $('#error_nik_old').html(json.errors.nik_old[0]);
                            $('#error_nik_old').addClass('invalid-feedback');
                        }

                        if(typeof json.errors.role_old != 'undefined' && json.errors.role_old[0] != null){
                            $('#role_old').addClass('is-invalid');
                            $('#error_role_old').html(json.errors.role_old[0]);
                            $('#error_role_old').addClass('invalid-feedback');
                        }
                    }

                    $.preloader.stop();
                }
            });
        }
    });

    function refresh(){
        $('input').val('');
        $('input[name="_token"]').prop('value', '{{ csrf_token() }}');

        $('.field').removeClass('is-invalid');
        $('.field_error').empty();
        $('.field_error').removeClass('invalid-feedback');
    }

    function listRole($position = ''){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '{{ route("role.item") }}',
            contentType: false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend: function(){
                $.preloader.start({
                    modal: true,
                    src : 'sprites.png'
                });

                $('#role')
                    .empty()
                    .append('<option value="">- Pilih -</option>');

                $('#role_old')
                    .empty()
                    .append('<option value="">- Pilih -</option>');
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

                if(json != null && json.length > 0){
                    for(var i=0; i<json.length; i++){
                        var id = json[i].id;
                        var name = json[i].name;

                        var option = "<option value='"+id+"'>"+name+"</option>"; 

                        $("#role").append(option);
                        $("#role_old").append(option);
                    }
                }

                if($position != ''){
                    $('select[id="role_old"] option[value="'+$position+'"]').prop('selected', true);
                }

                $.preloader.stop();
            }
        });
    }
</script>
@endsection