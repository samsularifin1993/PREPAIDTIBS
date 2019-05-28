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
</style>
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.css">
<link rel="stylesheet" type="text/css" href="https://pivottable.js.org/dist/pivot.css">
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Pivot Data</h1>
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

      <div id="output"></div>
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

<script>
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

        $('#output table').addClass('table');
    }

    $('#year').on('change', function(){
        if($(this).find(":selected").val() != ''){
            getChart($(this).find(":selected").val());
        }
    });

    function getChart(year){
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type    : 'POST',
            url     : "{{ route('pivot.data.get') }}",
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

    $(document).ready(function() {
        $.noConflict();
        getChart($('#year').find(":selected").val());

        $('main[role="main"]').removeClass('ml-sm-auto');
    });
</script>

@endsection