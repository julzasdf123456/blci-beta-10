@php
    use App\Models\ServiceConnections;
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <h4 style="display: inline; margin-right: 15px;">Applications for Energization</h4>
                <i class="text-muted">Paid applications to be scheduled for energization/connection.</i>
            </div>
        </div>
    </div>
</section>

<div class="content px-3">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-none">
               <div class="card-header">
                  <span class="card-title"><i class="fas fa-check-circle ico-tab"></i>Paid Applications for Connection Scheduling</span>
               </div>
               <div class="card-body table-responsive p-0">
                  <table class="table table-hover table-bordered table-sm">
                     <thead>
                        <th>ID</th>
                        <th>Account Name</th>
                        <th class="text-center">Status</th>
                        <th>Inspector</th>
                        <th>Inspection Date</th>
                        <th>Connection Schedule</th>
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
                              <td class="text-center"><span class="badge bg-success">{{ $item->Status }}</span></td>
                              <td>{{ $item->name }}</td>
                              <td>{{ $item->DateOfVerification != null ? date('M d, Y', strtotime($item->DateOfVerification)) : 'n/a' }}</td>
                              <td>
                                 <input type='date' class='form-control form-control-sm' id='date-{{ $item->id }}' placeholder='Set Connection Date' value="{{ $item->ConnectionSchedule != null ? $item->ConnectionSchedule : '' }}">
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
                                 <button class="btn btn-sm btn-success" onclick="setSchedule(`{{ $item->id }}`)"><i class="fas fa-check-circle ico-tab-mini"></i>Save</button>
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
            var sched = $('#date-' + id).val()
            var crew = $('#crew-' + id).val()

            if (jQuery.isEmptyObject(sched) | jQuery.isEmptyObject(crew)) {
               Toast.fire({
                  icon : 'warning',
                  text : 'please set crew and connection schedule'
               })
            } else {
               $.ajax({
                  url : "{{ route('serviceConnections.set-connection-schedule') }}",
                  type : 'GET',
                  data : {
                     id : id,
                     Schedule : sched,
                     Crew : crew,
                  },
                  success : function(res) {
                     // $('#' + id).remove()
                     Toast.fire({
                        icon : 'success',
                        text : 'schedule added!'
                     })
                  },
                  error : function(err) {
                     console.log(err)
                     Toast.fire({
                        icon : 'error',
                        text : 'error adding connection schedule'
                     })
                  }
               })
            }
        }
    </script>
@endpush