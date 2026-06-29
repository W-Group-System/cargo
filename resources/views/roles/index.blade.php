@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Roles</h4>

                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                   <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#saveRoleModal">Create Role</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto ms-auto">
                            <form method="GET" id="roleListForm" class="form-inline mt-4">
                                @csrf
                                <div class="row g-3 align-items-center">
                                    <div class="col-auto">
                                        <input class="form-control" type="text" name="search" id="search" value="{{ request('search') }}">
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
                        <table class="table table-striped table-bordered table-hover" id="cargoTable">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Role Description</th>
                                    <th>Date Created</th>
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
@component('components.modalv2',['modal_id' => 'RoleAccessModal','title' => 'Role Access', 'size' => 'modal-xl'])
    <div class="container-fluid accessTable">
        
    </div>
@endcomponent

@component('components.modal',['modal_id' => 'saveRoleModal','title' => 'Save Role','form_id' => 'saveRoleForm', 'size' => 'modal-lg','canCreate'=>$canCreate])
    <div class="container-fluid">
        <div class="form-group">
            <label for="name">Role Name</label>
            <input type="text" class="form-control" name="roleName" placeholder="Enter Role Name" required>
        </div>
        <div class="form-group">
            <label for="name">Role Description</label>
            <input type="text" class="form-control" name="roleDescription"  placeholder="Enter Role Description" required>
        </div>
    </div>
@endcomponent

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function () {

         $(this).on('submit','#updateAccessForm',function(e){
            e.preventDefault();
            console.log($(this).serializeArray());
            $.ajax({
                type: "POST",
                url: "{{ route('save.role.access') }}",
                data: $(this).serializeArray(),
                success: function (response) {
                    $('#RoleAccessModal').modal('hide');
                    Swal.fire({
                        title: 'Success',
                        text: response.message,
                        icon: 'Success',
                        confirmButtonText: 'Close'
                    });
                }
            });
            
        });
        $('#saveRoleForm').submit(function (e) { 
            e.preventDefault();
            
            $.ajax({
                type: "POST",
                url: "{{ route('save.role') }}",
                data: $(this).serialize(),
                // dataType: "JSON",
                success: function (response) {
                    $('#saveRoleModal').modal('hide');
                    ReloadDataTable();
                },
                error: function (xhr, status, error) {
                    let message = "An unexpected error occurred."; // default
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        message = Object.values(errors).flat().join('\n'); // combine all messages
                    }
                    else if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    else if (xhr.responseText) {
                        message = xhr.responseText;
                    }
                    Swal.fire({
                        title: 'Failed',
                        text: message,
                        icon: 'error',
                        confirmButtonText: 'Close'
                    });
                }
            });
        });

        $('#roleListForm').submit(function (e) { 
            e.preventDefault();
            ReloadDataTable();
        });

        function ReloadDataTable() {
            $('#cargoTable').DataTable().ajax.reload(null, true);
        }

        let orderTable = $('#cargoTable').DataTable({
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
                    url: "{{ route('role.list') }}",
                    type: 'GET',
                    data: {
                        page: page,
                        limit: limit,                          
                        search:  $('#search').val()
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
                { data: 'role_name' },
                { data: 'role_description' },
                { data: 'created_at'},
                {
                    render: function (data, type, row) {
                        return `<button type="button" class="btn btn-primary btn-update" id="btnAccessUpdate"
                            data-id="${row.id}">
                            Update
                        </button>`
                    }
                }
            ],
            rowCallback : function(row,data,DisplayIndex){
                $(row).find('.btn-update').unbind('click').on('click',function(){
                    let id = $(this).attr('data-id');
                    $.ajax({
                        type: "GET",
                        url: "{{ route('role.access.list') }}",
                        data: {
                            id:id,
                            canUpdate:"{{ $canUpdate }}"
                        },
                        success: function (response) {
                            $('.accessTable').html(response);
                            $('#RoleAccessModal').modal('show');
                        }
                    });
                });
            }
        });

        orderTable.on('draw', function() {
            //
        });

        $('#RoleAccessModal').on('hide.bs.modal', function () {
            // $('#updateCargoDetailsForm').trigger('reset');
        });
        $('#saveRoleModal').on('hide.bs.modal', function () {
            $('#saveRoleForm').trigger('reset');
        });

        function GetRoleAccessDetails(id){
            
        }
    });
</script>
@endsection