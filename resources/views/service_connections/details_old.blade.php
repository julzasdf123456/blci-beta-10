@php
    use App\Models\ServiceConnectionChecklists;
@endphp
<div class="row">
   {{-- CHECKLISTS --}}
   <div class="col-lg-6 col-md-6">
       <div class="card card-primary card-outline">
           <div class="card-header border-0">
               <h3 class="card-title">Requirements Checklist</h3>
               <div class="card-tools">
                   <a class="btn btn-tool" href="{{ route('serviceConnections.assess-checklists', [$serviceConnections->id]) }}"><i class="fas fa-pen"></i></a>
                   <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>           
               </div>
           </div>

           <div class="card-body">
               @if ($serviceConnectionChecklistsRep == null) 
                   <p class="text-center"><i>No checklist set. Go to settings and add some.</i></p>
               @else
                   @if ($serviceConnectionChecklists == null)
                       <p class="text-center"><i>No checklist recorded.</i></p>
                       <a href="{{ route('serviceConnections.assess-checklists', [$serviceConnections->id]) }}" class="btn btn-sm btn-primary">
                           <lord-icon
                               src="https://cdn.lordicon.com/jvihlqtw.json"
                               trigger="loop"
                               colors="primary:#ffffff,secondary:#ffffff"
                               stroke="100"
                               delay="1500"
                               style="width:28px;height:28px">
                           </lord-icon>
                           Assess Requirements</a>
                   @else
                       <ul class="todo-list ui-sortable" data-widget="todo-list">
                           @foreach ($serviceConnectionChecklistsRep as $item)

                               @if (!in_array($item->id, $serviceConnectionChecklists))
                                   {{-- IF HASN'T COMPLIED --}}
                                   <li class="done">
                                       <span class="handle ui-sortable-handle">
                                       </span>
                                       <div class="icheck-primary d-inline ml-2">
                                           <i class="fas fa-times-circle"></i>
                                       </div>
                                       <span class="text">{{ $item->Checklist }}</span>

                                       <div class="tools">
                                           <a href="{{ route('serviceConnections.assess-checklists', [$serviceConnections->id]) }}"><i class="fas fa-edit"></i></a>
                                       </div>
                                   </li>
                               @else
                                   {{-- IF COMPLIED --}}
                                   @php
                                       // FETCH CHECKLIST RECORD IF THERE'S ALREADY AN EXISTING RECORD
                                       $checkListRecord = ServiceConnectionChecklists::where('ServiceConnectionId', $serviceConnections->id)
                                           ->where('ChecklistId', $item->id)
                                           ->first();
                                   @endphp
                                   <li class="">
                                       <span class="handle ui-sortable-handle">
                                       </span>
                                       <div class="icheck-primary d-inline ml-2">
                                           <i class="fas fa-check text-success"></i>
                                       </div>
                                       <span class="text">
                                           {{ $item->Checklist }}
                                           @if ($checkListRecord->Notes != null)
                                               (<a href="{{ route('serviceConnectionChecklists.download-file', [$serviceConnections->id, $item->Checklist, $checkListRecord->Notes]) }}" target="_blank">{{ $checkListRecord->Notes }}</a>)
                                           @else
                                               
                                           @endif
                                           
                                       </span>
                                   </li>
                               @endif
                           @endforeach
                       </ul>
                   @endif
               @endif
           </div>
       </div>

       <div class="card card-primary card-outline">
           <div class="card-header border-0">
               <h3 class="card-title">Station Crew Assigned</h3>
               <div class="card-tools">
                   <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>           
               </div>
           </div>

           <div class="card-body">
               <table class="table">
                   <tr>
                       <th>Station</th>
                       <td>{{ $serviceConnections->StationName }}</td>
                   </tr>
                   <tr>
                       <th>Leader</th>
                       <td>{{ $serviceConnections->CrewLeader }}</td>
                   </tr>
                   <tr>
                       <th>Members</th>
                       <td>{{ $serviceConnections->Members }}</td>
                   </tr>
               </table>

               @if ($serviceConnections->Status == 'Energized')
                   <div class="position-relative p-3 bg-gray" style="height: 180px">
                       <div class="ribbon-wrapper ribbon-lg">
                           <div class="ribbon bg-success text-lg">
                               ENERGIZED
                           </div>
                       </div>
                       <small>Crew arrived at</small><br>
                       {{ date('F d, Y, h:i:s A', strtotime($serviceConnections->DateTimeLinemenArrived)) }}<br>
                       <hr>
                       <small>Energized at</small><br>
                       {{ date('F d, Y, h:i:s A', strtotime($serviceConnections->DateTimeOfEnergization)) }}<br>
                   </div>
               @endif
               
           </div>
       </div>
   </div>

   {{-- TIMELINE --}}
   <div class="col-lg-6 col-md-6">
       <div class="card card-primary card-outline">
           <div class="card-header border-0">
               <h3 class="card-title">Timeframe</h3>
               <div class="card-tools">
                   <button type="button" class="btn btn-sm" data-card-widget="collapse" title="Collapse"><i class="fas fa-minus"></i></button>           
               </div>
           </div>

           <div class="card-body">
               <div class="timeline timeline-inverse">
                   @if ($timeFrame == null)
                       <p><i>No timeframe recorded</i></p>
                   @else
                       @php
                           $i = 0;
                       @endphp
                       @foreach ($timeFrame as $item)
                           <div class="time-label" style="font-size: .9em !important;">
                               <span class="{{ $i==0 ? 'bg-success' : 'bg-secondary' }}">
                                   {{ $item->Status }}
                               </span>
                           </div>
                           <div>
                           <i class="fas fa-info-circle bg-primary"></i>

                           <div class="timeline-item">
                                   <span class="time"><i class="far fa-clock"></i> {{ date('h:i A', strtotime($item->created_at)) }}</span>

                                   <p class="timeline-header"  style="font-size: .9em !important;"><a href="">{{ date('F d, Y', strtotime($item->created_at)) }}</a> by {{ $item->name }}</p>

                                   @if ($item->Notes != null)
                                       <div class="timeline-body" style="font-size: .9em !important;">
                                           <?= $item->Notes ?>
                                       </div>
                                   @endif
                                   
                               </div>
                           </div>
                           @php
                               $i++;
                           @endphp
                       @endforeach
                   @endif
               </div>
           </div>
       </div>
   </div>
</div>