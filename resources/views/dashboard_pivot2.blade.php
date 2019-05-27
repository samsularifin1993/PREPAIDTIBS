@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<style media="screen" type="text/css">
    th, td, .pvtUiCell, .nav-link {
        max-width: 10000px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .input-width {
        max-width: 10000px;
    }

    .full_modal-dialog {
        width: 98% !important;
        height: 40% !important;
        min-width: 98% !important;
        min-height: 40% !important;
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
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.css">
<link rel="stylesheet" type="text/css" href="https://pivottable.js.org/dist/pivot.css">
@endsection

@section('content')
<div class="container">
@if(Auth::guard('user')->check())
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
          <div class="input-group">
            <select class="form-control" name="year" id="year">
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019" selected>2019</option>
            </select>
        </div>
        </div>
      </div>

      <div id="output"></div>

      <div class="modal fade" id="filter_modal" tabindex="-1" role="dialog" aria-labelledby="filter" aria-hidden="true">
            <div class="modal-dialog full_modal-dialog" role="document">
                <div class="modal-content full_modal-content">
                    <form id="form_filter" method="post" enctype="multipart/form-data">
                        <input type="hidden" id="id" name="id">
                        <div class="modal-header">
                            <h5 class="modal-title">Filter</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @csrf

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="opt_org">Organization</label>
                                        <select name="opt_org" id="opt_org" class="form-control field">
                                            <option value="">All</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="opt_product">Product</label>
                                        <select name="opt_product" id="opt_product" class="form-control field">
                                            <option value="">All</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="opt_payment">Payment</label>
                                        <select name="opt_payment" id="opt_payment" class="form-control field">
                                            <option value="">All</option>
                                        </select>
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

    <div class="row">
        <div class="col-md-6"></div>
        <div class="col-md-6 text-right">
            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#filter_modal">Filter</button>
        </div>
    </div>

      <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>

      <canvas class="my-4 w-100" id="myChart2" width="900" height="380"></canvas>

    <div class="row mt-3 mb-3">
        <div class="col-md-6">
            <h5>Success Transaction</h5>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <table id="datatables" class="table table-hover">
                <thead>
                    <tr>
                        <th>Trans ID Merchant</th>
                        <th>Channel</th>
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://pivottable.js.org/dist/pivot.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

<script type="text/javascript" src="http://localhost/pivottable/dist/export_renderers.js"></script>
<script type="text/javascript" src="http://localhost/pivottable/dist/d3_renderers.js"></script>
<script type="text/javascript" src="http://localhost/pivottable/dist/c3_renderers.js"></script>

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js" type="text/javascript"></script>

<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js" type="text/javascript"></script>
@if(Auth::guard('user')->check())
<script>
    var myChart;
    var myChart2;

    function viewChart(revenue, ppn){
        if(myChart != undefined || myChart != null){
            myChart.destroy();
        }

        var ctx = document.getElementById('myChart').getContext('2d');
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Revenue',
                    data: revenue,
                    backgroundColor: 'transparent',
                    borderColor: '#007bff',
                    borderWidth: 1
                }, {
                    label: 'PPN',
                    data: ppn,
                    backgroundColor: 'transparent',
                    borderColor: '#a142f4',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                legend: {
                    onClick: function (e) { e.stopPropagation(); }
                }
            }
        });
    }

    function viewChart2(labelData, dataValue, bgColor){
        if(myChart2 != undefined || myChart2 != null){
            myChart2.destroy();
        }

        var ctx = document.getElementById('myChart2').getContext('2d');

        myChart2 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labelData,
                datasets: [{
                    label: 'Product',
                    data: dataValue,
                    backgroundColor: bgColor,
                    borderColor: 'transparent',
                    borderWidth: 1
                }]
            },
            options: {
                legend: {
                    display: false,
                },
                title: {
                    display: true,
                    text: 'Product'
				},
                onClick:function(e){
                    var activePoints = myChart2.getElementsAtEvent(e);
                    // var selectedIndex = activePoints[0]._index;
                    // alert(this.data.datasets[0].data[selectedIndex]);
                    console.log(this.data);
                }
            }
        });
    }

    function getPivot(datasource){
        var derivers = $.pivotUtilities.derivers;

        var renderers = $.extend(
            $.pivotUtilities.renderers,
            $.pivotUtilities.c3_renderers,
            $.pivotUtilities.d3_renderers,
            $.pivotUtilities.export_renderers,
            $.pivotUtilities.plotly_renderers
            );
        
        var tpl = $.pivotUtilities.aggregatorTemplates;

        $("#output").pivotUI(
            datasource,
            {
                renderers: renderers,
                cols: [], rows: ["Price"],
                rendererName: "Bar Chart"
            }
        );

        // $('#output table').removeClass('pvtUI');
        $('#output table').addClass('table');
    }

    $('#year').on('change', function(){
        if($(this).find(":selected").val() != ''){
            getChart($(this).find(":selected").val());
        }
    });

    function getChart(year){
        var arrayData = [0,0,0,0,0,0,0,0,0,0,0,0];
        var labelData = [];
        var valueData = [];
        var bgColor = [];

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type    : 'POST',
            url     : "{{ route('dashboard.value') }}",
            data    : {_token:"{{ csrf_token() }}", year: year},
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

                if(json.length != 0){
                    for(var i=0; i < json.length; i++){
                        arrayData[json[i].datemonth - 1] = parseInt(json[i].sum_price);
                    }
                }
                
                var data1 = arrayData;

                arrayData = [0,0,0,0,0,0,0,0,0,0,0,0];

                if(json.length != 0){
                    for(var i=0; i < json.length; i++){
                        arrayData[json[i].datemonth - 1] = parseInt(json[i].sum_ppn);
                    }
                }

                var data2 = arrayData;

                viewChart(data1, data2);
            }
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type    : 'POST',
            url     : "{{ route('dashboard.product') }}",
            data    : {_token:"{{ csrf_token() }}", year: year},
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

                var valData = 105;
                var x = 0;

                if(json.length != 0){
                    var z = valData / json.length;
                    for(var i=0; i < json.length; i++){
                        labelData[i] = json[i].product;
                        valueData[i] = parseInt(json[i].count);

                        if(parseInt(json[i].count) >= x){
                            bgColor[i] = 'hsl('+valData.toString()+', 100%, 60%)';
                        }else{
                            valData = valData - z;
                            bgColor[i] = 'hsl('+valData.toString()+', 100%, 60%)';
                        }

                        x = parseInt(json[i].count);
                    }
                }

                viewChart2(labelData, valueData, bgColor);
            }
        });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type    : 'POST',
            url     : "{{ route('dashboard.productAll2') }}",
            data    : {_token:"{{ csrf_token() }}", year: year},
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
                json = JSON.parse(JSON.stringify(json).split('"product":').join('"Product":'));
                json = JSON.parse(JSON.stringify(json).split('"product_family":').join('"Product Family":'));
                json = JSON.parse(JSON.stringify(json).split('"regional":').join('"TREG":'));
                json = JSON.parse(JSON.stringify(json).split('"witel":').join('"WITEL":'));
                json = JSON.parse(JSON.stringify(json).split('"datel":').join('"DATEL":'));
                json = JSON.parse(JSON.stringify(json).split('"payment":').join('"Payment Type":'));
                json = JSON.parse(JSON.stringify(json).split('"price":').join('"Price":'));
                json = JSON.parse(JSON.stringify(json).split('"ppn":').join('"PPN":'));
                getPivot(json);
            }
        });
    }

    $("input[type='search']").on("change", function(e) {
        var value = $(this).val();
        alert(value);
        // if ($(this).data("lastval") != value) {

        //     $(this).data("lastval", value);
        //     clearTimeout(timerid);

        //     timerid = setTimeout(function() {
        //     //your change action goes here 
        //     console.log(value);
        //     }, 500);
        // };
    });

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
            ajax: '{{ route('transaction.getAllSuccess') }}',
            columns: [
                { data: 'transidmerchant',name: 'transidmerchant'},
                { data: 'channel',name: 'channel'},
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
                { data: 'prov_status',name: 'prov_status'}
            ],
            scrollX: true
        });

        listOrg();
        listProduct();
        listPayment();
        getChart($('#year').find(":selected").val());
    });

    $("#form_filter").on('submit', function(e){
        e.preventDefault();

        var arrayData = [0,0,0,0,0,0,0,0,0,0,0,0];
        var formData = new FormData();
        formData.append("org", $('#opt_org').find(":selected").val());
        formData.append("product", $('#opt_product').find(":selected").val());
        formData.append("payment", $('#opt_payment').find(":selected").val());
        formData.append("year", $('#year').find(":selected").val());
            
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },            
            type    : 'POST',
            url     : "{{ route('dashboard.value') }}",
            data    : formData,
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

                if(json.length != 0){
                    for(var i=0; i < json.length; i++){
                        arrayData[json[i].datemonth - 1] = parseInt(json[i].sum_price);
                    }
                }
                
                var data1 = arrayData;

                arrayData = [0,0,0,0,0,0,0,0,0,0,0,0];

                if(json.length != 0){
                    for(var i=0; i < json.length; i++){
                        arrayData[json[i].datemonth - 1] = parseInt(json[i].sum_ppn);
                    }
                }

                var data2 = arrayData;

                viewChart(data1, data2);

                $("button[data-dismiss='modal']").click();
            }
        });
    });

    function listOrg(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '{{ route("organization.item.map") }}',
            contentType: false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend: function(){
                $('#opt_org')
                    .empty()
                    .append('<option value="">All</option>');
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

                        $("#opt_org").append(option);
                    }
                }
            }
        });
    }

    function listProduct(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '{{ route("product.map") }}',
            contentType: false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend: function(){
                $('#opt_product')
                    .empty()
                    .append('<option value="">All</option>');
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

                        $("#opt_product").append(option);
                    }
                }
            }
        });
    }

    function listPayment(){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            url: '{{ route("payment.map") }}',
            contentType: false,
            cache: false,
            processData:false,
            dataType:'json',
            beforeSend: function(){
                $('#opt_payment')
                    .empty()
                    .append('<option value="">All</option>');
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

                        $("#opt_payment").append(option);
                    }
                }
            }
        });
    }
</script>
@endif

@endsection