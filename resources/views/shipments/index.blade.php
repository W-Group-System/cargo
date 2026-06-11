@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Shipment Management</h4>
                     <div class="row">
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card border border-primary">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Pending Shipments</h6>
                                            <h6 class="font-extrabold mb-0">0</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stats-icon purple">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck-front-fill" viewBox="0 0 16 16">
                                                    <path d="M3.5 0A2.5 2.5 0 0 0 1 2.5v9c0 .818.393 1.544 1 2v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5V14h6v1.5a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2c.607-.456 1-1.182 1-2v-9A2.5 2.5 0 0 0 12.5 0zM3 3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3.9c0 .625-.562 1.092-1.17.994C10.925 7.747 9.208 7.5 8 7.5s-2.925.247-3.83.394A1.008 1.008 0 0 1 3 6.9zm1 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2m8 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2m-5-2h2a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card border border-primary">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">In Transit</h6>
                                            <h6 class="font-extrabold mb-0">0</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stats-icon blue">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck-front-fill" viewBox="0 0 16 16">
                                                    <path d="M3.5 0A2.5 2.5 0 0 0 1 2.5v9c0 .818.393 1.544 1 2v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5V14h6v1.5a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2c.607-.456 1-1.182 1-2v-9A2.5 2.5 0 0 0 12.5 0zM3 3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3.9c0 .625-.562 1.092-1.17.994C10.925 7.747 9.208 7.5 8 7.5s-2.925.247-3.83.394A1.008 1.008 0 0 1 3 6.9zm1 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2m8 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2m-5-2h2a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card border border-primary">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Shipped</h6>
                                            <h6 class="font-extrabold mb-0">0</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stats-icon green">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck-front-fill" viewBox="0 0 16 16">
                                                    <path d="M3.5 0A2.5 2.5 0 0 0 1 2.5v9c0 .818.393 1.544 1 2v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5V14h6v1.5a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2c.607-.456 1-1.182 1-2v-9A2.5 2.5 0 0 0 12.5 0zM3 3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3.9c0 .625-.562 1.092-1.17.994C10.925 7.747 9.208 7.5 8 7.5s-2.925.247-3.83.394A1.008 1.008 0 0 1 3 6.9zm1 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2m8 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2m-5-2h2a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card border border-primary">
                                <div class="card-body px-3 py-4-5">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="text-muted font-semibold">Irregularities</h6>
                                            <h6 class="font-extrabold mb-0">0</h6>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="stats-icon red">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-truck-front-fill" viewBox="0 0 16 16">
                                                    <path d="M3.5 0A2.5 2.5 0 0 0 1 2.5v9c0 .818.393 1.544 1 2v2a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5V14h6v1.5a.5.5 0 0 0 .5.5h2a.5.5 0 0 0 .5-.5v-2c.607-.456 1-1.182 1-2v-9A2.5 2.5 0 0 0 12.5 0zM3 3a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v3.9c0 .625-.562 1.092-1.17.994C10.925 7.747 9.208 7.5 8 7.5s-2.925.247-3.83.394A1.008 1.008 0 0 1 3 6.9zm1 9a1 1 0 1 1 0-2 1 1 0 0 1 0 2m8 0a1 1 0 1 1 0-2 1 1 0 0 1 0 2m-5-2h2a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <form method="GET" id="shipmentListForm" class="form-inline mt-4">
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
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-search"></i>&nbsp;Search
                                    </button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-bordered table-hover" id="shipmentTable">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Buyers Code</th>
                                    <th>Buyers Name</th>
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
@component('components.modal',['modal_id' => 'updateStatusModal','title' => 'Update shipment information','form_id' => 'updateShipmentForm', 'size' => 'modal-xl'])
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <div class="d-flex mb-2 align-items-center">
                <label class="fw-bold" style="width: 100px; margin-right: 5px;">Buyer's Code:</label>
                <span>25001</span>
            </div>
            <div class="d-flex mb-2 align-items-center">
                <label class="fw-bold" style="width: 120px; margin-right: 5px;">Selected SO #:</label>
                <span><u>5</u></span>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="d-flex mb-2 align-items-center">
                <label class="fw-bold" style="width: 180px; margin-right: 5px;">Overall shipping status:</label>
                <span>Pending</span>
            </div>
        </div>
    </div>
    <hr>
    <div class="row g-4">
        <div class="col-12 col-lg-6">
            <label class="fw-bold" style="width: 100%; margin-right: 5px;">Shipment Tracking</label>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Delivery status:</label>
                <div class="col-sm-8">
                    <select name="deliveryStatus" id="deliveryStatus" class="form-control">
                        <option value="">-Select Data-</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Track points:</label>
                <div class="col-sm-8">
                    <select name="trackPoints" id="trackPoints" class="form-control">
                        <option value="">-Select Data-</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Location</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="location">
                </div>
            </div>
            <label class="fw-bold" style="width: 100%; margin-right: 5px;">Shipment Overview</label>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Client Reference #:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="clientRefNo">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Invoice Number:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="invoiceNo">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">BDE/Account Holder</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="accHolder">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Mode:</label>
                <div class="col-sm-8">
                    <select name="mode" id="mode" class="form-control">
                        <option value="">-Select Data-</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">INCO Terms:</label>
                <div class="col-sm-8">
                    <select name="incoTerms" id="incoTerms" class="form-control">
                        <option value="">-Select Data-</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">CBW Doc Status:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="cbwDocStatus">
                </div>
            </div>
            <label class="fw-bold" style="width: 100%; margin-right: 5px;">Cargo details</label>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Quantity:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="quantity" id="quantity">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Type of Pallets:</label>
                <div class="col-sm-8">
                    <select name="palletType" id="palletType" class="form-control">
                        <option value="">-Select Data-</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Cargo Readyness Date:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="cargoReadinessDate">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Posting Date:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="postingDate">
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <label class="fw-bold" style="width: 100%; margin-right: 5px;">Shipping & Destination Information</label>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Current Location:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="currentLocation">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Port of Destination:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="destinationPort">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Country of Destination:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="destinationCountry">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Regions:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="regions">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Shipping Line / Forwarder:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="forwarder">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">ED Number / BL Number:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="blNumber">
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Container Number:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="containerNumber">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Courier Tracking:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="courierTracking">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">ETD Origin:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="etdOrigin">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">ATD Origin:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="atdOrigin">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">ETA Destination:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="etaDestination">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">ATA Destination:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="ataDestination">
                </div>
            </div>

            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Initial Transit Time:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="initialTransitTime">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Actual Transit Time:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="actualTransitTime">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Delivery Date:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="deliveryDate">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Date Docs. Completed:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="dateDocsCompleted">
                </div>
            </div>
            <div class="row mb-3 align-items-center">
                <label class="col-sm-4 col-form-label">Remarks:</label>
                <div class="col-sm-8">
                    <textarea class="form-control" name="remarks" id="remarks" cols="30" rows="2"></textarea>
                </div>
            </div>
        </div>
    </div>
@endcomponent
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script type="text/javascript">
    $(function () {
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

        $('#shipmentListForm').submit(function (e) { 
            e.preventDefault();
            ReloadDataTable();
        });

        let orderTable = $('#shipmentTable').DataTable({
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
                    url: "{{ route('shipment.list') }}",
                    type: 'GET',
                    data: {
                        page: page,
                        limit: limit,
                        start_date: $('#start_date').val(),
                        end_date: $('#end_date').val()
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
                { data: 'shipment_status.description'},
                { data: 'created_at' },
                { data: 'CardCode' },
                { data: 'CardName'},
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
                    $('#updateStatusModal').modal('show');
                });
            }
        });

        function ReloadDataTable() {
            orderTable.ajax.reload(null, true);
        }
    });
</script>
@endsection