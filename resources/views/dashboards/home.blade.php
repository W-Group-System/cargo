@extends('layouts.header')
@section('content')
<style>
    .clickable-row {
        cursor: pointer;
    },
    .track-header {
        background: #43b39d;
        color: #fff;
        font-weight: bold;
        padding: 15px 20px;
        font-size: 24px;
        text-transform: uppercase;
    }
</style>
<header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
        <i class="bi bi-justify fs-3"></i>
    </a>
</header>

<div class="page-content">
    <section class="row">
        <div class="row mb-4">
    <div class="col-12">
        <h4 class="card-title mb-3">Dashboard Cargo Tracking</h4>

        <form method="GET" id="dashboardFilterForm">
            @csrf

            <input type="hidden" name="start_date" id="start_date" value="{{ request('start_date') }}">
            <input type="hidden" name="end_date" id="end_date" value="{{ request('end_date') }}">

            <div class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="form-label mb-0 fw-semibold">
                        Filter by date sync:
                    </label>
                </div>

                <div class="col-md-4 col-lg-3">
                    <div id="reportrange"
                        class="form-control d-flex justify-content-between align-items-center"
                        style="cursor:pointer;">
                        <span>
                            @if(request('start_date') && request('end_date'))
                                {{ request('start_date') }} - {{ request('end_date') }}
                            @else
                                Select Date Range
                            @endif
                        </span>
                        <i class="bi bi-calendar"></i>
                    </div>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-search"></i>
                        Search
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">PENDING</h6>
                                <h6 class="font-extrabold mb-0" id="pending">0</h6>
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
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">IN-TRANSIT</h6>
                                <h6 class="font-extrabold mb-0" id="inTransit">0</h6>
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
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">DELIVERED</h6>
                                <h6 class="font-extrabold mb-0" id="delivered">0</h6>
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
                <div class="card">
                    <div class="card-body px-3 py-4-5">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-muted font-semibold">IRREGULARITIES</h6>
                                <h6 class="font-extrabold mb-0" id="irregularities">0</h6>
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
        </div>
        <div class="col-12 col-lg-9">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        {{-- <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div> --}}
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="shipmentTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Status</th>
                                            <th>Date Created</th>
                                            <th>Buyers Code</th>
                                            <th>Buyers Name</th>
                                            <th>Warehouse</th>
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
        <div class="col-12 col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-3">Tracking Point</h4>
                </div>
                <div class="card-content pb-4 trackinPointDiv">
                    <h3><center>Select Shipment</center></h3>
                </div>
            </div>
        </div>
    </section>
</div>
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

        LoadShipmentCounts();
        
        $('#dashboardFilterForm').submit(function (e) { 
            e.preventDefault();
            ReloadDataTable();
        });

        let orderTable = $('#shipmentTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            searching: true,
            ordering: false,
            paging: true,
            autoWidth: false,
            // scrollY: '480px',
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
                        end_date: $('#end_date').val(),
                        search: $('#dt-search-0').val()
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
                { data: 'shipmentStatus'},
                { data: 'formatted_created_at' },
                { data: 'CardCode' },
                { data: 'CardName'},
                {
                    data: 'SapServer',
                    render: function(data, type, row) {
                        return data ? data.toUpperCase() : '';
                    }
                },
            ],
            createdRow: function(row, data, dataIndex) {
                $(row).addClass('clickable-row');
            },
            rowCallback : function(row,data,DisplayIndex){
                $(row).on('click', function () {
                    $('.trackinPointDiv').html('<center><span class="spinner-border spinner-border-lg" role="status"></span></center>');
                    $.ajax({
                        type: "GET",
                        url: "{{ route('trackpoints') }}",
                        data: {
                            id:data.shipment_details?.id??""
                        },
                        success: function (response) {
                            $('.trackinPointDiv').html(response);
                        }
                    });
                });
            }
        });

        function ReloadDataTable() {
            orderTable.ajax.reload(null, true);
            LoadShipmentCounts();
        }

        function LoadShipmentCounts(){
            $.ajax({
                type: "GET",
                url: "{{ route('shipment.counts') }}",
                data: {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val()
                },
                success: function (response) {
                    $('#pending').text(response.pending);
                    $('#inTransit').text(response.in_transit);
                    $('#delivered').text(response.delivered);
                    $('#irregularities').text(response.irregularities);
                }
            });
        }
    });
</script>
@endsection
