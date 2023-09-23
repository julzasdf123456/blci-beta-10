@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-8">
                    <h4>{{ $user->name }}'s Schedule</h4>
                </div>
                <div class="col-sm-4">
                    <a class="btn btn-primary float-right"
                       href="{{ route('readingSchedules.update-schedule', [$user->id]) }}">
                        Create New Schedule
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="col-lg-4 col-md-5">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <span class="card-title">Upcoming Reading Scheds</span>
                </div>
                <div class="card-body table-responsive px-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <th>Billing Month</th>
                            <th>Area</th>
                            <th>Day</th>
                            <th>Reading Date</th>
                        </thead>
                        <tbody>
                            @foreach ($readingSchedules as $item)
                                <tr>
                                    <td>{{ date('F Y', strtotime($item->ServicePeriod)) }}</td>
                                    <td>{{ $item->AreaCode }}</td>
                                    <td>{{ $item->GroupCode }}</td>
                                    <td>{{ date('F d, Y', strtotime($item->ScheduledDate)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-7">
            <div class="card card-primary">
                <div class="card-body p-0">
                    <div id="calendar"></div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        var scheds = [];

        $(document).ready(function() {
            // QUERY SCHEDS
            $.ajax({
                url : '/reading_schedules/get-latest-schedule',
                type : 'GET',
                data : {
                    id : "{{ $user->id }}"
                },
                success : function(res) {
                    $.each(res, function(index, element) {
                        var obj = {}
                        var schedDate = moment(res[index]['ScheduledDate'], moment.defaultFormat).toDate();
                        obj['title'] = 'Area: ' + res[index]['AreaCode'] + ' | Day ' + res[index]['GroupCode'];
                        obj['start'] = schedDate;

                        if (res[index]['Status'] == 'Downloaded') {                            
                            obj['backgroundColor'] = '#ff8a65';
                            obj['borderColor'] = '#ff8a65';
                        } else {
                            obj['backgroundColor'] = '#66bb6a';
                            obj['borderColor'] = '#66bb6a';
                        }
                        

                        var urlShow = "{{ route('readingSchedules.edit', ['rsId']) }}"
                        urlShow = urlShow.replace("rsId", res[index]['id'])
                        obj['url'] = urlShow

                        obj['allDay'] = true;
                        scheds.push(obj)
                    })

                            /* initialize the calendar
                    -----------------------------------------------------------------*/
                    //Date for the calendar events (dummy data)
                    var date = new Date()
                    var d    = date.getDate(),
                        m    = date.getMonth(),
                        y    = date.getFullYear()

                    var Calendar = FullCalendar.Calendar;

                    var calendarEl = document.getElementById('calendar');
                
                    var calendar = new Calendar(calendarEl, {
                        headerToolbar: {
                            left  : 'prev,next today',
                            center: 'title',
                            right : 'dayGridMonth,timeGridWeek,timeGridDay'
                        },
                        themeSystem: 'bootstrap',
                        events : scheds,
                        //     {
                        //         title          : 'Click for Google',
                        //         start          : new Date(y, m, 28),
                        //         end            : new Date(y, m, 29),
                        //         url            : 'https://www.google.com/',
                        //         backgroundColor: '#3c8dbc', //Primary (light-blue)
                        //         borderColor    : '#3c8dbc' //Primary (light-blue)
                        //     }
                        editable  : true,
                    });

                    calendar.render();
                },
                error : function(err) {
                    alert('An error occurred while trying to query the schedules')
                }
            })

            

        })
    </script>
@endpush

