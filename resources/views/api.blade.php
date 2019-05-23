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
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <table id="datatables" class="table table-hover">
                <thead>
                    <tr>
                        <th>HTTP method</th>
                        <th>Description</th>
                        <th>URL Path End Point</th>
                        <th>Parameter</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>POST</td>
                        <td>Auth Login</td>
                        <td>http://domain/api/auth/login</td>
                        <td>
                            <span class="badge badge-light">nik</span>
                            <span class="badge badge-light">password</span>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Auth Logout</td>
                        <td>http://domain/api/auth/logout</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Profile</td>
                        <td>http://domain/api/auth/me</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Refresh Token</td>
                        <td>http://domain/api/auth/refresh</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>


                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Channel</td>
                        <td>http://domain/api/channel</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Channel by ID</td>
                        <td>http://domain/api/channel/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>POST</td>
                        <td>Create data Channel</td>
                        <td>http://domain/api/channel</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">name</span>
                            <span class="badge badge-light">description</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PUT</td>
                        <td>Update data Channel</td>
                        <td>http://domain/api/channel/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">name</span>
                            <span class="badge badge-light">description</span>
                        </td>
                    </tr>
                    <tr>
                        <td>DELETE</td>
                        <td>Delete data Channel</td>
                        <td>http://domain/api/channel/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>


                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Payment</td>
                        <td>http://domain/api/payment</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Payment by ID</td>
                        <td>http://domain/api/payment/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>POST</td>
                        <td>Create data Payment</td>
                        <td>http://domain/api/payment</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">type</span>
                            <span class="badge badge-light">description</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PUT</td>
                        <td>Update data Paymet</td>
                        <td>http://domain/api/payment/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">type</span>
                            <span class="badge badge-light">description</span>
                        </td>
                    </tr>
                    <tr>
                        <td>DELETE</td>
                        <td>Delete data Payment</td>
                        <td>http://domain/api/payment/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>

                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Organization</td>
                        <td>http://domain/api/organization</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Organization by ID</td>
                        <td>http://domain/api/organization/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>POST</td>
                        <td>Create data Organization</td>
                        <td>http://domain/api/organization</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">regional</span>
                            <span class="badge badge-light">witel</span>
                            <span class="badge badge-light">datel</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PUT</td>
                        <td>Update data Organization</td>
                        <td>http://domain/api/organization/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">regional</span>
                            <span class="badge badge-light">witel</span>
                            <span class="badge badge-light">datel</span>
                        </td>
                    </tr>
                    <tr>
                        <td>DELETE</td>
                        <td>Delete data Organization</td>
                        <td>http://domain/api/organization/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>

                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Product Family</td>
                        <td>http://domain/api/product_family</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Product Family by ID</td>
                        <td>http://domain/api/product_family/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>POST</td>
                        <td>Create data Product Family</td>
                        <td>http://domain/api/product_family</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">name</span>
                            <span class="badge badge-light">description</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PUT</td>
                        <td>Update data Product Family</td>
                        <td>http://domain/api/product_family/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">name</span>
                            <span class="badge badge-light">description</span>
                        </td>
                    </tr>
                    <tr>
                        <td>DELETE</td>
                        <td>Delete data Product Family</td>
                        <td>http://domain/api/product_family/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Product</td>
                        <td>http://domain/api/product</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Product by ID</td>
                        <td>http://domain/api/product/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>POST</td>
                        <td>Create data Product</td>
                        <td>http://domain/api/product</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">name</span>
                            <span class="badge badge-light">description</span>
                            <span class="badge badge-light">product_family</span>
                        </td>
                    </tr>
                    <tr>
                        <td>PUT</td>
                        <td>Update data Product</td>
                        <td>http://domain/api/product/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">name</span>
                            <span class="badge badge-light">description</span>
                            <span class="badge badge-light">product_family</span>
                        </td>
                    </tr>
                    <tr>
                        <td>DELETE</td>
                        <td>Delete data Product</td>
                        <td>http://domain/api/product/{id}</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>

                    <tr>
                        <td>POST</td>
                        <td>Retrieve data Report All</td>
                        <td>http://domain/api/report_all</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">month</span>
                            <span class="badge badge-light">year</span>
                        </td>
                    </tr>

                    <tr>
                        <td>POST</td>
                        <td>Retrieve data Report Sum By Organization</td>
                        <td>http://domain/api/report_by_org</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">month</span>
                            <span class="badge badge-light">year</span>
                        </td>
                    </tr>

                    <tr>
                        <td>POST</td>
                        <td>Retrieve data Report Sum By Product</td>
                        <td>http://domain/api/report_by_product</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">month</span>
                            <span class="badge badge-light">year</span>
                        </td>
                    </tr>

                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Transaction Success</td>
                        <td>http://domain/api/trx_success</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>GET</td>
                        <td>Retrieve data Transaction Rejected</td>
                        <td>http://domain/api/trx_rejected</td>
                        <td>
                            <span class="badge badge-light">token</span>
                        </td>
                    </tr>
                    <tr>
                        <td>POST</td>
                        <td>Update Transaction Rejected</td>
                        <td>http://domain/api/updated_trx_rejected</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">id</span>
                            <span class="badge badge-light">transidmerchant</span>
                            <span class="badge badge-light">channel</span>
                            <span class="badge badge-light">item_id</span>
                            <span class="badge badge-light">product_family</span>
                            <span class="badge badge-light">product</span>
                            <span class="badge badge-light">nd</span>
                            <span class="badge badge-light">duration</span>
                            <span class="badge badge-light">price</span>
                            <span class="badge badge-light">ppn</span>
                            <span class="badge badge-light">payment_dtm</span>
                            <span class="badge badge-light">request_dtm</span>
                            <span class="badge badge-light">start_dtm</span>
                            <span class="badge badge-light">end_dtm</span>
                            <span class="badge badge-light">treg</span>
                            <span class="badge badge-light">witel</span>
                            <span class="badge badge-light">datel</span>
                            <span class="badge badge-light">payment_type</span>
                            <span class="badge badge-light">prov_status</span>
                        </td>
                    </tr>
                    <tr>
                        <td>POST</td>
                        <td>Reprocess Transaction Rejected</td>
                        <td>http://domain/api/reprocess_rejected</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">id</span>
                        </td>
                    </tr>
                    <tr>
                        <td>POST</td>
                        <td>Reprocess Bulk Transaction Rejected</td>
                        <td>http://domain/api/reprocess_bulk_rejected</td>
                        <td>
                            <span class="badge badge-light">token</span>
                            <span class="badge badge-light">id</span>
                            ex : <i>id = x,y,z (add comma in multiple id)</i>
                        </td>
                    </tr>
                </tbody>
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
</script>
@endif
@endsection