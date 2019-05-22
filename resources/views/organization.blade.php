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
            <h5>Organization</h5>
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
                                <label for="regional">Regional</label>
                                <select class="form-control field regional" name="opt_regional" id="opt_regional" aria-describedby="opt_regional" required>
                                    <option value="">- Pilih -</option>
                                </select>
                                <div id="error_regional" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="witel">Witel</label>
                                <select class="form-control field" name="opt_witel" id="opt_witel" aria-describedby="opt_witel" required>
                                    <option value="">- Pilih -</option>
                                </select>
                                <div id="error_witel" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="datel">Datel</label>
                                <input type="text" class="form-control field" id="datel" name="datel" aria-describedby="datel" placeholder="Enter datel"autocomplete="off" required>
                                <div id="error_datel" class="field_error"></div>
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
                                <label for="regional">Regional</label>
                                <select class="form-control field regional" name="opt_regional_old" id="opt_regional_old" aria-describedby="opt_regional_old" required disabled>
                                    <option value="">- Pilih -</option>
                                </select>
                                <div id="error_regional_old" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="witel">Witel</label>
                                <select class="form-control field" name="opt_witel_old" id="opt_witel_old" aria-describedby="opt_witel_old" required disabled>
                                    <option value="">- Pilih -</option>
                                </select>
                                <div id="error_witel_old" class="field_error"></div>
                            </div>
                            <div class="form-group">
                                <label for="datel">Datel</label>
                                <input type="text" class="form-control field" id="datel_old" name="datel_old" aria-describedby="datel_old" placeholder="Enter datel"autocomplete="off" required>
                                <div id="error_datel_old" class="field_error"></div>
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
                        <th>ID</th>
                        <th>Regional</th>
                        <th>Witel</th>
                        <th>Datel</th>
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
            ajax: '{{ route('organization.getAll') }}',
            columns: [
                { data: 'id',name: 'id'},
                { data: 'regional',name: 'regional'},
                { data: 'witel',name: 'witel'},
                { data: 'datel',name: 'datel'},
                { data: 'created',name: 'created'},
                { data: 'updated',name: 'updated'},
                { data: 'id',name: 'id', 
                    render: function ( data, type, row, meta ) {
                        var editHref = '{{ route("organization.edit") }}';
                        var deleteHref = '{{ route("organization.delete") }}';

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

        listRegional();
        listWitel();
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

                    console.log(json);

                    $('#id').val(json[0].id);
                    listRegional(json[0].regional);
                    listWitel(json[0].regional, json[0].witel);
                    $('#datel_old').val(json[0].datel);
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
                url: '{{ route("organization.store") }}',
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
                        if(json.result.regional != null){
                            $('#opt_regional').addClass('is-invalid');
                            $('#error_regional').html(json.result.regional[0]);
                            $('#error_regional').addClass('invalid-feedback');
                        }

                        if(json.result.witel != null){
                            $('#opt_witel').addClass('is-invalid');
                            $('#error_witel').html(json.result.witel[0]);
                            $('#error_witel').addClass('invalid-feedback');
                        }

                        if(json.result.datel != null){
                            $('#datel').addClass('is-invalid');
                            $('#error_datel').html(json.result.datel[0]);
                            $('#error_datel').addClass('invalid-feedback');
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
                url: '{{ route("organization.update") }}',
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
                        if(json.result.opt_regional_old != null){
                            $('#opt_regional_old').addClass('is-invalid');
                            $('#error_regional_old').html(json.result.opt_regional_old[0]);
                            $('#error_regional_old').addClass('invalid-feedback');
                        }

                        if(json.result.opt_witel_old != null){
                            $('#opt_witel_old').addClass('is-invalid');
                            $('#error_witel_old').html(json.result.opt_witel_old[0]);
                            $('#error_witel_old').addClass('invalid-feedback');
                        }

                        if(json.result.datel_old != null){
                            $('#datel_old').addClass('is-invalid');
                            $('#error_datel_old').html(json.result.datel_old[0]);
                            $('#error_datel_old').addClass('invalid-feedback');
                        }
                    }

                    $.preloader.stop();
                }
            });
        }
    });

    $("#add_regional").on('click', function(e){
        if("{{ $permission['i'] }}" === 'true'){
            e.preventDefault();

            if($(this).text() == 'Add'){
                disable();
                $("#regional").removeAttr('disabled');
                $("#add_regional").removeClass('d-none');
                $('#regional').removeClass('d-none');
                $('#opt_regional').addClass('d-none');
                $(this).text('Save');
                $('#cancel_regional').removeClass('d-none');
            }else if($(this).text() == 'Save'){
                var formData = new FormData(document.getElementById('form_add'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route("organization.store.regional") }}',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    dataType:'json',
                    beforeSend: function(){
                        
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
                            listRegional();
                            listWitel();
                            enable();
                            $('#opt_regional').removeClass('d-none');
                            $('#opt_regional').removeAttr('disabled');
                            $('#regional').addClass('d-none');
                            $('#add_regional').text('Add');
                            $('#cancel_regional').addClass('d-none');
                        }else{
                            if(json.result.regional != null){
                                $('#regional').addClass('is-invalid');
                                $('#error_regional').html(json.result.regional[0]);
                                $('#error_regional').addClass('invalid-feedback');
                            }
                        }
                    }
                });
            }
        }
    });

    $("#add_witel").on('click', function(e){
        if("{{ $permission['i'] }}" === 'true'){
            e.preventDefault();

            if($(this).text() == 'Add'){
                disable();
                $('#opt_regional').removeAttr('disabled');
                $("#witel").removeAttr('disabled');
                $("#add_witel").removeClass('d-none');
                $('#witel').removeClass('d-none');
                $('#opt_witel').addClass('d-none');
                $(this).text('Save');
                $('#cancel_witel').removeClass('d-none');
            }else if($(this).text() == 'Save'){
                var formData = new FormData(document.getElementById('form_add'));
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'POST',
                    url: '{{ route("organization.store.witel") }}',
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData:false,
                    dataType:'json',
                    beforeSend: function(){
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
                            listWitel($('#opt_regional').val());
                            enable();
                            $('#opt_witel').removeClass('d-none');
                            $('#opt_witel').removeAttr('disabled');
                            $('#witel').addClass('d-none');
                            $('#add_witel').text('Add');
                            $('#cancel_witel').addClass('d-none');

                            $('#opt_regional').removeClass('is-invalid');
                            $('#error_regional').empty();
                            $('#error_regional').removeClass('invalid-feedback');
                        }else{
                            if(json.result.opt_regional != null){
                                $('#opt_regional').addClass('is-invalid');
                                $('#error_regional').html(json.result.opt_regional[0]);
                                $('#error_regional').addClass('invalid-feedback');
                            }

                            if(json.result.witel != null){
                                $('#witel').addClass('is-invalid');
                                $('#error_witel').html(json.result.witel[0]);
                                $('#error_witel').addClass('invalid-feedback');
                            }
                        }
                    }
                });
            }
        }
    });

    $('#cancel_regional').on('click', function(){
        enable();
        $('#opt_regional').removeClass('d-none');
        $('#opt_regional').removeAttr('disabled');
        $('#regional').addClass('d-none');
        $('#add_regional').text('Add');
        $(this).addClass('d-none');
        $('#error_regional').empty();
    });

    $('#cancel_witel').on('click', function(){
        enable();
        $('#opt_witel').removeClass('d-none');
        $('#opt_witel').removeAttr('disabled');
        $('#witel').addClass('d-none');
        $('#add_witel').text('Add');
        $(this).addClass('d-none');

        $('#opt_regional').removeClass('is-invalid');
        $('#error_regional').removeClass('invalid-feedback');
        $('#error_regional').empty();
        $('#error_witel').empty();
    });

    function refresh(){
        $('input').val('');
        $('input[name="_token"]').prop('value', '{{ csrf_token() }}');

        $('.field').removeClass('is-invalid');
        $('.field_error').empty();
        $('.field_error').removeClass('invalid-feedback');
    }

    $('.regional').on('change', function(){
        if($(this).find(":selected").val() != ''){
            listWitel($(this).find(":selected").val());
        }else{
            $('#opt_witel')
                    .empty()
                    .append('<option value="">- Pilih -</option>');
            $('#opt_witel_old')
                    .empty()
                    .append('<option value="">- Pilih -</option>');
        }
    });

    function listRegional(position = ''){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '{{ route("organization.item.regional") }}',
            contentType: false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend: function(){
                $('#opt_regional')
                    .empty()
                    .append('<option value="">- Pilih -</option>');

                $('#opt_regional_old')
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

                        $("#opt_regional").append(option);
                        $("#opt_regional_old").append(option);
                    }
                }

                if(position != ''){
                    $('select[id="opt_regional_old"] option[value="'+position+'"]').prop('selected', true);
                }
            }
        });
    }

    function listWitel(regional = '', position = ''){
        if(regional != ''){
            var url = '{{ url("li_witel") }}';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: url+'/'+regional.toString(),
                contentType: false,
                cache: false,
                processData:false,
                dataType:'json',
                beforeSend: function(){
                    $('#opt_witel')
                        .empty()
                        .append('<option value="">- Pilih -</option>');

                    $('#opt_witel_old')
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

                            $("#opt_witel").append(option);
                            $("#opt_witel_old").append(option);
                        }
                    }

                    if(position != ''){
                        $('select[id="opt_witel"] option[value="'+position+'"]').prop('selected', true);
                        $('select[id="opt_witel_old"] option[value="'+position+'"]').prop('selected', true);
                    }
                }
            });
        }
    }

    function disable(){
        $("#regional").attr('disabled','disabled');
        $("#witel").attr('disabled','disabled');
        $("#datel").attr('disabled','disabled');
        $("#opt_regional").attr('disabled','disabled');
        $("#opt_witel").attr('disabled','disabled');
        $("#add_regional").addClass('d-none');
        $("#add_witel").addClass('d-none');
    }

    function enable(){
        $("#regional").removeAttr('disabled');
        $("#witel").removeAttr('disabled');
        $("#datel").removeAttr('disabled');
        $("#opt_regional").removeAttr('disabled');
        $("#opt_witel").removeAttr('disabled');
        $("#add_regional").removeClass('d-none');
        $("#add_witel").removeClass('d-none');
    }
</script>
@endif
@endsection