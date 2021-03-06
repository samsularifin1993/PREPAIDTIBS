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
        <div class="btn-toolbar mb-2 mb-md-0">
          <!-- <div class="btn-group mr-2">
            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
          </div> -->
          <div class="input-group">
            <select class="form-control" name="year" id="year">
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019" selected>2019</option>
            </select>
        </div>
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

    function viewChart(dataValue){
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
                    data: dataValue,
                    backgroundColor: 'transparent',
                    borderColor: '#007bff',
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
                        arrayData[i] = parseInt(json[i].sum_price);
                    }
                }
                viewChart(arrayData);
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
                        labelData[i] = json[i].Product;
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
                { data: 'trans_id_merchant',name: 'trans_id_merchant'},
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

        getChart($('#year').find(":selected").val());
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
</script>
@endif

@endsection