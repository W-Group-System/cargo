<div class="modal fade" id="edit_cargo{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="EditCargo " aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class='row'>
                    <div class='col-md-12'>
                        <h5 class="modal-title" id="EditHoldayData">Edit Holiday</h5>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ url('edit-hmo/' . $availment->id) }}" enctype="multipart/form-data" onsubmit="show()">
                @csrf
                <div class="modal-body">
                    <div class="row">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>