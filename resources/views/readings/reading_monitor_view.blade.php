@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>Reading Monitoring Console</h4>
                </div>
            </div>
        </div>
    </section>

    <div class="content">
        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="MeterReader">Select Meter Reader</label>
                                <select name="MeterReader" id="MeterReader" class="form-control">
                                    @if (count($meterReaders) > 0)
                                        @foreach ($meterReaders as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="">No Meter Reader Found</option>
                                    @endif
                                </select>
                            </div>

                            <div class="form-group col-lg-3">
                                <label for="Town">Select Town</label>
                                <select name="Town" id="Town" class="form-control">
                                    @foreach ($towns as $item)
                                        <option value="{{ $item->id }}">{{ $item->Town }}</option>
                                    @endforeach
                                </select>
                            </div>
    
                            <div class="form-group col-lg-3">
                                <label for="Day">Select Day</label>
                                <select name="Day" id="Day" class="form-control">
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-sm" id="view-btn"><i class="fas fa-eye ico-tab-mini"></i>View GPS</button>
                        <button class="btn btn-warning btn-sm float-right" id="view-report-btn"><i class="fas fa-list ico-tab-mini"></i>View Report</button>
                    </div>
                </div>

                <div class="card" style="height: 65vh;">
                    <div class="card-header border-0">
                        <span class="card-title">Accounts Read</span>
                        <div class="card-tools">
                            <button class="btn btn-sm btn-default" disabled=true id="update-gps" data-toggle="modal" data-target="#modal-confirm-update-gps">Update GPS LatLong</button>
                            <button class="btn btn-sm btn-default" disabled=true id="re-seq" data-toggle="modal" data-target="#modal-confirm-re-seq">Re-Sequence</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive px-0">
                        <table class="table table-sm table-hover" id="res-table">
                            <thead>
                                <th>Account No.</th>
                                <th>Reading</th>
                                <th>Timestamp</th>
                                <th>Seq.</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 col-md-7">
                <div id="map" style="width: 100%; height: 90vh;"></div>
            </div>
        </div>
    </div>
@endsection

{{-- RE SEQUENCE CONFIRMATION MODAL --}}
<div class="modal fade" id="modal-confirm-re-seq" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Re-Sequence Based on Reading Time</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Re-sequencing will update all the sequence numbers of these accounts according to the chronological order of reading for this period. Do you wish to proceed?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="proceed-resequence">Proceed</button>
            </div>
        </div>
    </div>
</div>

{{-- UPDATE GPS CONFIRMATION MODAL --}}
<div class="modal fade" id="modal-confirm-update-gps" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update GPS Coordinates</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>The GPS coordinates for all the accounts in this reading schedule will be updated accordingly. Do you wish to proceed?</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="proceed-update-gps">Proceed</button>
            </div>
        </div>
    </div>
</div>

@push('page_scripts')
    <script src="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.js"></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v2.5.1/mapbox-gl.css" rel="stylesheet">
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoianVsemxvcGV6IiwiYSI6ImNqZzJ5cWdsMjJid3Ayd2xsaHcwdGhheW8ifQ.BcTcaOXmXNLxdO3wfXaf5A';
            const map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/satellite-v9',
            center: [123.242197, 10.844679], // starting position [lng, lat], 
            zoom: 10 // starting zoom
        });

        map.on('load', () => {
            loadMapAndAccounts()
        })
        

        function loadMapAndAccounts() {
            $.ajax({
                url : "{{ route('readings.get-readings-from-meter-reader') }}",
                type : 'GET',
                data : {
                    ServicePeriod : "{{ $servicePeriod }}",
                    MeterReader : $("#MeterReader").val(),
                    Day : $("#Day").val(),
                    Town : $('#Town').val(),
                },
                success : function(result) {
                    $('#res-table tbody tr').remove();
                    if (jQuery.isEmptyObject(result)) {
                        console.log("No data found")
                    } else {
                        $.each(result, function(index, element) {
                            // ADD TO TABLE
                            $('#res-table tbody').append(addRowToTable(result[index]['AccountNumber'], result[index]['KwhUsed'], result[index]['ReadingTimestamp'], result[index]['SequenceCode']))

                            // ADD TO MAP
                            if (jQuery.isEmptyObject(result[index]['Longitude']) | jQuery.isEmptyObject(result[index]['Latitude'])) {

                            } else {
                                if (index == 0) {
                                    map.flyTo({
                                            center: [parseFloat(result[index]['Longitude']), parseFloat(result[index]['Latitude'])],
                                            zoom: 15,
                                            bearing: 0,
                                            speed: 1, // make the flying slow
                                            curve: 1, // change the speed at which it zooms out
                                            easing: (t) => t,
                                            essential: true
                                        });                                                                      
                                }  

                                const el = document.createElement('div');
                                el.className = 'marker';
                                el.id = result[index]['id'];
                                el.title = result[index]['ServiceAccountName']
                                el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" style="margin-left: 10px;"> <span><i class="fas fa-map-marker-alt text-danger" style="font-size: 1.2em;"></i></span> </button>'
                                el.style.backgroundColor = `transparent`;                       
                                el.style.width = `15px`;
                                el.style.height = `15px`;
                                el.style.borderRadius = '50%';
                                el.style.backgroundSize = '100%';

                                el.addEventListener('click', () => {
                                    Swal.fire({
                                        title : result[index]['ServiceAccountName'],
                                        text : 'Latitude: ' + result[index]['Latitude'] + ', Longitude: ' + result[index]['Longitude'],
                                    })
                                });
                                
                                new mapboxgl.Marker(el)
                                        .setLngLat([parseFloat(result[index]['Longitude']), parseFloat(result[index]['Latitude'])])
                                        .addTo(map);
                            }
                        })
                    }
                },
                error : function(error) {
                    alert("An error occurred while fetching data")
                    console.log(error)
                }
            })
        }

        function addRowToTable(acctNo, kwhUsed, timestamp, sequence) {
            return "<tr>" + 
                    "<td>" + acctNo + "</td>" +
                    "<td>" + kwhUsed + "</td>" +
                    "<td>" + moment(timestamp).format('MMMM DD, Y | h:mm:ss a') + "</td>" +
                    "<td>" + sequence + "</td>" +
                "</tr>"
        }

        // FETCH ACCOUNTS
        $('#view-btn').on('click', function() {
            $('#update-gps').attr('disabled', false)
            $('#re-seq').attr('disabled', false)
            loadMapAndAccounts()
        })

        // RE SEQUENCE
        $('#proceed-resequence').on('click', function() {
            $.ajax({
                url : "{{ route('serviceAccounts.re-sequence-accounts') }}",
                type : 'GET',
                data : {
                    ServicePeriod : "{{ $servicePeriod }}",
                    MeterReader : $("#MeterReader").val(),
                    Day : $("#Day").val(),
                    Town : $('#Town').val(),
                },
                success : function(res) {
                    $('#modal-confirm-re-seq').modal('hide')
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Re-Sequencing Successful',
                        showConfirmButton: false,
                        timer: 1800
                    })
                }, 
                error : function(err) {
                    $('#modal-confirm-re-seq').modal('hide')
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred during re-sequencing',
                    })
                }
            })            
        })

        // UPDATE GPS
        $('#proceed-update-gps').on('click', function() {
            $.ajax({
                url : "{{ route('serviceAccounts.update-gps-coordinates') }}",
                type : 'GET',
                data : {
                    ServicePeriod : "{{ $servicePeriod }}",
                    MeterReader : $("#MeterReader").val(),
                    Day : $("#Day").val(),
                    Town : $('#Town').val(),
                },
                success : function(res) {
                    $('#modal-confirm-update-gps').modal('hide')
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'GPS Coordinates Updated Successfully!',
                        showConfirmButton: false,
                        timer: 1800
                    })
                }, 
                error : function(err) {
                    $('#modal-confirm-update-gps').modal('hide')
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred during the update',
                    })
                }
            })            
        })

        // VIEW FULL REPORT
        $('#view-report-btn').on('click', function() {
            window.location.href  = "{{ url('/readings/view-full-report') }}" + "/{{ $servicePeriod }}/" + $('#MeterReader').val() + "/" + $('#Day').val() + "/" + $('#Town').val()
        })
    </script>
@endpush

