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

        .ticket-batch-print {
            page-break-after: always;
        }

        .ticket-batch-print:last-child {
            page-break-after: auto;
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

@if ($tickets != null)
    @foreach ($tickets as $item)
        <div class="content px-3 ticket-batch-print">
            <p class="center-text"><strong>{{ env('APP_COMPANY') }}</strong></p>
            <p class="center-text">({{ env('APP_COMPANY_ABRV') }})</p>
            <p class="center-text">{{ env('APP_ADDRESS') }}</p>

            <br>
            
            <p class="center-text"><strong>SERVICE REQUEST FORM</strong></p>

            <br>

            <p>Ticket No: <u>{{ $item->id }}</u></p>
            <p>Date Filed: <u>{{ date('F d, Y', strtotime($item->created_at)) }}</u></p>
            <p>Time Filed: <u>{{ date('h:i A', strtotime($item->created_at)) }}</u></p>

            <div class="divider"></div>

            <div class="row">
                <div class="col-3">
                    <p>Member Consumer:</p>
                    <p>Address:</p>
                    <p>Account Number:</p>
                    <p>Meter Number:</p>
                </div>

                <div class="col-7">
                    <p><u>{{ $item->ConsumerName }}</u></p>
                    <p><u>{{ Tickets::getAddress($item) }}</u></p>
                    <p><u>{{ $item->AccountNumber }}</u></p>
                    <p><u>n/a</u></p>
                </div>
            </div>

            <div class="divider"></div>

            <div class="row">
                @php
                    $parent = TicketsRepository::where('id', $item->ParentTicket)->first();
                @endphp
                <table>
                    <tr>
                        <th>Request/Complain: </th>
                        <td>{{ $parent != null ? $parent->Name . ' - ' : '' }}{{ $item->Ticket }}</td>
                    </tr>
                    <tr>
                        <th>Reason: </th>
                        <td>{{ $item->Reason }}</td>
                    </tr>
                    <tr>
                        <th>Notes: </th>
                        <td>{{ $item->Notes }}</td>
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
            <p class="center-text">({{ env('APP_COMPANY_ABRV') }})</p>
            <p class="center-text">{{ env('APP_ADDRESS') }}</p>

            <br>
            
            <p class="center-text"><strong>ACKNOWLEDGEMENT</strong></p>

            <br>

            <p>This is to acknowledge that we have received the request of Mr./Ms. <u>{{ $item->ConsumerName }}</u> on the _______ day of __________________ of the year 20____.</p>
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
    @endforeach
@endif


<script>
   window.onload = function() {
        window.print(); 

        window.setTimeout(function(){
            window.location.href = "{{ route('tickets.dashboard') }}";
        }, 200);
    }
</script>