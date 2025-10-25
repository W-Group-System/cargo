@extends('layouts.header')
@section('content')
<div class="main-panel">
    <div class="content-wrapper">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">User Accounts</h4>

                    <div class="offset-md-9 col-md-3" align="right">
                        <button type="button" class="btn btn-md btn-success" id="addUserBtn" data-bs-toggle="modal" data-bs-target="#formUser">
                            Add User
                        </button>
                    </div>

                    <div class="row mt-4">
                        <!-- Entries dropdown -->
                        <div class="col-lg-6 d-flex align-items-center">
                            <span>Show&nbsp;</span>
                            <form method="GET" class="d-inline-block me-2">
                                @foreach(request()->except('number_of_entries', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <select name="number_of_entries" class="form-select" onchange="this.form.submit()">
                                    <option value="10" {{ (request('number_of_entries') == 10 || !request('number_of_entries')) ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('number_of_entries') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('number_of_entries') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('number_of_entries') == 100 ? 'selected' : '' }}>100</option>
                                </select>
                            </form>
                            <span>&nbsp;Entries</span>
                        </div>

                        <!-- Search form -->
                        <div class="col-lg-6">
                            <form method="GET" class="custom_form mb-3 d-flex justify-content-end">
                                @foreach(request()->except('search', 'page') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach

                                <div class="search d-flex">
                                    <i class="ti ti-search align-self-center me-2"></i>
                                    <input type="text" class="form-control" placeholder="Search User" name="search" value="{{ request('search') }}"> 
                                    <button type="submit" class="btn btn-sm btn-success ms-2">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- User table -->
                    <div class="table-responsive mt-3">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>{{ $user->status }}</td>
                                        <td>
                                            
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $users->links() }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div> 

<div class="modal fade" id="formUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_user" action="{{url('new_user')}}" onsubmit="show()">
                    @csrf
                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Enter Username" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" name="full_name" placeholder="Enter Full Name" required>
                    </div>
                    <div class="form-group" id="formPasword">
                        <label for="name">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
                    </div>
                    <div class="form-group" id="formConfirmPassword">
                        <label for="name">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" placeholder="Enter Password" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Email Address</label>
                        <input type="text" class="form-control" name="email" placeholder="Enter Email Address" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control js-example-basic-single" name="role_id" style="position: relative !important" title="Select Role" required>
                            <option value="" disabled selected>Select Role</option>
                            {{-- @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->department->department_code .' - '. $role->name }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Department</label>
                        <select class="form-control js-example-basic-single" name="department_id" style="position: relative !important" title="Select Company" required>
                            <option value="" disabled selected>Select Department</option>
                            {{-- @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_code.' - '.$department->name }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection