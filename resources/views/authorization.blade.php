@extends('backend.layouts.panel')

@section('js.up')
@endsection

@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
<link href="http://js-grid.com/css/jsgrid.min.css" rel="stylesheet" />
<link href="http://js-grid.com/css/jsgrid-theme.min.css" rel="stylesheet" />

<style>
th, td {
        text-align:left;
        max-width: 500px;
        width: 50px;
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
            <h5>Role Authorization</h5>
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-md-12">
            <div id="jsGrid"></div>
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
<script src="http://js-grid.com/js/jsgrid.min.js"></script>

@if(Auth::guard('user')->check())
<script>
    $(document).ready(function() {
        $.noConflict();
        $(function() {
            $("#jsGrid").jsGrid({
                width: "100%",
                height: "400px",

                filtering: true,
                inserting: (/true/i).test("{{ $permission['i'] }}"),
                editing: (/true/i).test("{{ $permission['u'] }}"),
                editButton: (/true/i).test("{{ $permission['u'] }}"),
                deleteButton: (/true/i).test("{{ $permission['d'] }}"),
                sorting: true,
                paging: true,
                autoload: true,
                pageSize: 10,
                pageButtonCount: 5,
                deleteConfirm: "Do you really want to delete data?",
                controller: {
                    loadData: function(filter){
                        return $.ajax({
                            type: "GET",
                            url: "{{ route('role.get') }}",
                            data: filter
                        });
                    },
                    insertItem: function(item){
                        var method = "&_method=post"
                        var token = "?_token="+"{{ csrf_token() }}";
                        var url = "{{ route('role.store') }}"+token+method;

                        return $.ajax({
                            type: "POST",
                            url: url,
                            data:item,
                            complete: function(data){
                                $("#jsGrid").jsGrid("loadData");
                            }
                        });
                    },
                    updateItem: function(item){
                        var method = "&_method=put"
                        var token = "?_token="+"{{ csrf_token() }}";
                        var url = "{{ route('role.update') }}"+token+method;

                        return $.ajax({
                            type: "PUT",
                            url: url,
                            data: item,
                            complete: function(data){
                                $("#jsGrid").jsGrid("loadData");
                            }
                        });
                    },
                    deleteItem: function(item){
                        var method = "&_method=delete"
                        var token = "?_token="+"{{ csrf_token() }}";
                        var url = "{{ route('role.delete') }}"+token+method;

                        return $.ajax({
                            type: "DELETE",
                            url: url,
                            data: item
                        });
                    },
                },

                fields: [
                    {
                        name: "name", 
                        type: "text",
                        title: "Name",
                        validate: "required",
                        width: 200,
                        sorting: true, filtering: true, sorter: "string",
                    },
                    {
                        name: "role_r", 
                        type: "checkbox",
                        title: "<p><small>Role</small></p><p>R</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "role_i", 
                        type: "checkbox",
                        title: "<p><small>Role</small></p><p>I</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "role_u", 
                        type: "checkbox",
                        title: "<p><small>Role</small></p><p>U</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "role_d", 
                        type: "checkbox",
                        title: "<p><small>Role</small></p><p>D</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "user_r", 
                        type: "checkbox",
                        title: "<p><small>User</small></p><p>R</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "user_i", 
                        type: "checkbox",
                        title: "<p><small>User</small></p><p>I</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "user_u", 
                        type: "checkbox",
                        title: "<p><small>User</small></p><p>U</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "user_d", 
                        type: "checkbox",
                        title: "<p><small>User</small></p><p>D</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "channel_r", 
                        type: "checkbox",
                        title: "<p><small>Channel</small></p><p>R</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "channel_i", 
                        type: "checkbox",
                        title: "<p><small>Channel</small></p><p>I</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "channel_u", 
                        type: "checkbox",
                        title: "<p><small>Channel</small></p><p>U</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "channel_d", 
                        type: "checkbox",
                        title: "<p><small>Channel</small></p><p>D</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "organization_r", 
                        type: "checkbox",
                        title: "<p><small>Organization</small></p><p>R</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "organization_i", 
                        type: "checkbox",
                        title: "<p><small>Organization</small></p><p>I</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "organization_u", 
                        type: "checkbox",
                        title: "<p><small>Organization</small></p><p>U</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "organization_d", 
                        type: "checkbox",
                        title: "<p><small>Organization</small></p><p>D</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "payment_r", 
                        type: "checkbox",
                        title: "<p><small>Payment</small></p><p>R</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "payment_i", 
                        type: "checkbox",
                        title: "<p><small>Payment</small></p><p>I</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "payment_u", 
                        type: "checkbox",
                        title: "<p><small>Payment</small></p><p>U</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "payment_d", 
                        type: "checkbox",
                        title: "<p><small>Payment</small></p><p>D</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "product_family_r", 
                        type: "checkbox",
                        title: "<p><small>Product Family</small></p><p>R</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "product_family_i", 
                        type: "checkbox",
                        title: "<p><small>Product Family</small></p><p>I</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "product_family_u", 
                        type: "checkbox",
                        title: "<p><small>Product Family</small></p><p>U</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "product_family_d", 
                        type: "checkbox",
                        title: "<p><small>Product Family</small></p><p>D</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "product_r", 
                        type: "checkbox",
                        title: "<p><small>Product</small></p><p>R</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "product_i", 
                        type: "checkbox",
                        title: "<p><small>Product</small></p><p>I</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "product_u", 
                        type: "checkbox",
                        title: "<p><small>Product</small></p><p>U</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "product_d", 
                        type: "checkbox",
                        title: "<p><small>Product</small></p><p>D</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "error_r", 
                        type: "checkbox",
                        title: "<p><small>Error</small></p><p>R</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "error_i", 
                        type: "checkbox",
                        title: "<p><small>Error</small></p><p>I</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "error_u", 
                        type: "checkbox",
                        title: "<p><small>Error</small></p><p>U</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "error_d", 
                        type: "checkbox",
                        title: "<p><small>Error</small></p><p>D</p>",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "v_dashboard_admin", 
                        type: "checkbox",
                        title: "D. Admin",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "v_dashboard_revenue", 
                        type: "checkbox",
                        title: "D. Revenue",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "r_trx_success", 
                        type: "checkbox",
                        title: "Trx Success",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "r_trx_reject", 
                        type: "checkbox",
                        title: "Trx Rejected",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        name: "r_revenue", 
                        type: "checkbox",
                        title: "Revenue",
                        itemTemplate: function(value, item) {
                            return $("<input>").attr("type", "checkbox")
                                .attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.indeterminate = true;
                                        item.checked = item.uncheck = false;
                                        $(this).prop("indeterminate", true);
                                    }
                                }).on("change", function() {
                                    if (item.uncheck === true && item.checked === false && item.indeterminate === false) {
                                        item.indeterminate = true;
                                        item.uncheck = item.checked = false;
                                        $(this).prop("indeterminate", true);
                                    } else if (item.uncheck === false && item.checked === false && item.indeterminate === true) {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    } else {
                                        item.uncheck = true;
                                        item.indeterminate = item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                        },
                        editTemplate: function(value, item) {
                            item.indeterminate = false;
                            var $result = jsGrid.fields.checkbox.prototype.editTemplate.call(this, value, item);
                            $result.attr("checked", function() {
                                    if (value === 'false') {
                                        item.uncheck = true;
                                        item.checked = item.indeterminate = false;
                                        $(this).prop("checked", false);
                                    } else if (value === 'true') {
                                        item.checked = true;
                                        item.uncheck = item.indeterminate = false;
                                        $(this).prop("checked", true);
                                    }
                                }).on("change", function() {
                                    
                                    if (item.uncheck === true && item.checked === false) {
                                        item.uncheck = false;
                                        item.checked = true;
                                        $(this).prop("checked", true);
                                    } else if (item.uncheck === false && item.checked === true) {
                                        item.uncheck = true;
                                        item.checked = false;
                                        $(this).prop("checked", false);
                                    }
                            });
                            return $result;
                        },
                        sorting: false, filtering: false
                    },
                    {
                        type: "control"
                    }
                ]
            });
        });
    });
</script>
@endif
@endsection