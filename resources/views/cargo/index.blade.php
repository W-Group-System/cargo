@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Cargo Management</h4>
                    <form method="GET" action="{{ route('cargoes.index') }}" class="form-inline mt-4">
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateCargoModal">
                                    <i class="bi bi-pen"></i>&nbsp;Update
                                </button>
                                {{-- @include('cargo.edit') --}}
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-bordered table-hover">
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
                                @forelse ($cargoes as $item)
                                    <tr>
                                        <td><input type="checkbox" name="" id=""></td>
                                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $item->DocNum }}</td>
                                        <td>{{ $item->CardCode }}</td>
                                        <td>{{ $item->BuyerPONo ?? '-' }}</td>
                                        <td>{{ $item->Label ?? $item->Label ?? '-' }}</td>
                                        <td>{{ $item->Packaging ?? $item->Packaging ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">
                                            No records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>


                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <div>
                                {!! $cargoes->appends(request()->except('page'))->links() !!}
                            </div>
                            @php
                                $total = $cargoes->total();
                                $currentPage = $cargoes->currentPage();
                                $perPage = $cargoes->perPage();
                                $from = ($currentPage - 1) * $perPage + 1;
                                $to = min($currentPage * $perPage, $total);
                            @endphp
                            <div>
                                Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                            </div>
                        </div> 
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
                    <span>25001</span>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card border border-dark rounded-0" style="height: 547px;">
                    <div class="card-header bg-secondary text-white rounded-0 py-1 px-3">
                        Selected SO #.
                    </div>
                    <div class="card-body bg-light">
                        <u>250001</u>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-9">
                <div class="card border border-dark rounded-0">
                    <div class="card-header bg-secondary text-white rounded-0 py-1 px-3">
                        Sales Order Information
                    </div>
                    <div class="card-body bg-light">
                        <br>
                        <p style="margin-bottom: 0.25rem;">SO No.: <span style="font-weight: 700;">250001</span></p>
                        <p style="margin-bottom: 0.25rem;">Packing: <span style="font-weight: 700;">Rico Gel</span></p>
                        <p style="margin-bottom: 0.25rem;">Label <span style="font-weight: 700;">Rico Kraft Bag</span></p>
                        <p style="margin-bottom: 0.25rem;">Date created: <span style="font-weight: 700;">November 14, 2025</span></p>
                        <p style="margin-bottom: 0.25rem;">Quantity: <span style="font-weight: 700;">16</span></p>
                        <p style="margin-bottom: 0.25rem;">Remarks:<br /><span style="font-weight: 700;">Other order details and instructions here.</span></p>
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
    });
</script>
@endsection