@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
@endsection

@section('content')
<div class="container">
@if(Auth::guard('user')->check())
    <div class="row mt-3 mb-3">
        <div class="col-md-6">
            <h5>API</h5>
        </div>

        <div class="col-md-6 text-right">
            <div class="row">
                <div class="col-md-8">
                    <input type="text" class="form-control" id="token">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-primary" id="tokenButton">Generate</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <table id="datatables" class="table table-hover">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>End Point</th>
                        <th>Parameter</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>GET</td>
                        <td>Transaction Success</td>
                        <td>http://domain/api/trans_success</td>
                        <td>
                            <i>month</i>, <i>year</i>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Revenue by organization</td>
                        <td>http://domain/api/rev_by_org</td>
                        <td>
                            <i>month</i>, <i>year</i>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Revenue by product</td>
                        <td>http://domain/api/rev_by_product</td>
                        <td>
                            <i>month</i>, <i>year</i>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <div class="card alert alert-warning">
                <div class="card-body">
                    <ul>
                        <li>Generate token untuk mengubah token baru</li>
                        <li>Sesuaikan type request</li>
                        <li>Akses query string URL : End Point + token + parameter</li>
                    </ul>
                </div>
            </div>
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
    $(document).ready(function(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '{{ route("api.get_token") }}',
            contentType: false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend: function(){
            },
            succes  : function () {
            },
            error   : function (xhr, status, error) {
            },
            complete : function (data) {
                var json = JSON.parse(data.responseText);
                $('#token').val(json.token);
            }
        });
    });

    $('#tokenButton').on('click', function(){
        generate();
    });

    function generate(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '{{ route("api.generate_token") }}',
            contentType: false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend: function(){
            },
            succes  : function () {
            },
            error   : function (xhr, status, error) {
            },
            complete : function (data) {
                var json = JSON.parse(data.responseText);
                $('#token').val(json.token);
            }
        });
    }
</script>
@endif
@endsection