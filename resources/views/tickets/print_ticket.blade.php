@php
    use App\Models\Tickets;
    use App\Models\TicketsRepository;
@endphp
<style>
    @media print {
        html, body {
            width: 100%;
        }

        @page {
            margin: 10px;
        }

        header {
            display: none;
        }

        .divider {
            width: 100%;
            margin: 10px auto;
            height: 1px;
            background-color: #cdcdcd;
        }

        .center-text {
            text-align: center;
        }

        p {
            padding: 0 !important;
            margin: 0 !important;
        }

        .col-3 {
            width: 25%;
        }

        .col-7 {
            width: 75%;
        }

        .col-5 {
            width: 50%;
        }

        .row {
            width: 100%;
            display: inline;
        }

        table {
            width: 100%;
        }

        table th {
            width: 25%;
            padding: 5px 15px 2px 0px;
        }

        table td {
            border-bottom: 1px solid #878787;
            padding: 5px 15px 2px 0px;
        }

        .check-box {
            padding: 2px 12px;
            margin-right: 5px;
            border: 1px solid #878787;
        }

        .divider-dotted {
            width: 100%;
            margin: 10px auto;
            height: 1px;
            border-bottom: 1px dotted #878787;
        } 
    }  

    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

    .divider-dotted {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        border-bottom: 1px dotted #878787;
    } 

    .center-text {
        text-align: center;
    }

    p {
        padding: 0 !important;
        margin: 0 !important;
    }

    .row {
        width: 100%;
        display: inline;
    }

    .col-3 {
        width: 25%;
    }

    .col-7 {
        width: 75%;
    }

    .col-5 {
        width: 50%;
    }

    table {
        width: 100%;
    }

    table th {
        width: 25%;
        padding: 5px 15px 2px 0px;
    }

    table td {
        border-bottom: 1px solid #878787;
        padding: 5px 15px 2px 0px;
    }

    .check-box {
        padding: 2px 12px;
        margin-right: 5px;
        border: 1px solid #878787;
    }

</style>
<!-- AdminLTE -->
<link rel="stylesheet" href="https://adminlte.io/themes/v3/dist/css/adminlte.min.css"/>

<div class="content px-3">
    <p class="center-text"><strong>{{ env('APP_COMPANY') }}</strong></p>
    <p class="center-text">{{ env('APP_ADDRESS') }}</p>
    
    <p class="center-text"><strong>SERVICE REQUEST FORM</strong></p>

    <div class="divider"></div>

    <div class="row">
        <div class="col-sm-4">
            <p>Ticket No: <u>{{ $tickets->id }}</u></p>
            <p>Date Filed: <u>{{ date('F d, Y', strtotime($tickets->created_at)) }}</u></p>
            <p>Time Filed: <u>{{ date('h:i A', strtotime($tickets->created_at)) }}</u></p>
        </div>

        <div class="col-sm-8">
            <table>
                <tr>
                    <th style="width: 40%;">Member Consumer:</th>
                    <td>{{ $tickets->ConsumerName }}</td>
                </tr>
                <tr>
                    <th>Address:</th>
                    <td>{{ Tickets::getAddress($tickets) }}</td>
                </tr>
                <tr>
                    <th>Account Number:</th>
                    <td>{{ $account != null ? $account->OldAccountNo : '-' }}</td>
                </tr>
                <tr>
                    <th>Meter Number:</th>
                    <td>{{ $tickets->CurrentMeterNo }}</td>
                </tr>
            </table>
        </div>
        
    </div>

    <div class="divider"></div>

    <div class="row">
        @php
            $parent = TicketsRepository::where('id', $tickets->ParentTicket)->first();
        @endphp
        <div class="col-sm-6">
            <p><strong>Complain Details</strong></p>
            <table>
                <tr>
                    <th>Request/Complain: </th>
                    <td>{{ $parent != null ? $parent->Name . ' - ' : '' }}{{ $tickets->Ticket }}</td>
                </tr>
                <tr>
                    <th>Reason: </th>
                    <td>{{ $tickets->Reason }}</td>
                </tr>
                <tr>
                    <th>Notes: </th>
                    <td>{{ $tickets->Notes }}</td>
                </tr>
                <tr>
                    <th>Action Taken: </th>
                    <td></td>
                </tr>
                <tr>
                    <th style="color: transparent;">Action</th>
                    <td></td>
                </tr>
            </table>
        </div>

        <div class="col-sm-6">
            <p><strong>Change Meter Details</strong></p>
            <table>
                <tr>
                    <th>Pull Out Reading</th>
                    <td></td>
                </tr>
                <tr>
                    <th style="width: 45%">New Meter Number</th>
                    <td></td>
                </tr>
                <tr>
                    <th>New Meter Brand</th>
                    <td></td>
                </tr>
                <tr>
                    <th>New Meter Kwh Start</th>
                    <td></td>
                </tr>
                <tr>
                    <th>Remarks</th>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    <br>
    <p style="margin-bottom: 5px !important;"><i>Assessment</i></p>

    <div class="row">
        <div class="col-5">
            <p><span class="check-box"></span>Executed</p>
            <table>
                <tr>
                    <th>Timestamp:</th>
                    <td></td>
                </tr>
            </table>
            <table>
                <tr>
                    <th>Remarks:</th>
                    <td></td>
                </tr>
            </table>
        </div>

        <div class="col-5">
            <p><span class="check-box"></span>Not Executed</p>
            <table>
                <tr>
                    <th>Timestamp:</th>
                    <td></td>
                </tr>
            </table>
            <table>
                <tr>
                    <th>Reason:</th>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>

    <br>
    <div class="divider-dotted"></div>
    <br>

    <p class="center-text"><strong>{{ env('APP_COMPANY') }}</strong></p>
    <p class="center-text">{{ env('APP_ADDRESS') }}</p>

    <br>
    
    <p class="center-text"><strong>ACKNOWLEDGEMENT</strong></p>

    <br>

    <p>This is to acknowledge that we have received the request of Mr./Ms. <u>{{ $tickets->ConsumerName }}</u> on the _______ day of __________________ of the year 20____.</p>
    <br>
    <br>
    <div class="row">
        <div class="col-5">
            <table>
                <tr>
                    <td></td>
                </tr>
            </table>
            <p class="center-text"><i>MSD Coordinator</i></p>
        </div>

        <div class="col-5">
            <table>
                <tr>
                    <td></td>
                </tr>
            </table>
            <p class="center-text"><i>Date & Time</i></p>
        </div>
    </div>

    <p class="center-text"><i>- Do What is Right -</i></p>
</div>

<script>
   window.onload = function() {
        window.print(); 

        window.setTimeout(function(){
            window.location.href = "{{ route('tickets.show', [$tickets->id]) }}";
        }, 200);
    }
</script>