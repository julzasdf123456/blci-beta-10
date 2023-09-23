@extends('layouts.app')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4>Generate New KWH Sales Report</h4>
            </div>
        </div>
    </div>
</section>

<div class="row">
    {{-- <div class="col-lg-3">
        <div class="card">
            <div class="card-header">
                <span class="card-title">Settings</span>
            </div>
            <div class="card-body">

            </div>
        </div>
    </div> --}}

    <div class="col-lg-8 offset-lg-2">
        {!! Form::open(['route' => 'distributionSystemLosses.store']) !!}
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('distribution_system_losses.fields')
                </div>
            </div>
            <div class="card-footer">
                {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                <a href="{{ route('distributionSystemLosses.index') }}" class="btn btn-default">Cancel</a>
            </div>
        </div>
        {!! Form::close() !!}   
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        $(document).ready(function() {
            getAllTotals()

            $('#CalatravaSubstation').keyup(function() {
                getAllTotals()
            })
            $('#VictoriasSubstation').keyup(function() {
                getAllTotals()
            })
            $('#SagaySubstation').keyup(function() {
                getAllTotals()
            })
            $('#SanCarlosSubstation').keyup(function() {
                getAllTotals()
            })
            $('#EscalanteSubstation').keyup(function() {
                getAllTotals()
            })
            $('#LopezSubstation').keyup(function() {
                getAllTotals()
            })
            $('#CadizSubstation').keyup(function() {
                getAllTotals()
            })
            $('#IpiSubstation').keyup(function() {
                getAllTotals()
            })
            $('#TobosoCalatravaSubstation').keyup(function() {
                getAllTotals()
            })
            $('#VictoriasMillingCompany').keyup(function() {
                getAllTotals()
            })
            $('#SanCarlosBionergy').keyup(function() {
                getAllTotals()
            })
            $('#EnergySales').keyup(function() {
                getAllTotals()
            })
            $('#EnergyAdjustmentRecoveries').keyup(function() {
                getAllTotals()
            })
        })

        function getAllTotals() {
            var totalInputSubs = getNumVal($('#CalatravaSubstation').val()) +
                getNumVal($('#VictoriasSubstation').val()) +
                getNumVal($('#SagaySubstation').val()) +
                getNumVal($('#SanCarlosSubstation').val()) +
                getNumVal($('#EscalanteSubstation').val()) +
                getNumVal($('#LopezSubstation').val()) +
                getNumVal($('#CadizSubstation').val()) +
                getNumVal($('#IpiSubstation').val()) +
                getNumVal($('#TobosoCalatravaSubstation').val())

            var totalInputGenerators = getNumVal($('#VictoriasMillingCompany').val()) +
                getNumVal($('#SanCarlosBionergy').val())

            var totalEnergyOutput = getNumVal($('#EnergySales').val()) +
                getNumVal($('#EnergyAdjustmentRecoveries').val())

            var totalInputAll = totalInputGenerators + totalInputSubs

            var totalSystemLoss = totalInputAll - totalEnergyOutput
            var systemLossPercentage = (totalSystemLoss / totalInputAll) * 100
            
            $('#totalInputDelivered').val(totalInputSubs)
            $('#generators').val(totalInputGenerators)
            $('#TotalEnergyInput').val(totalInputAll)
            $('#TotalEnergyOutput').val(totalEnergyOutput)
            $('#TotalSystemLoss').val(totalSystemLoss.toFixed(2))
            $('#TotalSystemLossPercentage').val(systemLossPercentage.toFixed(2))
        }

        function getNumVal(item) {
            if (parseFloat(item)) {
                if (jQuery.isEmptyObject(item)) {
                    return 0
                } else {
                    return parseFloat(item)
                }
            } else {
                return 0
            }
            
        }
    </script>
@endpush