@extends('layouts.header')

@section('content')
<div class="container text-center py-5">
    <div class="my-5">
        <div class="mb-4">
            <i class="bi bi-tools display-1 text-warning"></i>
        </div>
        <h1 class="fw-bold mb-3">Page Under Ongoing Development</h1>
        <p class="text-muted fs-5 mb-4">
            We are currently working hard on this feature. Please check back later!
        </p>
        <a href="{{ url('/') }}" class="btn btn-primary px-4">
            <i class="bi bi-arrow-left me-2"></i> Return to Dashboard
        </a>
    </div>
</div>
@endsection