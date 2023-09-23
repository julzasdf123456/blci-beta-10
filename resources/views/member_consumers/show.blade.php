<?php

use App\Models\MemberConsumers;

?>

@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Member Consumers Details</h1>
                </div>
                <div class="col-sm-6">
                    <a class="btn btn-default float-right"
                       href="{{ route('memberConsumers.index') }}">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        <div class="row">
            <div class="col-md-4 col-lg-4">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img id="prof-img" class="profile-user-img img-fluid img-circle" src="" alt="User profile picture">
                        </div>

                        <h3 class="profile-username text-center">
                            {{ MemberConsumers::serializeMemberName($memberConsumers) }} 
                            <a title="Change picture" href="{{ route('memberConsumers.capture-image', [$memberConsumers->ConsumerId]) }}" style="padding-left: 10px;"><i class="fas fa-camera text-success" style="font-size: .8em;"></i></a>
                        </h3>

                        <p class="text-muted text-center">{{ $memberConsumers->ConsumerId }}</p>

                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>First Name</b> <a class="float-right">{{ $memberConsumers->FirstName }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Middle Name</b> <a class="float-right">{{ $memberConsumers->MiddleName }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Last Name</b> <a class="float-right">{{ $memberConsumers->LastName }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Suffix</b> <a class="float-right">{{ $memberConsumers->Suffix }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Membership Type</b> <a class="float-right">{{ $memberConsumers->Type }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Status</b> <a class="float-right">{{ $memberConsumers->ApplicationStatus }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Date Applied</b> <a class="float-right">{{ date('F d, Y', strtotime($memberConsumers->DateApplied)) }}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Date Approved</b> <a class="float-right">{{ $memberConsumers->DateApproved==null ? '' : date('F d, Y', strtotime($memberConsumers->DateApproved)) }}</a>
                            </li>
                        </ul>

                        <a href="{{ route('memberConsumers.edit', [$memberConsumers->ConsumerId]) }}" class="btn btn-link text-info" title="Update"><i class="fas fa-edit"></i></a>
                        @if ($memberConsumers->CivilStatus=='Married' && empty($memberConsumerSpouse)) 
                            <a href="{{ route('memberConsumerSpouses.create', [$memberConsumers->ConsumerId]) }}" class="btn btn-link text-info" title="Add spouse"><i class="fas fa-user-plus"></i></a>
                        @elseif ($memberConsumers->CivilStatus=='Married' && !empty($memberConsumerSpouse))
                            <a href="{{ route('memberConsumerSpouses.edit', [$memberConsumerSpouse->id]) }}" class="btn btn-link text-info" title="Edit spouse"><i class="fas fa-user-edit"></i></a>
                        @endif

                        <a href="{{ route('memberConsumers.print-membership-application', [$memberConsumers->ConsumerId]) }}" class="btn btn-link" title="Print Application Form"><i class="fas fa-print"></i></a>
                        <a href="{{ route('memberConsumers.print-certificate', [$memberConsumers->ConsumerId]) }}" class="btn btn-link text-warning" title="Print Certificate"><i class="fas fa-print"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-8 col-lg-8">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#about" data-toggle="tab">About</a></li>
                            <li class="nav-item"><a class="nav-link" href="#spouse" data-toggle="tab">Spouse</a></li>
                            <li class="nav-item"><a class="nav-link" href="#other" data-toggle="tab">Other</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="about">
                                <strong><i class="fas fa-birthday-cake mr-1"></i> Birthday</strong>

                                <p class="text-muted">
                                {{ $memberConsumers->Birthdate==null ? 'not recorded' : date('F d, Y', strtotime($memberConsumers->Birthdate)) }}
                                </p>

                                <hr>

                                <strong><i class="fas fa-venus-mars mr-1"></i> Gender</strong>

                                <p class="text-muted">{{ $memberConsumers->Gender }}</p>

                                <hr>

                                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                                <p class="text-muted">{{ $memberConsumers->Sitio . ', ' . $memberConsumers->Barangay . ', ' . $memberConsumers->Town }}</p>

                                <hr>

                                <strong><i class="fas fa-phone mr-1"></i> Contact Numbers</strong>

                                <p class="text-muted">{{ $memberConsumers->ContactNumbers }}</p>

                                <hr>

                                <strong><i class="far fa-envelope mr-1"></i> Email</strong>

                                <p class="text-muted">{{ $memberConsumers->EmailAddress }}</p>

                                <hr>

                                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                                <p class="text-muted">{{ $memberConsumers->Notes}}</p>
                            </div>

                            <div class="tab-pane" id="spouse">
                                @if ($memberConsumers->CivilStatus=='Married' && empty($memberConsumerSpouse)) 
                                    <p class="text-center"><i>Spouse data not recorded</i></p>
                                    <a href="{{ route('memberConsumerSpouses.create', [$memberConsumers->ConsumerId]) }}" class="btn btn-link" title="Add spouse">Add Spouse</a>
                                @elseif ($memberConsumers->CivilStatus=='Married' && !empty($memberConsumerSpouse))
                                    <!-- Spouse Record -->
                                    <ul class="list-group list-group-unbordered mb-3">
                                        <li class="list-group-item">
                                            <b>First Name</b> <a class="float-right">{{ $memberConsumerSpouse->FirstName }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Middle Name</b> <a class="float-right">{{ $memberConsumerSpouse->MiddleName }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Last Name</b> <a class="float-right">{{ $memberConsumerSpouse->LastName }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Suffix</b> <a class="float-right">{{ $memberConsumerSpouse->Suffix }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Birthdate</b> <a class="float-right">{{ $memberConsumerSpouse->Birthdate==null ? '' : date('F d, Y', strtotime($memberConsumerSpouse->Birthdate)) }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Contact Numbers</b> <a class="float-right">{{ $memberConsumerSpouse->ContactNumbers }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Email Address</b> <a class="float-right">{{ $memberConsumerSpouse->EmailAddress }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Religion</b> <a class="float-right">{{ $memberConsumerSpouse->Religion }}</a>
                                        </li>
                                        <li class="list-group-item">
                                            <b>Citizenship</b> <a class="float-right">{{ $memberConsumerSpouse->Citizenship }}</a>
                                        </li>
                                    </ul>

                                    <a href="{{ route('memberConsumerSpouses.edit', [$memberConsumerSpouse->id]) }}" class="btn btn-link" title="Edit spouse">Edit Spouse</a>
                                @else
                                    <p class="text-center"><i>Consumer type not applicable for spouse recording</i></p>
                                @endif
                            </div>

                            <div class="tab-pane" id="other">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            // LOAD IMAGE
            $.ajax({
                url : '/member_consumer_images/get-image/' + "{{ $memberConsumers->ConsumerId }}",
                type : 'GET',
                success : function(result) {
                    var data = JSON.parse(result)
                    $('#prof-img').attr('src', data['img'])
                },
                error : function(error) {
                    console.log(error);
                }
            })
        });
    </script>
@endpush
