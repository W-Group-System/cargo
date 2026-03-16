@extends('layouts.header')
@section('content')
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
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-search"></i>&nbsp;Search
                                </button>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" id="btnCargoUpdate" data-bs-toggle="modal" data-bs-target="#updateCargoModal" disabled>
                                    <i class="bi bi-pen"></i>&nbsp;Update
                                </button>
                                {{-- @include('cargo.edit') --}}
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-bordered table-hover" id="cargoTable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                                    <th>Date Created</th>
                                    <th>SO No.</th>
                                    <th>Buyer Code</th>
                                    <th>Buyer PO No.</th>
                                    <th>Label</th>
                                    <th>Packaging</th>
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
@component('components.modalv2',['modal_id' => 'updateCargoModal','title' => 'Cargo Management','form_id' => 'updateCargoForm', 'size' => 'modal-xl'])
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <div class="d-flex align-items-center">
                    <label class="fw-bold me-2" style="min-width: 130px;">
                        Buyer Code #:
                    </label>
                    <span id="soNoHeader">-</span>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card border border-dark rounded-0" style="height: 547px;">
                    <div class="card-header bg-secondary text-white rounded-0 py-1 px-3">
                        Selected SO #.
                    </div>
                    <ul id="selectedSOList" class="mb-0">
                    </ul>
                </div>
            </div>
            <div class="col-12 col-lg-9">
                <div class="card border border-dark rounded-0">
                    <div class="card-header bg-secondary text-white rounded-0 py-1 px-3">
                        Sales Order Information
                    </div>
                    <div class="card-body bg-light">
                        <br>
                        <p style="margin-bottom: 0.25rem;">SO No.: <span style="font-weight: 700;" id="soNo">-</span></p>
                        <p style="margin-bottom: 0.25rem;">Packing: <span style="font-weight: 700;" id="packaging">-</span></p>
                        <p style="margin-bottom: 0.25rem;">Label <span style="font-weight: 700;" id="label">-</span></p>
                        <p style="margin-bottom: 0.25rem;">Date created: <span style="font-weight: 700;" id="dateCreated">-</span></p>
                        <p style="margin-bottom: 0.25rem;">Quantity: <span style="font-weight: 700;" id="qty">-</span></p>
                        <p style="margin-bottom: 0.25rem;">Remarks:<br /><span style="font-weight: 700;" id="remarks">-</span></p>
                        <br>
                        <p style="margin-bottom: 0.25rem;">To be  filled out by the Plant</p>
                        <hr>
                        <form action="">
                            <div class="row g-4">
                                <div class="col-12 col-lg-6">
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-sm-4 col-form-label">Availability Date:</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" name="availabilityDate" id="availabilityDate">
                                        </div>
                                    </div>
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-sm-4 col-form-label">Date Pickup:</label>
                                        <div class="col-sm-8">
                                            <input type="date" class="form-control" name="datePickup" id="datePickup">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="row mb-3 align-items-center">
                                        <label class="col-sm-4 col-form-label">Status:</label>
                                        <div class="col-sm-8">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">-Select Data-</option>
                                                <option value="">Ready to pickup</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-secondary form-control" type="submit">Process</button>
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
<script type="text/javascript">
    $(document).ready(function () {
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

        let cargoIds = [];
        $('#cargoListForm').submit(function (e) { 
            e.preventDefault();
            ReloadDataTable();
            $('#checkAll').prop('checked',false);
            $('#btnCargoUpdate').prop('disabled',true);
        });

        $('#checkAll').on('change', function() {
            if ($(this).is(':checked')) {
                $('#cargoTable .cargoCheckbox').each(function () {
                    $(this).prop('checked', true); // check checkbox
                    let soNo = $(this).attr('data-soNo');
                    if (!cargoIds.includes(soNo)) {
                        cargoIds.push(soNo); // add to array if not exists
                    }
                });
                $('#btnCargoUpdate').prop('disabled', false);
            } else {
                $('#cargoTable .cargoCheckbox').each(function () {
                    $(this).prop('checked', false);

                    let soNo = $(this).attr('data-soNo');
                    cargoIds = cargoIds.filter(id => id !== soNo); // remove from array
                });
                $('#btnCargoUpdate').prop('disabled', true);
            }
        });

        $('#cargoTable').on('change', '.cargoCheckbox', function () {
            var soNumber = $(this).attr('data-soNo');

            if ($(this).is(':checked')) {
                $('.cargoCheckbox:checked').each(function () {
                    var soNo = $(this).attr('data-soNo');
                    if (!cargoIds.includes(soNo)) {
                        cargoIds.push(soNo);
                    }
                });
                $('#btnCargoUpdate').prop('disabled',false);
            } else {
                if(cargoIds.includes(soNumber)){
                    var index = cargoIds.indexOf(soNumber);
                    if (index !== -1) {
                        cargoIds.splice(index, 1);
                    }
                }                
                if (cargoIds.length === 0) {                    
                    $('#btnCargoUpdate').prop('disabled',true);
                }else{
                    $('#btnCargoUpdate').prop('disabled',false);
                }                
            }
        });
        

        $('#btnCargoUpdate').on('click', function () {
            if (cargoIds.length > 0) {
                let firstSoNo = cargoIds[0];
                cargoIds.forEach(element => {
                    $('#selectedSOList').append(
                        '<li><a href="#" class="so-link" data-so="'+element+'"><u>' + element + '</u></a></li>'
                    );
                });
                GetCargoDetails(firstSoNo);
            }
        });

        $(this).on('click', '#selectedSOList .so-link', function(e) {
            e.preventDefault();
            let soNo = $(this).data('so');
            GetCargoDetails(soNo);
        });

        $('#updateCargoModal').on('hide.bs.modal', function () {
            $('#selectedSOList').empty();
            $('#soNo').text("");
            $('#packaging').text("");
            $('#label').text("");
            $('#dateCreated').text("");
        });

        function ReloadDataTable() {
            $('#cargoTable').DataTable().ajax.reload(null, true);
        }

        $('#cargoTable').DataTable({
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
                    url: "{{ route('cargoes.list') }}",
                    type: 'GET',
                    data: {
                        page: page,
                        limit: limit,                          
                        start_date:  $('#start_date').val(),
                        end_date:  $('#end_date').val()
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
                { 
                    render: function (data, type, row) {
                        return `<input type="checkbox" class="cargoCheckbox" name="${row.id}" id="${row.id}" data-soNo="${row.DocNum}">`;
                    }
                },
                { data: 'created_at' },
                { data: 'DocNum' },
                { data: 'CardCode'},
                { data: 'CardCode' },
                { data: 'Label'},
                { data: 'Packaging'}
            ],
            rowCallback : function(row,data,DisplayIndex){
            }
        });

        function GetCargoDetails(soNo){
            $.ajax({
                type: "GET",
                url: "{{ route('cargoes.list') }}",
                data: {
                    page: 1,
                    limit: 1,
                    search: soNo
                },
                dataType: "JSON",
                success: function (response) {
                    // console.log(response);
                    $('#soNoHeader').text(response.data[0].DocNum);
                    $('#soNo').text(response.data[0].DocNum);
                    $('#packaging').text(response.data[0].Packaging);
                    $('#label').text(response.data[0].Label);
                    $('#dateCreated').text(response.data[0].created_at);
                }
            });
        }
    });
</script>
@endsection