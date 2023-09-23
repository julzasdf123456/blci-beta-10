@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <span>
                        <h4 style="display: inline; margin-right: 15px;">TODAC</h4>
                        <i class="text-muted">Typhoon Odette Damage Assessment Console</i>
                        <strong style="margin-left: 100px; margin-right: 20px;">LEGEND:</strong>
                        <span style="margin-right: 20px;"><i class="fas fa-info-circle text-info"></i> Undamaged</span>
                        <span style="margin-right: 20px;"><i class="fas fa-exclamation-triangle text-danger"></i> Damaged</span>
                        <span style="margin-right: 20px;"><i class="fas fa-check-circle text-warning"></i> Fixed</span>
                        <span style="margin-right: 20px;"><i class="fas fa-lightbulb text-success"></i> Energized</span>
                    </span>
                </div>
            </div>
        </div>
    </section>  

    <div class="row">
        <div class="col-lg-10">
            <div id="map" style="height: 88vh;"></div>  
        </div>
        <div class="col-lg-2">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <span class="card-title">Display Config</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {!! Form::label('Feeder', 'Feeder') !!}
                        <select name="Feeder" id="Feeder" class="form-control">
                            <option>-- Select --</option>
                            <option value="All">All</option>
                            @foreach ($feeders as $feeder)
                                <option value="{{ $feeder->Feeder }}">{{ $feeder->Feeder }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <span class="card-title">Search</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {!! Form::text('search', null, ['class' => 'form-control','id'=>'search', 'placeholder' => 'Search Pole No']) !!}
                    </div>
                    <button id="searchBtn" class="btn btn-sm btn-primary">Go</button>
                    
                    <div class="divider"></div>

                    <table id="searchTable" class="table table-sm table-hover">
                        <thead></thead>
                        <tbody style="display: block; height: 300px; overflow-y: scroll"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
@endsection

{{-- MODAL FOR UPDATING INSPECTIONS --}}
<div class="modal fade" id="modal-update" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i id="status-icon" class="ico-tab"></i>Update Pole Status</h4>
                
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::label('item-id', 'Object ID') !!}
                    {!! Form::text('Id', null, ['class' => 'form-control','id'=>'item-id', 'readonly' => 'true']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('PoleNumber', 'PoleNumber') !!}
                    {!! Form::text('PoleNumber', null, ['class' => 'form-control','id'=>'PoleNumber', 'readonly' => 'true']) !!}
                </div>

                <div class="form-group">
                    {!! Form::label('Feeder', 'Feeder') !!}
                    {!! Form::text('Feeder', null, ['class' => 'form-control','id'=>'FeederInfo', 'readonly' => 'true']) !!}
                </div>

                <div class="form-group" id="status-div">
                    {!! Form::label('Status', 'Status') !!}
                    <select name="Status" class="form-control" id="Status">
                        <option value="ONGOING">ONGOING</option>
                        <option value="DONE">FIXED</option>
                        <option value="ENERGIZED">ENERGIZED</option>
                    </select>
                </div>

                <div class="form-group">
                    {!! Form::label('Remarks', 'Remarks') !!}
                    {!! Form::textarea('Remarks', null, ['class' => 'form-control','id'=>'Remarks', 'rows' => 3]) !!}
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                {{-- <button type="button" class="btn btn-primary" id="submit">Save changes</button> --}}
                <button id="save" class="btn btn-primary">Save</button>
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
            // style: 'mapbox://styles/mapbox/satellite-v9',
            style : 'mapbox://styles/julzlopez/ckavvlwp029ua1ipexauenqnc',
            center: [124.017820, 9.762118], // starting position [lng, lat]
            zoom: 10 // starting zoom
        });

        var markers = [];

        map.on('load', () => {
            loadObjects()
        })

        // RELOAD MAP ON FEEDRE CHANGE
        $('#Feeder').on('change', function() {
            loadObjects()
        })

        // SEARCH POLE
        $('#searchBtn').on('click', function() {
            if (jQuery.isEmptyObject($('#search').val())) {
                $('#searchTable tbody tr').remove()
            } else {
                $.ajax({
                    url : '/damage_assessments/search-pole',
                    type : 'GET',
                    data : {
                        Search : $('#search').val(),
                    }, 
                    success : function(res) {
                        $('#searchTable tbody tr').remove()
                        $.each(res, function(index, element) {
                            $('#searchTable tbody').append('<tr>' +
                                '<td>' + res[index]['ObjectName'] + '</td>' +
                                '<td><button id="update" onclick="goToPole(' + res[index]['id'] + ')" class="btn btn-sm btn-link" data-toggle="modal" data-target="#modal-update" pole-no="' + res[index]['ObjectName'] + '" feeder="' + res[index]['Feeder'] + '" status="' + res[index]['Status'] + '" remarks="' + res[index]['Notes'] + '" data-id="' + res[index]['id'] + '" svcid="' + res[index]['id'] + '"><i class="fas fa-eye"></i></button></td>' +
                            '</tr>')
                        })
                    },
                    error : function(err) {
                        alert("An error occurred during the search")
                    }
                })
            }
            
        })

        function goToPole(id) {
            $.ajax({
                url : '/damage_assessments/view-pole',
                type : 'GET',
                data : {
                    id : id
                },
                success : function(res) {
                    map.flyTo({
                            center: [parseFloat(res['Longitude']), parseFloat(res['Latitude'])],
                            zoom: 20,
                            bearing: 0,                        
                            speed: 1.8, // make the flying slow
                            curve: 1, // change the speed at which it zooms out                        
                            // easing: (t) => t,                        
                            essential: true
                        });

                    if (res['Feeder'] == $('#Feeder').val()) {

                    } else {
                        // CREATE MARKER
                        const el = document.createElement('div');
                        el.className = 'marker';
                        el.id = res['id'];
                        el.title = res['ObjectName']
                        // el.innerHTML += "<p>" + res['ObjectName'] + "</p>"
                        
                        if (res['Status'] == 'OK') {
                            el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" data-toggle="modal" data-target="#modal-update" pole-no="' + res['ObjectName'] + '" feeder="' + res['Feeder'] + '" status="' + res['Status'] + '" remarks="' + res['Notes'] + '" data-id="' + res['id'] + '" svcid="' + res['id'] + '" style="margin-left: 10px;"> <span id="' + res['id'] + '"><i class="fas fa-info-circle text-info"></i></span> </button>'
                        } else if (res['Status'] == 'ONGOING') {
                            el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" data-toggle="modal" data-target="#modal-update" pole-no="' + res['ObjectName'] + '" feeder="' + res['Feeder'] + '" status="' + res['Status'] + '" remarks="' + res['Notes'] + '" data-id="' + res['id'] + '" svcid="' + res['id'] + '" style="margin-left: 10px;"> <span id="' + res['id'] + '"><i class="fas fa-exclamation-triangle text-danger"></i></span> </button>'
                        } else if (res['Status'] == 'DONE') {
                            el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" data-toggle="modal" data-target="#modal-update" pole-no="' + res['ObjectName'] + '" feeder="' + res['Feeder'] + '" status="' + res['Status'] + '" remarks="' + res['Notes'] + '" data-id="' + res['id'] + '" svcid="' + res['id'] + '" style="margin-left: 10px;"> <span id="' + res['id'] + '"><i class="fas fa-check-circle text-warning"></i></span> </button>'
                        }                        
                        el.style.width = `15px`;
                        el.style.height = `15px`;
                        el.style.borderRadius = '50%';
                        el.style.backgroundSize = '100%';

                        marker = new mapboxgl.Marker(el)
                            .setLngLat([parseFloat(res['Longitude']), parseFloat(res['Latitude'])])
                            .addTo(map);

                        markers.push(marker)
                    }
                    
                },
                error : function (err) {
                    alert("An error occurred during fetching the pole")
                }
            })
        }

        function loadObjects() {
            $.ajax({
                url : '/damage_assessments/get-objects',
                type : 'GET',
                data : {
                    Feeder : $('#Feeder').val(),
                },
                success : function(res) {
                    if (markers.length > 0) {
                        for (x=0; x<markers.length; x++) {
                            markers[x].remove()
                        }
                    }
                    var i = 0;
                    $.each(res, function(index, element) {
                        // Create a DOM element for each marker.
                        const el = document.createElement('div');
                        el.className = 'marker';
                        el.id = res[index]['id'];
                        el.title = res[index]['ObjectName']
                        // el.innerHTML += "<p>" + res[index]['ObjectName'] + "</p>"
                        if (res[index]['Status'] == 'OK') {
                            el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" data-toggle="modal" data-target="#modal-update" pole-no="' + res[index]['ObjectName'] + '" feeder="' + res[index]['Feeder'] + '" status="' + res[index]['Status'] + '" remarks="' + res[index]['Notes'] + '" data-id="' + res[index]['id'] + '" svcid="' + res[index]['id'] + '" style="margin-left: 10px;"> <span id="' + res[index]['id'] + '"><i class="fas fa-info-circle text-info"></i></span> </button>'
                        } else if (res[index]['Status'] == 'ONGOING') {
                            el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" data-toggle="modal" data-target="#modal-update" pole-no="' + res[index]['ObjectName'] + '" feeder="' + res[index]['Feeder'] + '" status="' + res[index]['Status'] + '" remarks="' + res[index]['Notes'] + '" data-id="' + res[index]['id'] + '" svcid="' + res[index]['id'] + '" style="margin-left: 10px;"> <span id="' + res[index]['id'] + '"><i class="fas fa-exclamation-triangle text-danger"></i></span> </button>'
                        } else if (res[index]['Status'] == 'DONE') {
                            el.innerHTML += '<button id="update" class="btn btn-sm" style="margin-left: -10px;" data-toggle="modal" data-target="#modal-update" pole-no="' + res[index]['ObjectName'] + '" feeder="' + res[index]['Feeder'] + '" status="' + res[index]['Status'] + '" remarks="' + res[index]['Notes'] + '" data-id="' + res[index]['id'] + '" svcid="' + res[index]['id'] + '" style="margin-left: 10px;"> <span id="' + res[index]['id'] + '"><i class="fas fa-check-circle text-warning"></i></span> </button>'
                        }  
                        el.style.backgroundColor = `transparent`;                       
                        el.style.width = `15px`;
                        el.style.height = `15px`;
                        el.style.borderRadius = '50%';
                        el.style.backgroundSize = '100%';

                        // el.addEventListener('click', () => {
                        //     alert(this.id)
                        // });

                        marker = new mapboxgl.Marker(el)
                            .setLngLat([parseFloat(res[index]['Longitude']), parseFloat(res[index]['Latitude'])])
                            .addTo(map);

                        markers.push(marker)

                        if (i==0) {
                            map.flyTo({
                            center: [parseFloat(res[index]['Longitude']), parseFloat(res[index]['Latitude'])],
                            zoom: 12,
                            bearing: 0,                        
                            speed: 1.8, // make the flying slow
                            curve: 1, // change the speed at which it zooms out                        
                            // easing: (t) => t,                        
                            essential: true
                        })
                        }

                        i++;
                    })

                },
                error : function(err) {
                    alert('An error occurred while fetching the data objects')
                }
            })
        }

        // MODAL INITIALIZATION
        $('body').on('click', '#update', function() {
            $('#status-icon').removeAttr('class');
            $('#PoleNumber').val($(this).attr('pole-no'));
            $('#FeederInfo').val($(this).attr('feeder'));
            $('#Remarks').val($(this).attr('remarks'));
            $('#item-id').val($(this).attr('svcid'));
            $('#status-icon').attr('class');
            if ($(this).attr('status') == 'OK') {
                $('#status-icon').addClass('ico-tab').addClass('fas').addClass('fa-info-circle').addClass('text-info');
            } else if ($(this).attr('status') == 'ONGOING') {
                $('#status-icon').addClass('ico-tab').addClass('fas').addClass('fa-exclamation-triangle').addClass('text-danger');
            } else {
                $('#status-icon').addClass('ico-tab').addClass('fas').addClass('fa-check-circle').addClass('text-success');
            }
        });

        // SAVE MODAL
        $('#save').on('click', function() {
            $.ajax({
                url : '/damage_assessments/update-ajax',
                type : 'POST',
                data : {
                    _token : "{{ csrf_token() }}",
                    id : $('#item-id').val(),
                    Status : $('#Status').val(),
                    Notes : $('#Remarks').val(),
                }, 
                success : function(res) {
                    $('#modal-update').modal('hide');

                    if (res == 'ok') {
                        $('#' + $('#item-id').val() + ' i').remove();
                        if ($('#Status').val() == 'OK') {
                            $('#' + $('#item-id').val()).append('<i class="fas fa-info-circle text-info"></i>');
                        } else if ($('#Status').val() == 'ONGOING') {
                            $('#' + $('#item-id').val()).append('<i class="fas fa-exclamation-triangle text-danger"></i>');
                        } else {
                            $('#' + $('#item-id').val()).append('<i class="fas fa-check-circle text-success"></i>');
                        }
                        $('#PoleNumber').val("");
                        $('#FeederInfo').val("");
                        $('#Remarks').val("");
                        $('#item-id').val("");
                        $('#Status').val("");
                        // alert('Data updated!')
                    } else {
                        alert(res)
                    }
                },
                error : function(err) {
                    alert('An error occured while updating this data')
                }
            })
        })

    </script>
@endpush

