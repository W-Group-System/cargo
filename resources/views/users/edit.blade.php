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
                        <select class="form-control" name="role" id="role">
                            <option value="">- Select Role -</option>
                            @foreach ($roles as $key => $value)
                                <option value="{{ $key }}" {{ $user->role == $key?'selected':'' }}>{{ $value }}</option>
                            @endforeach
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