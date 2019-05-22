@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<style media="screen" type="text/css">
    th, td {
        max-width: 200px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
@endsection

@section('content')
<div class="container">
@if(Auth::guard('user')->check())
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
      </div>

      <div class="row mt-3 mb-3">
        <div class="col-md-4 mb-2">
            <div class="card" style="background-color:#00ace6">
                <div class="card-body">
                    <p><strong><h4>USER</h4></strong></p>
                    <h4 class="text-right"><strong>{{ $list['user'] }}</strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card" style="background-color:#ff6666">
                <div class="card-body">
                    <p><strong><h4>CHANNEL</h4></strong></p>
                    <h4 class="text-right"><strong>{{ $list['channel'] }}</strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card" style="background-color:#00cc44">
                <div class="card-body">
                    <p><strong><h4>PAYMENT</h4></strong></p>
                    <h4 class="text-right"><strong>{{ $list['payment'] }}</strong></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-6 mb-2">
            <div class="card" style="background-color:#ff7733">
                <div class="card-body">
                    <p><strong><h4>PRODUCT FAMILY</h4></strong></p>
                    <h4 class="text-right"><strong>{{ $list['product_family'] }}</strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-2">
            <div class="card" style="background-color:#cccc00">
                <div class="card-body">
                    <p><strong><h4>PRODUCT</h4></strong></p>
                    <h4 class="text-right"><strong>{{ $list['product'] }}</strong></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-4 mb-2">
            <div class="card" style="background-color:#75a3a3">
                <div class="card-body">
                    <p><strong><h4>REGIONAL</h4></strong></p>
                    <h4 class="text-right"><strong>{{ $list['regional'] }}</strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card" style="background-color:#cc9966">
                <div class="card-body">
                    <p><strong><h4>WITEL</h4></strong></p>
                    <h4 class="text-right"><strong>{{ $list['witel'] }}</strong></h4>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card" style="background-color:#d279a6">
                <div class="card-body">
                    <p><strong><h4>DATEL</h4></strong></p>
                    <h4 class="text-right"><strong>{{ $list['datel'] }}</strong></h4>
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
</script>
@endif

@endsection