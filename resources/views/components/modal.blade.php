<div class="modal fade" id="{{ isset($modal_id) ? $modal_id:'' }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog {{ isset($size) ? $size:'' }}">
    <div class="modal-content">
      <form id="{{ isset($form_id) ? $form_id:'' }}" method="POST" enctype="{{ isset($enctype) ? $enctype:"" }}">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">{{ isset($title) ? $title:'' }}</h1>
          <button type="button" class="btn-close closeBtn" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
           {{ $slot }}
        </div>
        <div class="modal-footer modalFooter">
          <div class="spinner-border spinner-border-sm text-dark loading" role="status" hidden>
              <span class="visually-hidden">Loading...</span>
          </div>
          <button type="button" class="btn btn-secondary closeBtn" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success submitBtn">
            {{ isset($saveButtonName) ? $saveButtonName:'Save' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</div>