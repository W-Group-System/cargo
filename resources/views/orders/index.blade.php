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
                    <form method="GET" action="{{ route('orders.index') }}" class="form-inline mt-4">
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
                                {{-- Hidden date inputs --}}
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
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date Created</th>
                                    <th>SO No.</th>
                                    <th>Buyers Code</th>
                                    <th>Buyers Name</th>
                                    <th>Label</th>
                                    <th>Packaging</th>  
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $item)
                                    <tr>
                                        <td>{{ $item->DocDate }}</td>
                                        <td>{{ $item->DocNum }}</td>
                                        <td>{{ $item->CardCode }}</td>
                                        <td>{{ $item->CardName }}</td>
                                        <td>{{ $item->U_Label ?? $item->Label }}</td>
                                        <td>{{ $item->U_Packaging ?? $item->Packaging }}</td>
                                        <td>
                                            <button 
                                                type="button"
                                                class="btn btn-success btn-process"
                                                data-docnum="{{ $item->DocNum }}"
                                                data-cardcode="{{ $item->CardCode }}"
                                                data-cardname="{{ $item->CardName }}"
                                                data-label="{{ $item->U_Label }}"
                                                data-packaging="{{ $item->U_Packaging }}"
                                            >
                                                Process
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No records found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <div class="mt-2 d-flex justify-content-between align-items-center">
                            <div>
                                {!! $data->appends(request()->except('page'))->links() !!}
                            </div>
                            @php
                                $total = $data->total();
                                $currentPage = $data->currentPage();
                                $perPage = $data->perPage();
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" /> -->
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

        cb(start, end); // call it initially to set values
    });

    $(document).ready(function() {
        $('.btn-process').on('click', function() {
            let button = $(this);
            let sapServer = $('#sap_server').val();

            if (!sapServer) {
                Swal.fire('Error', 'Please select an SAP Server before processing.', 'error');
                return;
            }

            $.ajax({
                url: "{{ route('orders.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    sap_server: sapServer,
                    docnum: button.data('docnum'),
                    cardcode: button.data('cardcode'),
                    cardname: button.data('cardname'),
                    label: button.data('label'),
                    packaging: button.data('packaging')
                },
                beforeSend: function() {
                    button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                },
                success: function(response) {
                    Swal.fire('Success', response.message, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 409) {
                        Swal.fire('Warning', xhr.responseJSON.message, 'warning');
                    } else {
                        Swal.fire('Error', 'Something went wrong.', 'error');
                    }
                },
                complete: function() {
                    button.prop('disabled', false).text('Process');
                }
            });
        });
    });
</script>
@endsection