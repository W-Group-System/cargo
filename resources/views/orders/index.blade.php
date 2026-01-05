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
                                @foreach(['Date','SO','Code','Name','Label','Packaging'] as $i => $label)
                                    <th>
                                        <select class="form-select form-select-sm column-select select2"
                                                data-col="{{ $i }}">
                                            <option value="">All {{ $label }}</option>
                                        </select>
                                    </th>
                                @endforeach
                            </thead>
                            <tbody>
                                @forelse($data as $item)
                                    <tr class="main-order-row" data-docentry="{{ $item->DocEntry }}">
                                        <td>{{ $item->DocDate }}</td>
                                        <td>{{ $item->DocNum }}</td>
                                        <td>{{ $item->CardCode }}</td>
                                        <td>{{ $item->CardName }}</td>
                                        <td>{{ $item->U_Label ?? $item->Label ?? '' }}</td>
                                        <td>{{ $item->U_Packaging ?? $item->Packaging ?? '' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success btn-process"
                                                data-docnum="{{ $item->DocNum }}"
                                                data-cardcode="{{ $item->CardCode }}"
                                                data-cardname="{{ $item->CardName }}"
                                                data-label="{{ $item->U_Label ?? $item->Label ?? '' }}"
                                                data-packaging="{{ $item->U_Packaging ?? $item->Packaging ?? '' }}">
                                                Process
                                            </button>
                                        </td>
                                    </tr>

                                    {{-- Items --}}
                                    @if(isset($item->items) && count($item->items) > 0)
                                        @foreach($item->items as $index => $orderItem)
                                            <tr class="order-item-row table-info" hidden>
                                                <td colspan="2"></td>
                                                <td>
                                                    <input type="hidden" class="item-ItemCode" value="{{ $orderItem->ItemCode }}">
                                                </td>
                                                <td>
                                                    <input type="hidden" class="item-Dscription" value="{{ $orderItem->Dscription }}">
                                                </td>
                                                <td></td>
                                                <td class="text-right">
                                                    <strong>{{ number_format($orderItem->Quantity,0) }}</strong>
                                                    <input type="hidden" class="item-Quantity" value="{{ $orderItem->Quantity }}">
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="order-item-row table-warning">
                                            <td colspan="7" class="text-center text-danger">No items found for this Sales Order.</td>
                                        </tr>
                                    @endif
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

    buildDropdowns();
    initSelect2Filters();
    bindFilters();

    function bindFilters() {
        $('.column-input').on('keyup', applyFilters);
        $('.column-select').on('change change.select2', applyFilters);
    }

    function initSelect2Filters() {
        $('.column-select').each(function () {
            let $select = $(this);

            // Prevent double init
            if ($select.hasClass('select2-hidden-accessible')) {
                return;
            }

            let placeholder = $select.find('option:first').text();

            $select.select2({
                placeholder: placeholder,
                allowClear: true,
                width: '100%',
                minimumResultsForSearch: 8, // hide search if few options
                dropdownAutoWidth: true
            });
        });
    }

    function buildDropdowns() {
        $('.column-select').each(function () {
            let col = $(this).data('col');
            let select = $(this);
            let values = new Set();

            $('.main-order-row').each(function () {
                let text = $(this).find('td').eq(col).text().trim();
                if (text) values.add(text);
            });

            [...values].sort().forEach(v => {
                select.append(`<option value="${v}">${v}</option>`);
            });
        });
    }

    function applyFilters() {
        let inputFilters = {};
        let selectFilters = {};

        $('.column-input').each(function () {
            let val = $(this).val().toLowerCase();
            if (val) inputFilters[$(this).data('col')] = val;
        });

        $('.column-select').each(function () {
            let val = $(this).val();
            if (val) selectFilters[$(this).data('col')] = val;
        });

        $('.main-order-row').each(function () {
            let row = $(this);
            let itemRows = row.nextUntil('.main-order-row');
            let show = true;

            // Text search (partial match)
            $.each(inputFilters, function (col, val) {
                let cellText = row.find('td').eq(col).text().toLowerCase();
                if (!cellText.includes(val)) {
                    show = false;
                    return false;
                }
            });

            // Dropdown (exact match)
            if (show) {
                $.each(selectFilters, function (col, val) {
                    let cellText = row.find('td').eq(col).text().trim();
                    if (cellText !== val) {
                        show = false;
                        return false;
                    }
                });
            }

            if (show) {
                row.show();
                itemRows.show();
            } else {
                row.hide();
                itemRows.hide();
            }
        });

        showNoResults();
    }

    function showNoResults() {
        let visible = $('.main-order-row:visible').length;

        if (visible === 0) {
            if ($('#no-results').length === 0) {
                $('tbody').append(`
                    <tr id="no-results">
                        <td colspan="7" class="text-center text-muted">
                            No matching records found
                        </td>
                    </tr>
                `);
            }
        } else {
            $('#no-results').remove();
        }
    }

    $(document).ready(function(){
        $('.btn-process').on('click', function(){
            let button = $(this);
            let mainRow = button.closest('tr');

            let itemRows = mainRow.nextUntil('.main-order-row');

            let sapServer = $('#sap_server').val();
            // let row = button.closest('tr');
            let docnum = button.data('docnum');
            let cardcode = button.data('cardcode');
            let cardname = button.data('cardname');
            let label = button.data('label');
            let packaging = button.data('packaging');

            if(!sapServer){
                Swal.fire('Error','Please select SAP Server.','error');
                return;
            }

            // initialize items array
            let items = [];

            // Collect items under this order
            // row.nextUntil('.main-order-row').each(function(){
            itemRows.each(function () {
                let ItemCode = $(this).find('.item-ItemCode').val();
                let Dscription = $(this).find('.item-Dscription').val();
                let Quantity = parseInt($(this).find('.item-Quantity').val());
                if(ItemCode && Dscription && !isNaN(Quantity)){
                    items.push({ItemCode,Dscription,Quantity});
                }
            });

            if(items.length === 0){
                Swal.fire('Error','No items found for this order.','error');
                return;
            }

            let removedMainRow = mainRow.detach();
            let removedItemRows = itemRows.detach();
            
            // AJAX request
            $.ajax({
                url: "{{ route('orders.store') }}",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    _token: "{{ csrf_token() }}",
                    sap_server: sapServer,
                    docnum: docnum.toString(), // <-- force string
                    cardcode: cardcode,
                    cardname: cardname,
                    label: label,
                    packaging: packaging,
                    items: items
                }),
                beforeSend: function(){
                    button.prop('disabled',true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
                },
                success: function(res){
                    Swal.fire('Success',res.message,'success');
                    updateRowCount();
                },
                error: function(xhr){
                    removedMainRow.insertBefore(mainRow);
                    removedItemRows.insertAfter(removedMainRow);
                    Swal.fire('Error',xhr.responseJSON?.message || 'Error','error');
                },
                complete: function(){
                    button.prop('disabled',false).text('Process');
                }
            });
        });
        function updateRowCount() {
            let rows = $('.main-order-row').length;
            if (rows === 0) {
                $('tbody').html(
                    '<tr><td colspan="7" class="text-center text-muted">No records found.</td></tr>'
                );
            }
        }
    });
</script>
@endsection
