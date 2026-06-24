@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Order Management</h4>
                    <small>Sync orders from SAP</small>
                    
                    {{-- Filter & Sync Form --}}
                    <form method="GET" id="loadOrdersForm" class="form-inline mt-4">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label for="sap_server" class="col-form-label">SAP Server:</label>
                            </div>
                            <div class="col-auto">
                                <select id="sap_server" class="form-select required" name="sap_server" required>
                                    <option disabled {{ request('sap_server') ? '' : 'selected' }}>Select SAP Server</option>
                                    <option value="whi" {{ request('sap_server') == 'whi' ? 'selected' : '' }}>WHI-SAP</option>
                                    <option value="pbi" {{ request('sap_server') == 'pbi' ? 'selected' : '' }}>PBI-SAP</option>
                                    <option value="ccc" {{ request('sap_server') == 'ccc' ? 'selected' : '' }}>CCC-SAP</option>
                                </select>
                            </div>

                            <div class="col-auto">
                                <label class="col-form-label">Filter By:</label>
                            </div>
                            <div class="col-auto">
                                <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
                                <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">

                                <div id="reportrange" style="cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <i class="bi bi-calendar"></i>&nbsp;
                                    <span>
                                        @if(request('start_date') && request('end_date'))
                                            {{ request('start_date') }} - {{ request('end_date') }}
                                        @else
                                            Select Date Range
                                        @endif
                                    </span> 
                                    <i class="bi bi-caret-down"></i>
                                </div>
                            </div>

                            <div class="col-auto">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-arrow-repeat"></i>&nbsp;Sync
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-bordered table-hover" id="ordersTable">
                            <thead>
                                <tr>
                                    <th>Date Created</th>
                                    <th>Buyers Code</th>
                                    <th>Buyers Name</th>
                                    <th>SO # Count</th>
                                    <th>Status/Remarks</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <th><input type="date" name="" id="dateCreated" placeholder="Search date" class="form-control form-control-sm column-search"></th>
                                    <th><input type="text" name="" id="buyersCode" placeholder="Search Buyers Code" class="form-control form-control-sm column-search"></th>
                                    <th><input type="text" name="" id="buyersName" placeholder="Search Buyers Name" class="form-control form-control-sm column-search"></th>
                                    <th></th>
                                    <th></th>
                                    <th></th> <!-- for Action column -->
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div> 
                </div> 
            </div> 
        </div>
    </div>
</div> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
$(document).ready(function(){
    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#start_date').val(start.format('YYYY-MM-DD'));
        $('#end_date').val(end.format('YYYY-MM-DD'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    $('#loadOrdersForm').submit(function (e) { 
        e.preventDefault();
        ReloadDataTable();
        
    });
    $('#ordersTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        searching: false,
        ordering: false,
        paging: true,
        autoWidth: false,
        scrollY: '480px',
        scrollCollapse: false,
        lengthChange: false,
        language: {
            processing: '<div class="spinner-border"></div>',
        },
        ajax: function (data, callback) {
            let page = (data.start / data.length) + 1;
            let limit = data.length;

            $.ajax({
                url: "{{ route('orders.list') }}",
                type: 'GET',
                data: {
                    page: page,
                    limit: limit,                          
                    start_date:  $('#start_date').val(),
                    end_date:  $('#end_date').val(),
                    sap_server: $('#sap_server').val(),
                    buyersCode: $('#buyersCode').val(),
                    buyersName: $('#buyersName').val(),
                    dateCreated: $('#dateCreated').val(),
                },
                success: function (resp) {
                    callback({
                        data: resp.data,            
                        recordsTotal: resp.total,   
                        recordsFiltered: resp.total 
                    });
                },
                error: function (xhr) {
                    callback({
                        data: [],
                        recordsTotal: 0,
                        recordsFiltered: 0
                    });
                }
            });
        },
        columns: [
            { data: 'DocDate' },
            { data: 'BuyersCode' },
            { data: 'CardName'},
            { data: 'Count'},
            { data: 'Remarks'},
            {
                render: function (data, type, row) {
                    let button = '';
                    if (row.OrderStatus !== "") {
                        button = `<button type="button" class="btn btn-success btn-process"
                            data-cardcode="${row.BuyersCode}" data-sapserver="${$('#sap_server').val()}" data-cardname="${row.CardName}" data-docdate="${row.DocDate}" disabled>
                            <i class="bi bi-download"></i>
                        </button>`;
                    }else{
                        button = `<button type="button" class="btn btn-success btn-process"
                            data-cardcode="${row.BuyersCode}" data-sapserver="${$('#sap_server').val()}" data-cardname="${row.CardName}" data-docdate="${row.DocDate}">
                            <i class="bi bi-download"></i>
                        </button>`;
                    }
                    return button;
                }
            }
        ],
        rowCallback : function(row,data,DisplayIndex){
            $(row).find('.btn-process').unbind('click').on('click',function(){
                let button = $(this);
                let cardCode = $(this).attr('data-cardcode');
                let sapServer = $(this).attr('data-sapserver');
                let cardName = $(this).attr('data-cardname');
                let docDate = $(this).attr('data-docdate');
                
                $.ajax({
                    type: "POST",
                    url: "{{ route('orders.store') }}",
                    contentType: "application/json",
                    data: JSON.stringify({
                        _token: "{{ csrf_token() }}",
                        cardCode : cardCode,
                        sapServer : sapServer,
                        cardName : cardName,
                        docDate : docDate
                    }),
                    beforeSend: function(){
                        button.prop('disabled',true).html('<span class="spinner-border spinner-border-sm" role="status"></span>');
                    },
                    success: function (response) {
                        $('#ordersTable').DataTable().ajax.reload(null, false);
                        Swal.fire('Success',response.message,'success');
                    },
                    error: function (xhr) {
                        Swal.fire('Error',xhr.responseJSON?.message || 'Error','error');
                    },
                    complete: function(){
                        button.prop('disabled',false).html('<i class="bi bi-download"></i>');
                    }
                });
            });
        }
    });

    $('#dateCreated,#buyersCode,#buyersName').on('change keyup', function () {
        ReloadDataTable();
    });

    function ReloadDataTable() {
        $('#ordersTable').DataTable().ajax.reload(null, true);
    }
});
</script>
@endsection
