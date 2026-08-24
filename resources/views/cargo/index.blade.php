@extends('layouts.header')
@section('content')
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Cargo Management</h4>
                    <form method="GET" id="cargoListForm" class="form-inline mt-4">
                        @csrf
                        <div class="row g-3 align-items-center">
                            <div class="col-auto">
                                <label class="col-form-label">Filter by date sync:</label>
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
                                <select class="form-control" name="status">
                                    <option value="">-Select Status-</option>
                                    @foreach ($CargoStatusArr as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <select class="form-control" name="warehouse">
                                    <option value="">-Select Warehouse-</option>
                                    @foreach (["whi"=>"WHI", "ccc"=>"CCC", "pbi"=>"PBI"] as $key => $value)
                                        <option value="{{ $key }}">
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-search"></i>&nbsp;Search
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="cargoTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Status</th>
                                    <th>Buyers Code</th>
                                    <th>Buyers Name</th>
                                    <th>Availability Date</th>
                                    <th>CBW Doc Status</th>
                                    <th>Warehouse</th>
                                    <th>Action</th>
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
@component('components.modalv2', [
    'modal_id' => 'updateCargoModal',
    'title' => 'Cargo Management',
    'form_id' => 'updateCargoForm',
    'size' => 'modal-xl'
])
<div class="container-fluid">
    <div class="row g-4">
        <!-- Header -->
        <div class="col-12">
            <div class="d-flex align-items-center">
                <label class="fw-bold me-2 mb-0">
                    Buyer Code #:
                </label>
                <span id="soNoHeader">-</span>
            </div>
        </div>
        <!-- Left Panel -->
        <div class="col-12 col-lg-5">
            <div class="d-flex flex-column gap-3 h-100">
                <!-- Selected SO -->
                <div class="card border-dark rounded-0 flex-grow-1">
                    <div class="card-header bg-secondary text-white rounded-0 py-1 px-3">
                        Selected SO #
                    </div>
                    <div class="card-body bg-light py-2 px-2">
                        <form id="addCoLoadForm" method="GET">
                            @csrf
                            <div class="row g-2">
                                <div class="col-12 col-md-10">
                                    <select
                                        class="form-control select2"
                                        id="coLoadBuyersCode"
                                        name="coLoadBuyersCode" required>
                                    </select>
                                </div>
                                <div class="col-12 col-md-2">
                                    @if ($canUpdate)
                                        <button
                                            type="submit"
                                            id="btnAddCoLoad"
                                            class="btn btn-secondary w-100">
                                            +
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="p-4 overflow-auto" style="max-height: 440px;">
                            <ul id="selectedSOList">
                            </ul>
                            <h6 class="mb-2">
                                Co Loads
                            </h6>
                            <div>
                                <ul id="coLoadDetails">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="card border-dark rounded-0">
                <div class="card-header bg-secondary text-white rounded-0 py-1 px-3">
                    Sales Order Information
                </div>
                <div class="card-body bg-light py-2 px-2">
                    <div class="soInfoContainer">
                    </div>
                    <div class="mt-3">
                        <p class="mb-2">To be filled out by the Plant</p>
                        <hr>
                    </div>
                    <form id="updateCargoDetailsForm">
                        @csrf
                        <input
                            type="hidden"
                            name="buyersCode"
                            id="buyersCode">
                        <div class="row g-3">
                            <!-- Left Column -->
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="availabilityDate" class="form-label">
                                        Availability Date
                                    </label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        name="availabilityDate"
                                        id="availabilityDate"
                                        required
                                        >
                                </div>
                                <div class="mb-3">
                                    <label for="pickupDate" class="form-label">
                                        Date Pickup
                                    </label>
                                    <input
                                        type="date"
                                        class="form-control"
                                        name="pickupDate"
                                        id="pickupDate"
                                        >
                                </div>
                            </div>
                            <!-- Right Column -->
                            <div class="col-12 col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        Status
                                    </label>
                                    <select
                                        name="status"
                                        id="status"
                                        class="form-control">
                                        <option value="">-Select Data-</option>
                                        @foreach ($CargoStatusArr as $key => $value)
                                            <option value="{{ $key }}">
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">
                                        CBW Doc Status
                                    </label>
                                    <select class="form-control" name="cbwDocStatus" id="cbwDocStatus">
                                        <option value="">- Status -</option>
                                        @foreach (["Ongoing","Approved","N/A"] as $item)
                                            <option value="{{ $item }}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                @if ($canUpdate)
                                    <button
                                        type="submit"
                                        id="btnProcessCargo"
                                        class="btn btn-secondary w-100">
                                        Process
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endcomponent

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 JS -->
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<script type="text/javascript">
    $(document).ready(function () {
        let canUpdate = "{{ $canUpdate }}";
        let canCreate = "{{ $canCreate }}";
        let canDelete = "{{ $canDelete }}";

        let sapServerDefault = "";
        var start = moment().subtract(29, 'days');
        var end = moment();
        let coLoadArray = {};
        let modalBuyersCode = '';
        let removedColoadsArr = [];

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

        let cargoIds = [];
        $('#cargoListForm').submit(function (e) { 
            e.preventDefault();
            ReloadDataTable();
        });

        $(this).on('click', '#selectedSOList .so-link, #coLoadDetails .so-link', function(e) {
            e.preventDefault();
            let soNo = $(this).data('so');
            let buyersCode = $(this).data('buyerscode');
            let sapServer = $(this).data('sapserver');
            GetCargoDetails(soNo,buyersCode,sapServer);
        });

        $('#updateCargoDetailsForm').submit(function (e) { 
            e.preventDefault();
            
            var form_data = $(this).serializeArray();
            form_data.push({
                name:'coloads',
                value:JSON.stringify(coLoadArray)
            });
            form_data.push({
                name:'removedColoads',
                value:JSON.stringify(removedColoadsArr)
            });
            
            $.ajax({
                type: "POST",
                url: "{{ route('cargo.update') }}",
                data:  form_data,
                // contentType: "application/json",
                beforeSend: function(){
                    $('#btnProcessCargo').prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                },
                success: function (response) {
                    $('#updateCargoModal').modal('hide');
                    Swal.fire('Success',response.message,'success');
                },
                error: function (xhr) {
                    Swal.fire('Error',xhr.responseJSON?.message || 'Error','error');
                },
                complete: function(){
                    $('#btnProcessCargo').prop('disabled',false).text('Process');
                    ReloadDataTable();
                }
            });
        });

        function ReloadDataTable() {
            $('#cargoTable').DataTable().ajax.reload(null, true);
        }

        let orderTable = $('#cargoTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: true,
            ordering: true,
            order: [],
            paging: true,
            autoWidth: false,
            // scrollY: '480px',
            scrollCollapse: false,
            lengthChange: false,
            language: {
                processing: '<div class="spinner-border"></div>',
            },
            columnDefs: [
                {
                    targets: '_all',
                    className: 'text-center align-middle'
                }
            ],
            ajax: function (data, callback) {
                let page = (data.start / data.length) + 1;
                let limit = data.length;
                let orderColumn = data.order?.[0]?.column;
                let orderDir = data.order?.[0]?.dir;
                $.ajax({
                    url: "{{ route('cargoes.list') }}",
                    type: 'GET',
                    data: {
                        page: page,
                        limit: limit,                          
                        start_date:  $('#start_date').val(),
                        end_date:  $('#end_date').val(),
                        status:  $('select[name="status"]').val(),
                        warehouse:  $('select[name="warehouse"]').val(),
                        search: $('#dt-search-0').val(),

                        // DataTables sorting
                        order_column: orderColumn ?? '',
                        order_dir: orderDir ?? ''
                    },
                    success: function (resp) {
                        callback({
                            data: resp.data,            
                            recordsTotal: resp.total,   
                            recordsFiltered: resp.total 
                        });
                    }
                });
            },
            columns: [
                { data: 'cargo_status.description'},
                { data: 'CardCode' },
                { data: 'CardName'},
                { data: 'AvailabilityDate'},
                { data:'cbw_doc_status'},
                {
                    data: 'SapServer',
                    render: function(data, type, row) {
                        return data ? data.toUpperCase() : '';
                    }
                },
                {
                    render: function (data, type, row) {
                        return `<button type="button" class="btn btn-primary btn-update" id="btnCargoUpdate"
                            data-cardcode="${row.CardCode}" data-sapserver="${row.SapServer}">
                            <i class="bi bi-pencil"></i>
                        </button>`
                    }
                }
            ],
            rowCallback : function(row,data,DisplayIndex){
                $(row).find('.btn-update').unbind('click').on('click',function(){
                let button = $(this);
                let cardCode = $(this).attr('data-cardcode');
                let sapServer = $(this).attr('data-sapserver');
                modalBuyersCode = cardCode;
                $('#buyersCode').val(cardCode);
                $.ajax({
                    type: "GET",
                    url: "{{ route('cargo.details') }}",
                    data: {
                        page : 1,
                        limit : 100,
                        buyersCode : cardCode,
                        sapServer : sapServer
                    },
                    beforeSend: function(){
                        button.prop('disabled',true).html('<span class="spinner-border spinner-border-sm" role="status"></span>');
                    },
                    success: function (response) {
                        // console.log(response);
                        sapServerDefault = response.data[0].sap_server;
                        let firstSoNo = response.data[0].DocNum;
                        $('#soNoHeader').text(cardCode);
                        response.data.forEach(element => {
                            $('#selectedSOList').append(
                                '<li><a href="#" class="so-link" data-sapServer="'+element.sap_server+'" data-so="'+element.DocNum+'" data-buyerscode="'+element.CardCode+'"><u>' + element.DocNum + '</u></a></li>'
                            );
                        });
                        GetCargoDetails(firstSoNo,cardCode,sapServer);
                        $('#availabilityDate').val(response.availabilityDate);
                        $('#pickupDate').val(response.pickupDate);
                        $('#status').val(response.status);
                        $('#cbwDocStatus').val(response.cbwDocStatus);
                        $.each(Object.entries(response.coloads), function(index, item) {
                            let key = item[0];
                            let value = item[1];
                            coLoadArray[key]=value;
                        });
                        LoadCoLoadList(coLoadArray);
                        $('#updateCargoModal').modal('show');
                    },
                    error: function (xhr) {
                        Swal.fire('Error',xhr.responseJSON?.message || 'Error','error');
                    },
                    complete: function(){
                        button.prop('disabled',false).html('<i class="bi bi-pencil"></i>');
                    }
                });
            });
            }
        });

        orderTable.on('draw', function() {
            //
        });

        $('#updateCargoModal').on('hide.bs.modal', function () {
            $('#soNoHeader').text('');
            $('#qty').text('');
            $('#selectedSOList').empty();
            $('#coLoadDetails').empty();
            $('#soNo').text("");
            $('.soInfoContainer').html("");
            $('#dateCreated').text("");
            $('#updateCargoDetailsForm').trigger('reset');
            $('#coLoadBuyersCode').empty();
            sapServerDefault = '';
            coLoadArray = {};
            modalBuyersCode = '';
            removedColoadsArr = [];
        });

        $('#coLoadBuyersCode').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Search buyers code',
            dropdownParent: $('#updateCargoModal'),
            allowClear: true,
            ajax: {
                url: "{{ route('orders.list') }}",
                // dataType: 'JSON',
                type: 'GET',
                delay: 250,
                data: function (params) {
                    // console.log(params); 
                    return {
                        buyersCode: params.term, // user typing
                        sap_server: sapServerDefault,
                        // position: $('#searchPosition').val(),
                        page: params.page || 1,
                        limit: 5
                    };
                },
                processResults: function (data, params) {
                    // console.log(data);
                    return {
                        results: data.data.map(emp => ({
                            id: emp.BuyersCode,
                            text: emp.BuyersCode + ' - ' + emp.Count + ' - ' + (emp.OrderStatus !== ''?emp.OrderStatus:'Open'),
                            disabled: emp.BuyersCode == modalBuyersCode || emp.OrderStatus == 'Base Load'
                        })),
                        pagination: {
                            more: data.length === 10 // enables infinite scroll
                        }
                    };
                }
            }
        });

        $('#addCoLoadForm').submit(function (e) { 
            e.preventDefault();
            let buyersCode = $('#coLoadBuyersCode').val();
            $.ajax({
                type: "GET",
                url: "{{ route('cargo.details.buyersCode') }}",
                data: {
                    buyersCode: buyersCode,
                    sapServer: sapServerDefault
                },
                success: function (response) {
                    $('#coLoadBuyersCode').empty();
                    $.each(Object.entries(response.data), function(index, item) {
                        let key = item[0];
                        let value = item[1];

                        let indexRemoved = removedColoadsArr.indexOf(key);
                        if (index > -1) {
                            removedColoadsArr.splice(indexRemoved, 1);
                        }
                        coLoadArray[key]=value;
                    });
                    LoadCoLoadList(coLoadArray);
                }
            });
        });

        $(this).on('click','.remove-coload', function () {
            var buyersCode = $(this).data('buyerscode');
            removedColoadsArr.push(buyersCode);
            delete coLoadArray[buyersCode];
            LoadCoLoadList(coLoadArray);
        });

        $('#availabilityDate,#pickupDate').change(function (e) { 
            e.preventDefault();
            let availabilityDate = $('#availabilityDate').val();
            let pickupDate = $('#pickupDate').val();

            if (availabilityDate !== '' && pickupDate == '') {
                $('#status').val('RFP');
            }else if(availabilityDate == '' && pickupDate !== ''){
                $('#status').val('L');
            }else if(availabilityDate == '' && pickupDate == ''){
                $('#status').val('');
            }else{
                $('#status').val('L');
            }
        });

        function LoadCoLoadList(coLoadArray){
            let coLoadHtml = '';
            $.each(coLoadArray, function(buyersCode, soList) {
                coLoadHtml += 
                    `<li>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-bold">${buyersCode}</div>
                        <button type="button" class="btn btn-sm btn-danger remove-coload" data-buyerscode="${buyersCode}">×</button>
                    </div>
                    <ul class="mb-2">`;
                    $.each(soList, function(index, soNo) {
                        coLoadHtml += `<li> <a href="#" class="so-link" data-so="${soNo}" data-buyerscode="${buyersCode}" data-sapserver="${sapServerDefault}">
                                                <u>${soNo}</u>
                                            </a>
                                        </li>`;
                    });
                            
                coLoadHtml += `</ul></li>`;
            });
            $('#coLoadDetails').html(coLoadHtml);
        }

        function GetCargoDetails(soNo,cardCode,sapServer){
            $.ajax({
                type: "GET",
                url: "{{ route('cargo.details.soNo') }}",
                data: {
                    page: 1,
                    limit: 1,
                    buyersCode : cardCode,
                    soNo: soNo,
                    sapServer: sapServer
                },
                dataType: "JSON",
                beforeSend: function(){
                    $('.soInfoContainer').html('<center><div class="spinner-border"></div></center>');
                },
                success: function (response) {
                    let soNo = response.data[0].DocNum;
                    let packaging = response.data[0].U_Packaging;
                    let label = response.data[0].U_Label;
                    let dateCreated = response.data[0].DocDate;
                    let portDestination = response.data[0].PortOfDestination;
                    let incoTerms = response.data[0].IncoTerms;
                    let mode = response.data[0].U_Modeship;

                    let orderItemList = response.data[0].items;
                    let html = `
                        <p style="margin-bottom: 0.25rem;">SO No.: <span style="font-weight: 700;" id="soNo">${soNo}</span></p>
                        <p style="margin-bottom: 0.25rem;">Packing: <span style="font-weight: 700;" id="packaging">${packaging}</span></p>
                        <p style="margin-bottom: 0.25rem;">Label: <span style="font-weight: 700;" id="label">${label}</span></p>
                        <p style="margin-bottom: 0.25rem;">Date created: <span style="font-weight: 700;" id="dateCreated">${dateCreated}</span></p>
                        <p style="margin-bottom: 0.25rem;">Inco terms: <span style="font-weight: 700;" id="dateCreated">${incoTerms}</span></p>
                        <p style="margin-bottom: 0.25rem;">Mode: <span style="font-weight: 700;" id="dateCreated">${mode}</span></p>
                        <p style="margin-bottom: 0.25rem;">Port of destination: <span style="font-weight: 700;" id="dateCreated">${portDestination}</span></p>
                    `;
                    html += `<div class="border rounded p-2 overflow-auto" style="max-height: 150px;">`;
                    orderItemList.forEach(item => {
                        let qty = '-';
                        let itemCode = '-';
                        let description = '-';
                        
                        qty = item.Quantity;
                        itemCode = item.ItemCode;
                        description = item.Dscription;
                    
                        html += `<br>
                                    <p style="margin-bottom: 0.25rem;">Item Code: <span style="font-weight: 700;" id="itemCode">${itemCode}</span></p>
                                    <p style="margin-bottom: 0.25rem;">Description: <span style="font-weight: 700;" id="description">${description}</span></p>
                                    <p style="margin-bottom: 0.25rem;">Quantity: <span style="font-weight: 700;" id="qty">${qty}</span></p>
                                    <p style="margin-bottom: 0.25rem;">Remarks: <span style="font-weight: 700;" id="remarks">-</span></p>`
                                ;
                    });
                    html += '</div>';
                    
                    $('.soInfoContainer').html(html);
                }
            });
        }
    });
</script>
@endsection