<div class="modal fade" id="{{ isset($modal_id) ? $modal_id:'' }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog {{ isset($size) ? $size:'' }}">
    <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ isset($title) ? $title:'' }}</h1>
          <button type="button" class="btn-close closeBtn" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           {{ $slot }}
        </div>
    </div>
  </div>
</div>