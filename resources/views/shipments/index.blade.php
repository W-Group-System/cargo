@extends('layouts.header')
@section('content')
{{-- @php
    dd(get_defined_vars());
@endphp --}}
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
                                    <th>Warehouse</th>
                                    <th>Posting Date</th>
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
                <span id="headerBuyersCode"></span>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="d-flex mb-2 align-items-center">
                <label class="fw-bold" style="width: 180px; margin-right: 5px;">Overall shipping status:</label>
                <span id="deliveryStatusDesc"></span>
            </div>
        </div>
    </div>
    <hr>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active"
                    id="home-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#shipmentTab"
                    type="button"
                    role="tab">
                Shipment
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link"
                    id="profile-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#cargoTab"
                    type="button"
                    role="tab">
                Cargo
            </button>
        </li>
    </ul>

    <div class="tab-content mt-3" id="myTabContent">
        <div class="tab-pane fade show active" id="shipmentTab" role="tabpanel">
            <div class="row g-4">
                <div class="col-12 col-lg-6">
                    <label class="fw-bold" style="width: 100%; margin-right: 5px;">Shipment Tracking</label>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Delivery status:</label>
                        <div class="col-sm-8">
                            <select name="deliveryStatus" id="deliveryStatus" class="form-control">
                                <option value="">- Delivery Status -</option>
                                @foreach ($deliveryStatus as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Track points:</label>
                        <div class="col-sm-8">
                            <select name="trackPoints" id="trackPoints" class="form-control">
                                
                            </select>
                        </div>
                    </div>
                    <label class="fw-bold" style="width: 100%; margin-right: 5px;">Shipment Overview</label>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Client Reference #:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="clientRefNo" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Invoice Number:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="invoiceNo" id="invoiceNo">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">BDE/Account Holder</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="accHolder" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Mode:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="mode" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">INCO Terms:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="incoTerms" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">CBW Doc Status:</label>
                        <div class="col-sm-8">
                            <select name="cbwDocStatus" id="cbwDocStatus" class="form-control">
                                <option value="">- CBW Doc Status -</option>
                                <option value="Ongoing">Ongoing</option>
                                <option value="Approved">Approved</option>
                                <option value="N/A">N/A</option>
                            </select>
                        </div>
                    </div>
                    <label class="fw-bold" style="width: 100%; margin-right: 5px;">Cargo details</label>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Type of Pallets:</label>
                        <div class="col-sm-8">
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="palletType" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Cargo Readiness Date:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="cargoReadinessDate" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Posting Date:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="postingDate" readonly>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <label class="fw-bold" style="width: 100%; margin-right: 5px;">Shipping & Destination Information</label>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Current Location:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="currentLocation" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Port of Destination:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="destinationPort" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Country of Destination:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="destinationCountry" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Regions:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="regions" id="regions">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Shipping Line / Forwarder:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="shippingLine" id="shippingLine">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">ED Number / BL Number:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="blNumber" id="blNumber">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Container Number:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="containerNumber" id="containerNumber">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Courier Tracking:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="courierTracking" id="courierTracking">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">ETD Origin:</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" name="etdOrigin" id="etdOrigin">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">ATD Origin:</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" name="atdOrigin" id="atdOrigin">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">ETA Destination:</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" name="etaDestination" id="etaDestination">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">ATA Destination:</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" name="ataDestination" id="ataDestination">
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Initial Transit Time:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="initialTransitTime" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Actual Transit Time:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="actualTransitTime" readonly>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Delivery Date:</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="deliveryDate" id="deliveryDate">
                        </div>
                    </div>
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Date Docs. Completed:</label>
                        <div class="col-sm-8">
                            <input type="datetime-local" class="form-control" name="dateDocsCompleted" id="dateDocsCompleted">
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
        </div>

        <div class="tab-pane fade" id="cargoTab" role="tabpanel">
            <div class="container-fluid">
                <div class="row g-4">
                    <!-- Left Panel -->
                    <div class="col-12 col-lg-5">
                        <div class="d-flex flex-column gap-3 h-100">
                            <!-- Selected SO -->
                            <div class="card border-dark rounded-0 flex-grow-1">
                                <div class="card-header bg-secondary text-white rounded-0 py-1 px-3">
                                    Selected SO #
                                </div>
                                <div class="card-body bg-light py-2 px-2">
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
                            </div>
                        </div>
                    </div>
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

        let coLoadArray = {};
        let sapServerDefault = '';

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
                { data: 'formatted_created_at' },
                { data: 'CardCode' },
                { data: 'CardName'},
                { data: 'SapServer'},
                { data: 'cargo_posting_date'},
                {
                    render: function (data, type, row) {
                        return `<button type="button" class="btn btn-primary btn-update" id="btnCargoUpdate"
                            data-id="${row.id}" data-cardcode="${row.CardCode}" data-sapserver="${row.SapServer}">
                            <i class="bi bi-pencil"></i>
                        </button>`
                    }
                }
            ],
            rowCallback : function(row,data,DisplayIndex){
                $(row).find('.btn-update').unbind('click').on('click',function(){
                    let button = $(this);
                    let shipmentId = $(this).data('id');
                    let buyersCode = $(this).data('cardcode');
                    let sapServer = $(this).data('sapserver');

                    $.ajax({
                        url: "{{ route('shipment.list') }}",
                        type: 'GET',
                        data: {
                            page: 1,
                            limit: 1,
                            id: shipmentId,
                            buyersCode: buyersCode,
                            sapServer: sapServer
                        },
                        beforeSend: function(){
                            button.prop('disabled',true).html('<span class="spinner-border spinner-border-sm" role="status"></span>');
                        },
                        success: function (resp) {
                            $('#headerBuyersCode').text(resp.data[0].CardCode);
                            $('#deliveryStatusDesc').text(resp.data[0].shipment_status?.description??"-");
                            $('#deliveryStatus').val(resp.data[0].shipment_details?.delivery_status[0]?.code??"");
                            $('#clientRefNo').val(resp.data[0].CardCode??"");
                            $('#invoiceNo').val(resp.data[0].shipment_details?.invoice_number??"");
                            $('#cbwDocStatus').val(resp.data[0].shipment_details?.cbw_doc_status??"");
                            $('#currentLocation').val(resp.data[0].SapServer??"");
                            $('#regions').val(resp.data[0].shipment_details?.region??"");
                            $('#shippingLine').val(resp.data[0].shipment_details?.shipping_line??"");
                            $('#blNumber').val(resp.data[0].shipment_details?.ed_bl_number??"");
                            $('#containerNumber').val(resp.data[0].shipment_details?.container_number??"");
                            $('#courierTracking').val(resp.data[0].shipment_details?.courier_tracking??"");
                            $('#etdOrigin').val(resp.data[0].shipment_details?.etd_origin??"");
                            $('#atdOrigin').val(resp.data[0].shipment_details?.atd_origin??"");
                            $('#etaDestination').val(resp.data[0].shipment_details?.eta_destination??"");
                            $('#ataDestination').val(resp.data[0].shipment_details?.ata_destination??"");
                            $('#initialTransitTime').val(ComputeTransitTime($('#etaDestination').val(),$('#etdOrigin').val()));
                            $('#actualTransitTime').val(ComputeTransitTime($('#ataDestination').val(),$('#atdOrigin').val()));
                            $('#deliveryDate').val(resp.data[0].shipment_details?.delivery_date??"");
                            $('#dateDocsCompleted').val(resp.data[0].shipment_details?.date_docs_completed??"");
                            $('#remarks').val(resp.data[0].shipment_details?.remarks??"");
                            $('#postingDate').val(resp.data[0].cargo_posting_date??"");
                            
                            GetCargoTabContent(resp.data[0].CardCode,resp.data[0].SapServer, function(success){
                                if (success) {
                                    $('#trackPoints').val(resp.data[0].shipment_details?.track_points??"");
                                    $('#updateStatusModal').modal('show');
                                }
                            });
                        },
                        error: function (xhr) {
                            Swal.fire('Error',xhr.responseJSON?.message || 'Error','error');
                        }
                        ,
                        complete: function(){
                            button.prop('disabled',false).html('<i class="bi bi-pencil"></i>');
                        }
                    });
                });
            }
        });

        $('#etaDestination,#etdOrigin').on('change', function () {
            const dateOne = new Date($('#etaDestination').val());
            const dateTwo = new Date($('#etdOrigin').val());
            $('#initialTransitTime').val(ComputeTransitTime(dateOne,dateTwo));
        });

        $('#ataDestination,#atdOrigin').on('change', function () {
            const dateOne = new Date($('#ataDestination').val());
            const dateTwo = new Date($('#atdOrigin').val());
            $('#actualTransitTime').val(ComputeTransitTime(dateOne,dateTwo));
        });

         $(this).on('click', '#selectedSOList .so-link, #coLoadDetails .so-link', function(e) {
            e.preventDefault();
            let soNo = $(this).data('so');
            let buyersCode = $(this).data('buyerscode');
            let sapServer = $(this).data('sapserver');
            GetCargoDetails(soNo,buyersCode,sapServer);
        });

        function GetCargoTabContent(cardCode,sapServer, callback){
            $.ajax({
                type: "GET",
                url: "{{ route('cargo.details') }}",
                data: {
                    page : 1,
                    limit : 100,
                    buyersCode : cardCode,
                    sapServer : sapServer
                },
                success: function (response) {
                    sapServerDefault = response.data[0].sap_server;
                    let firstSoNo = response.data[0].DocNum;
                    response.data.forEach(element => {
                        $('#selectedSOList').append(
                            '<li><a href="#" class="so-link" data-sapServer="'+element.sap_server+'" data-so="'+element.DocNum+'" data-buyerscode="'+element.CardCode+'"><u>' + element.DocNum + '</u></a></li>'
                        );
                    });
                    GetCargoDetails(firstSoNo,cardCode,sapServer,true);
                    $('#status').val(response.status);
                    $.each(Object.entries(response.coloads), function(index, item) {
                        let key = item[0];
                        let value = item[1];
                        coLoadArray[key]=value;
                    });
                    LoadCoLoadList(coLoadArray);
                    callback(true);
                },
                error: function (xhr) {
                    Swal.fire('Error',xhr.responseJSON?.message || 'Error','error');
                    callback(false);
                }
            });
        };

        function ReloadDataTable() {
            orderTable.ajax.reload(null, true);
        }

        function ComputeTransitTime(dateValOne,dateValTwo){
            const dateOne = new Date(dateValOne);
            const dateTwo = new Date(dateValTwo);

            if (!isNaN(dateOne) && !isNaN(dateTwo)) {
                const diffMs = dateOne - dateTwo;

                const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diffMs % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diffMs % (1000 * 60 * 60)) / (1000 * 60));

                return `${days}d ${hours}h ${minutes}m`;
            } else {
                return "";
            }
        }

        function GetCargoDetails(soNo,cardCode,sapServer,loadTrackinPoints = false){
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
                success: function (response) {
                    let soNo = response.data[0].DocNum;
                    let packaging = response.data[0].U_Packaging;
                    let label = response.data[0].U_Label;
                    let dateCreated = response.data[0].DocDate;

                    let orderItemList = response.data[0].items;
                    let html = `
                        <p style="margin-bottom: 0.25rem;">SO No.: <span style="font-weight: 700;" id="soNo">${soNo}</span></p>
                        <p style="margin-bottom: 0.25rem;">Packing: <span style="font-weight: 700;" id="packaging">${packaging}</span></p>
                        <p style="margin-bottom: 0.25rem;">Label: <span style="font-weight: 700;" id="label">${label}</span></p>
                        <p style="margin-bottom: 0.25rem;">Date created: <span style="font-weight: 700;" id="dateCreated">${dateCreated}</span></p>
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
                    
                    if (loadTrackinPoints) {
                        let trackingPointHtml = `<option value="">- Track Points -</option>`;
                        $.each(Object.entries(response.data['tracking_points']), function(index, item) {
                            trackingPointHtml += `<option value="${item[1]}">${item[1]}</option>`
                        });
                        $('#trackPoints').html(trackingPointHtml);
                        
                        $('#accHolder').val(response.data[0].bde_name['SlpName']);
                        $('#mode').val(response.data[0].U_Modeship);
                        $('#incoTerms').val(response.data[0].IncoTerms);
                        $('#palletType').val(response.data[0].U_Onpallet);
                        $('#destinationPort').val(response.data[0].PortOfDestination);
                        $('#destinationCountry').val(response.data[0].PortOfDestination);
                    }
                }
            });
        }

        function LoadCoLoadList(coLoadArray){
            let coLoadHtml = '';
            $.each(coLoadArray, function(buyersCode, soList) {
                coLoadHtml += 
                    `<li>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="fw-bold">${buyersCode}</div>
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

        $('#updateStatusModal').on('hide.bs.modal', function () {
            $('#selectedSOList').empty();
            $('#coLoadDetails').empty();
            $('.soInfoContainer').html("");
            $('#updateShipmentForm').trigger('reset');
            $('#trackPoints').html('');
            sapServerDefault = '';
            coLoadArray = {};
        });
    });
</script>
@endsection