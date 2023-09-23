@php
    // GET PREVIOUS MONTHS
    for ($i = 0; $i <= 12; $i++) {
        $months[] = date("Y-m-01", strtotime( date( 'Y-m-01' )." -$i months"));
    }
@endphp

@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-12">
             <h4 style="display: inline; margin-right: 15px;">Inspections Monitor</h4>
             <i class="text-muted">Monitoring poll for all inspection-related data.</i>
         </div>
     </div>
    </div>
</section>

<div class="row">
   {{-- RE INSPECTION SCHEDULER --}}
   <div class="col-lg-12">
      <div class="card shadow-none" style="height: 60vh;">
         <div class="card-header">
            <span class="card-title">
               <i class="fas fa-info-circle ico-tab"></i>Re-Inspection Scheduler
            </span>
            {{-- <select name="InspectorSummary" id="InspectorSummary" class="form-control form-control-sm float-right" style="width: 180px;">
               <option value="All">All</option>
               @foreach ($inspectors as $item)
                  <option value="{{ $item->id }}">{{ $item->name }}</option>
               @endforeach
            </select> --}}
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-sm table-hover table-bordered" id="re-inspection-summary">
               <thead>
                  <th></th>
                  <th>ID</th>
                  <th>Account Name</th>
                  <th>Address</th>
                  <th>Service Applied For</th>
                  <th>Inspection Date</th>
                  <th>Status</th>
                  <th>Inspector</th>
                  <th>Re-Inspection Schedule</th>
                  <th></th>
               </thead>
               <tbody>

               </tbody>
            </table>
         </div>
      </div>
   </div>

   {{-- FOR INSPECTION AND FOR RE INSPECTION LIST REPORT --}}
   <div class="col-lg-12">
      <div class="card shadow-none" style="height: 50vh;">
         <div class="card-header">
            <span class="card-title">
               <i class="fas fa-info-circle ico-tab"></i>For Inspection and For Re-Inspection Summary
            </span>

            {{-- <select name="InspectorSummary" id="InspectorSummary" class="form-control form-control-sm float-right" style="width: 180px;">
               <option value="All">All</option>
               @foreach ($inspectors as $item)
                  <option value="{{ $item->id }}">{{ $item->name }}</option>
               @endforeach
            </select> --}}
            <select name="StatusSummary" id="StatusSummary" class="form-control form-control-sm float-right" style="width: 150px; margin-right: 10px;">
               <option value="Inspection">Inspection</option>
               <option value="Re-Inspection">Re-Inspection</option>
            </select>
         </div>
         <div class="card-body table-responsive p-0">
            <table class="table table-sm table-hover table-bordered" id="inspection-summary">
               <thead>
                  <th></th>
                  <th>ID</th>
                  <th>Account Name</th>
                  <th>Address</th>
                  <th>Service Applied For</th>
                  <th>Date Applied</th>
                  <th>Inspector</th>
                  <th id="sched-table-label">Schedule</th>
                  <th>Status</th>
               </thead>
               <tbody>

               </tbody>
            </table>
         </div>
      </div>
   </div>

   {{-- CALENDAR --}}
   <div class="col-lg-12">
      <div class="card shadow-none">
          <div class="card-header">
              <span class="card-title">
                  <i class="fas fa-info-circle ico-tab"></i>Schedule Calendar
               </span>
               <div class="legend" style="display: inline;">
                  <span style="margin-left: 5%; border-left: 20px solid #30aa5f;">Inspection Schedule</span>
                  <span style="margin-left: 10px;; border-left: 20px solid #e94438;">Re-Inspection Schedule</span>
                  <span style="margin-left: 10px;; border-left: 20px solid #1fa1c9;">Performed Inspection</span>
               </div>
              <div class="card-tools">
                  <select name="Inspector" id="Inspector" class="form-control form-control-sm">
                      @foreach ($inspectors as $item)
                          <option value="{{ $item->id }}">{{ $item->name }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="card-body">
              <div id="calendar" style="height: 400px;"></div>
          </div>
      </div>
   </div>

    {{-- INDEX --}}
    <div class="col-lg-12">
        <div class="card shadow-none">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle ico-tab"></i>Inspection Assignment Summary</span>
                <div class="card-tools">
                  <select name="ServicePeriod" id="ServicePeriod" class="form-control form-control-sm">
                     @for ($i = 0; $i < count($months); $i++)
                         <option value="{{ $months[$i] }}">{{ date('F Y', strtotime($months[$i])) }}</option>
                     @endfor
                 </select>
                </div>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-sm table-hover table-bordered" id="table-summary">
                    <thead>
                        <th class="text-center">Inspector</th>
                        <th class="text-center">Inspections<br>Filed Today</th>
                        <th class="text-center">For Inspection</th>
                        <th class="text-center">Approved</th>
                        <th class="text-center">Total Inspections</th>
                        <th class="text-center">No. of Days</th>
                        <th class="text-center">Average Daily</th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>

{{-- INSPECTIONS MODAL --}}
<div class="modal fade" id="modal-inspections" aria-hidden="true" style="display: none;">
   <div class="modal-dialog modal-xl">
       <div class="modal-content">
           <div class="modal-header" id="inspection-col-head">
               <h4 class="modal-title" id="inspections-title">Inspection Schedules</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">×</span>
               </button>
           </div>
           <div class="modal-body">
               <div id="loader-inspections" class="spinner-border text-info gone" role="status">
                   <span class="sr-only">Loading...</span>
               </div>
               <table class="table table-hover table-sm" id="table-inspections">
                   <thead>
                       <th>ID</th>
                       <th>Service Account Name</th>
                       <th>Address</th>
                       <th>Status</th>
                   </thead>
                   <tbody>
                   </tbody>
               </table>
           </div>
           <div class="modal-footer justify-content-between">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
           </div>
       </div>
   </div>
</div>
@endsection

@push('page_scripts')
    <script>
        var scheds = [];
        
        var Calendar
        var calendarEl
        var calendar

        $(document).ready(function() {
            Calendar = FullCalendar.Calendar
            calendarEl = document.getElementById('calendar')
            getSummary()
            fetchCalendarData()
            getInspectionSummary($('#StatusSummary').val())
            getForReInspection()

            $('#ServicePeriod').on('change', function() {
                getSummary()
            })

            $('#Inspector').on('change', function() {
                fetchCalendarData()
            })

            $('#StatusSummary').on('change', function() {
               getInspectionSummary(this.value)
            })
        })

        function getSummary() {
            $.ajax({
                url : "{{ route('serviceConnections.get-inspection-summary-data') }}",
                type : 'GET',
                data : {
                    ServicePeriod : $('#ServicePeriod').val()
                },
                success : function(res) {
                    $('#table-summary tbody tr').remove()
                    $('#table-summary tbody').append(res)
                },
                error : function(err) {
                    Swal.fire({
                        icon : 'error',
                        title : 'Error fetching inspection summary'
                    })
                }
            })
        }

        function fetchCalendarData() {
            scheds = []
            // QUERY SCHEDS
            $.ajax({
                url : '{{ route("serviceConnections.get-inspection-summary-data-calendar") }}',
                type : 'GET',
                data : {
                    ServicePeriod : $('#ServicePeriod').val(),
                    Inspector : $('#Inspector').val()
                },
                success : function(res) {
                    console.log(res)
                    $.each(res, function(index, element) {
                        var obj = {}
                        var timestamp = moment(res[index]['InspectionSchedule'], 'YYYY-MM-DD')

                        obj['title'] = res[index]['Count']
                        obj['extendedProps'] = {
                           type : res[index]['Type']
                        }
                        if (res[index]['Type'] == 'Inspection') {
                           obj['backgroundColor'] = '#30aa5f';
                           obj['borderColor'] = '#30aa5f';
                        } else if (res[index]['Type'] == 'Accomplished') {
                           obj['backgroundColor'] = '#1fa1c9';
                           obj['borderColor'] = '#1fa1c9';
                        } else {
                           obj['backgroundColor'] = '#e94438';
                           obj['borderColor'] = '#e94438';
                        }
                        
                        obj['start'] = moment(timestamp).format('YYYY-MM-DD');
                        
                        // urlShow = urlShow.replace("rsId", res[index]['id'])
                        // obj['url'] = urlShow

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })

                    //         /* initialize the calendar
                    // -----------------------------------------------------------------*/
                    //Date for the calendar events (dummy data)
                    var date = new Date()
                    var d    = date.getDate(),
                        m    = date.getMonth(),
                        y    = date.getFullYear()

                    if (calendar != null) {
                        calendar.removeAllEvents()
                    }
                
                    calendar = new Calendar(calendarEl, {
                        headerToolbar: {
                            left  : 'prev,next today',
                            center: 'title',
                            right : 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        themeSystem: 'bootstrap',
                        events : scheds,
                        eventOrderStrict : true,
                        editable  : true,
                        height : 560,
                        eventClick: function(info) {
                           // alert('Event: ' + info.event.extendedProps['type']);

                           $('#modal-inspections').modal('show')
                           if (info.event.extendedProps['type'] == 'Inspection') {
                              $('#inspections-title').text('Inspection Sched for ' + moment(info.event.start).format('MMM DD, YYYY'))
                              $('#inspection-col-head').removeClass (function (index, className) {
                                 return (className.match (/\bbg-\S+/g) || []).join(' ');
                              })                              
                              $('#inspection-col-head').addClass('bg-success')
                              getInspectionData('Inspection', moment(info.event.start).format('MMM DD, YYYY'))
                           } else if (info.event.extendedProps['type'] == 'Accomplished') {
                              $('#inspections-title').text('Inspections Performed on ' + moment(info.event.start).format('MMM DD, YYYY'))
                              $('#inspection-col-head').removeClass (function (index, className) {
                                 return (className.match (/\bbg-\S+/g) || []).join(' ');
                              }) 
                              $('#inspection-col-head').addClass('bg-primary')
                              getInspectionData('Accomplished', moment(info.event.start).format('MMM DD, YYYY'))
                           } else {
                              $('#inspections-title').text('Re-Inspection Sched for ' + moment(info.event.start).format('MMM DD, YYYY'))
                              $('#inspection-col-head').removeClass (function (index, className) {
                                 return (className.match (/\bbg-\S+/g) || []).join(' ');
                              }) 
                              $('#inspection-col-head').addClass('bg-danger')
                              getInspectionData('ReInspection', moment(info.event.start).format('MMM DD, YYYY'))
                           }
                           // change the border color just for fun
                           // info.el.style.borderColor = 'red';
                        }
                    });

                    calendar.render();
                },
                error : function(err) {
                    alert('An error occurred while trying to query the schedules')
                }
            })
        }

        function getInspectionData(type, date) {
            $('#table-inspections tbody tr').remove()
            $.ajax({
               url : "{{ route('serviceConnections.get-inspection-data') }}",
               type : "GET",
               data : {
                  Type : type,
                  Schedule : date,
                  Inspector : $('#Inspector').val()
               },
               success : function(res) {
                  $('#table-inspections tbody').append(res)
               },
               error : function(err) {
                  console.log(err)
                  Toast.fire({
                     icon : 'error',
                     text : 'error getting inspection data'
                  })
               }
            })
        }

        function getInspectionSummary(type) {
         if (type == 'Inspection') {
            $('#sched-table-label').text('Inspection Sched')
         } else {
            $('#sched-table-label').text('Re-Inspection Sched')
         }
         $('#inspection-summary tbody tr').remove()
            $.ajax({
               url : "{{ route('serviceConnections.get-inspection-summary') }}",
               type : "GET",
               data : {
                  Type : type,
               },
               success : function(res) {
                  $('#inspection-summary tbody').append(res)
               },
               error : function(err) {
                  Toast.fire({
                     icon : 'error',
                     text : 'error getting inspection summary data'
                  })
                  console.log(err)
               }
            })
        }

        function getForReInspection() {
         $('#re-inspection-summary tbody tr').remove()
            $.ajax({
               url : "{{ route('serviceConnections.get-for-reinspection') }}",
               type : "GET",
               success : function(res) {
                  $('#re-inspection-summary tbody').append(res)
               },
               error : function(err) {
                  Toast.fire({
                     icon : 'error',
                     text : 'error getting re-inspection summary data'
                  })
                  console.log(err)
               }
            })
        }

        function saveSchedule(id) {
            var date = $('#date-' + id).val()

            if (jQuery.isEmptyObject(date)) {
               Toast.fire({
                  icon : 'warning',
                  text : 'please set date first!'
               })
            } else {
               date = moment(date).format('YYYY-MM-DD')
               $.ajax({
                  url : "{{ route('serviceConnections.update-reinspection-schedule') }}",
                  type : 'GET',
                  data : {
                     id : id,
                     Schedule : date,
                  },
                  success : function(res) {
                     $('#' + id).remove()
                     Toast.fire({
                        icon : 'success',
                        text : 're-inspection set!'
                     })
                  },
                  error : function(err) {
                     consoloe.log(err) 
                     Toast.fire({
                        icon : 'error',
                        text : 'error setting re-inspection date!'
                     })
                  }
               })
            }            
        }
    </script>
@endpush