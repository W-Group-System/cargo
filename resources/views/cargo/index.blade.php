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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editCargoModal">
                                    <i class="bi bi-pen"></i>&nbsp;Update
                                </button>
                                @include('cargo.edit')
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
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
                                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                                        <td>{{ $item->DocNum }}</td>
                                        <td>{{ $item->CardCode }}</td>
                                        <td>{{ $item->BuyerPONo ?? '-' }}</td>
                                        <td>{{ $item->U_Label ?? $item->Label ?? '-' }}</td>
                                        <td>{{ $item->U_Packaging ?? $item->Packaging ?? '-' }}</td>
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