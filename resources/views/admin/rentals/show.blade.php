@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            @include('admin.rentals.partials.info-card')
        </div>

        {{-- Sidebar Actions --}}
        <div class="col-lg-4">
            @include('admin.rentals.partials.actions')
        </div>
    </div>
</div>

{{-- Modals --}}
@if($rental->status == 'ongoing')
    @include('admin.rentals.partials.return-modal')
@endif
@endsection
