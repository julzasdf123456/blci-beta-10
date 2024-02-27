<?php

use App\Models\ServiceConnections;
use Illuminate\Support\Facades\Auth;

?>

@extends('layouts.app')

@section('content')

    <div class="content px-1">
        <div class="row">
            <div class="col-lg-6 offset-lg-3 col-md-12">
                {{-- HEADER DETAILS --}}
                <div class="py-3">
                    <a href="{{ route('serviceConnections.show', [$serviceConnection->id]) }}"><i class="fas fa-arrow-left"></i></a>
                    <h4 style="display: inline; margin-left: 30px;">Upload Files</h4>
                    <br>
                    <span class="text-muted" style="margin-left: 50px;">Upload supporting documents for {{ $serviceConnection->ServiceAccountName }}'s application</span>
                </div>

                <form action="{{ route('serviceConnections.save-uploaded-files') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                    @csrf
                    <input type="hidden" value="{{ $serviceConnection->id }}" name="id">
                    <input type="file" name="files[]" accept=".jpeg,.jpg,.png,.bmp,.webp,.pdf" placeholder="Choose files" multiple>
                    <br>
                    <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-upload ico-tab-mini"></i>Upload</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // LOAD IMAGE
            
        });
    </script>
@endpush
