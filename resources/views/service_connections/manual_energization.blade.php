@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 style="display: inline; margin-right: 15px;">Energized Applications for Manual Updating</h4>
                <i class="text-muted">Energized applications to be updated manually. These applications are not tied with the lineman's app.</i>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
               <div class="card-header">
                  <span class="card-title"><i class="fas fa-check-circle ico-tab"></i>Energized Applications</span>
               </div>
               <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-bordered table-sm">
                     <thead>
                        <th>ID</th>
                        <th>Account Name</th>
                        <th class="text-center">Status</th>
                        <th>Crew Arrival on Site</th>
                        <th>Date/Time Energized</th>
                        <th>Crew/Linemen</th>
                        <td></td>
                     </thead>
                     <tbody>
                        @foreach ($data as $item)
                           <tr id="{{ $item->id }}">
                              <td><a target="_blank" href="{{ route('serviceConnections.show', [$item->id]) }}">{{ $item->id }}</a></td>
                              <td>
                                 <strong>{{ $item->ServiceAccountName }}</strong><br>
                                 <span class="text-muted">{{ ServiceConnections::getAddress($item) }}</span>
                              </td>
                              <td class="text-center">
                                 <span class="badge bg-success">{{ $item->Status }}</span>
                                 @if ($item->MeterId == null)
                                     <br>
                                     <span class="badge bg-danger">No Meter Assigned</span>
                                 @endif
                              </td>
                              <td>
                                 <input type='datetime-local' class='form-control form-control-sm' id='arrived-{{ $item->id }}' placeholder='Set Crew Arrival Date and Time'>
                              </td>
                              <td>
                                 <input type='datetime-local' class='form-control form-control-sm' id='energized-{{ $item->id }}' placeholder='Set Energization Date and Time'>
                              </td>
                              <td>
                                 <select name="crew" id="crew-{{ $item->id }}" class='form-control form-control-sm'>
                                    <option value="">-</option>
                                    @foreach ($crew as $itemx)
                                       <option value="{{ $itemx->id }}" {{ $item->StationCrewAssigned==$itemx->id ? 'selected' : '' }}>{{ $itemx->StationName }}</option>
                                    @endforeach
                                 </select>
                              </td>
                              <td class="text-right">
                                 @if ($item->MeterId == null)
                                     <a class="btn btn-sm btn-warning" href="{{ route('serviceConnectionMtrTrnsfrmrs.create-step-three', [$item->id]) }}"><i class="fas fa-tachometer-alt ico-tab-mini"></i>Assign Meter</a>
                                 @else
                                    <button class="btn btn-sm btn-success" onclick="setSchedule(`{{ $item->id }}`)"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button>
                                 @endif
                                 
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {

        })

        function setSchedule(id) {
            var arrived = $('#arrived-' + id).val()
            var energized = $('#energized-' + id).val()
            var crew = $('#crew-' + id).val()

            if (jQuery.isEmptyObject(energized) | jQuery.isEmptyObject(crew)) {
               Toast.fire({
                  icon : 'warning',
                  text : 'Please set crew and energization date!'
               })
            } else {
               $.ajax({
                  url : "{{ route('serviceConnections.update-energization-status') }}",
                  type : 'POST',
                  data : {
                    _token : "{{ csrf_token() }}",
                    id : id,
                    Status : 'Energized',
                    EnergizationDate : energized,
                    ArrivalDate : arrived,
                    Reason : '',
                    Crew : crew,
                  },
                  success : function(res) {
                     $('#' + id).remove()
                     Toast.fire({
                        icon : 'success',
                        text : 'Application marked as energized!'
                     })
                  },
                  error : function(err) {
                     console.log(err)
                     Swal.fire({
                        icon : 'error',
                        text : 'Error energizing application'
                     })
                  }
               })
            }
        }
    </script>
@endpush