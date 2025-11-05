<div class="modal fade" id="editUser-{{$user->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" id="form_user" action="{{url('update_user/'.$user->id)}}" onsubmit="show()">
                    @csrf
                    <div class="form-group">
                        <label for="name">Username</label>
                        <input type="text" class="form-control" name="name" placeholder="Enter Name" value="{{$user->name}}" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Email Address</label>
                        <input type="text" class="form-control" name="email" placeholder="Enter Email Address" value="{{$user->email}}" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-select required" name="role" title="Select Role" required>
                            <option value="" disabled selected>Select Role</option>
                            <option value="Administrator" @if($user->role == 'Administrator') selected @endif>Administrator</option>
                            <option value="Planta Personnel" @if($user->role == 'Planta Personnel') selected @endif>Planta Personnel</option>
                            <option value="Regulatory Officer" @if($user->role == 'Regulatory Officer') selected @endif>Regulatory Officer</option>
                            <option value="Sales/ BDE" @if($user->role == 'Sales/ BDE') selected @endif>Sales/ BDE</option>
                            <option value="Logistics" @if($user->role == 'Logistics') selected @endif>Logistics</option>
                        </select>
                    </div>
                    <div class="form-group" id="formStatus" >
                        <label for="name">Status</label>
                        <select class="form-control js-example-basic-single" name="status" style="position: relative !important" title="Select Type" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="Inactive" @if($user->status == 'Inactive') selected @endif>Inactive</option>
                            <option value="Active" @if($user->status == 'Active') selected @endif>Active</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>