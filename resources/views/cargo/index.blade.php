@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Cargo Management</h4>
                    <small>Sync orders from SAP</small>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span>Show</span>
                            <select class="form-select form-select-sm" style="width: auto;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="col-auto">
                            <label class="col-form-label">Filter By:</label>
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

                    </div>
                    <div class="table-responsive mt-4">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date Created</th>
                                    <th>SO No.</th>
                                    <th>Buyers Code</th>
                                    <th>Label</th>
                                    <th>Packaging</th>  
                                    <th>Status</th>
                                    <th>Company</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cargoes as $item)
                                    <tr>
                                        <td>{{ $item->created_at }}</td>
                                        <td>{{ $item->DocNum }}</td>
                                        <td>{{ $item->CardCode }}</td>
                                        <td>{{ $item->U_Label ?? $item->Label ?? '' }}</td>
                                        <td>{{ $item->U_Packaging ?? $item->Packaging ?? '' }}</td>
                                        <td>Status</td>
                                        <td class="text-uppercase">{{ $item->sap_server }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success">
                                                Update
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No records found.</td>
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
@endsection