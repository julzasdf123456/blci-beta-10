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
            <div class="col-sm-6">
                <h4><strong>{{ urldecode($bapaName) }}</strong> Manual Read and Bill</h4>
            </div>
            <div class="col-sm-6">
                <div class="form-group row">
                    <div id="loader" class="spinner-border text-info float-right gone" role="status" style="margin-left: 10px;">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <label for="Period" class="col-sm-3">Set Billing Month</label>
                    <select id="Period" class="form-control col-sm-5 mx-sm-3">
                        @for ($i = 0; $i < count($months); $i++)
                            <option value="{{ $months[$i] }}" {{ $rate!=null && date('Y-m-d', strtotime($rate->ServicePeriod))==$months[$i] ? 'selected' : '' }}>{{ date('F Y', strtotime($months[$i])) }}</option>
                        @endfor
                    </select>
                    <button id="setBtn" class="btn btn-primary col-sm-3 mb-2"><i class="fas fa-check ico-tab-mini"></i>Set</button>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .select {
        background-color: #bbdefb;
    }
</style>

<div class="row">
    {{-- TO BE READ --}}
    <div class="col-lg-3">
        <div class="card" style="height: 80vh">
            <div class="card-header border-0">
                <span class="card-title" id="toBill"><i class="fas fa-exclamation-circle text-danger ico-tab"></i>Accounts to be Billed</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-sm table-hover" id="previous-table">
                    <thead>
                        <th>Acct. No</th>
                        <th>Acct. Name</th>
                        {{-- <th>Previous<br>Reading</th> --}}
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- READING FORM --}}
    <div class="col-lg-6">
        <div class="card" style="height: 80vh">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-info-circle text-primary ico-tab"></i>Read and Bill Form</span>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Account Number</td>
                        <th id="acctNo"></th>
                    </tr>
                    <tr>
                        <td>Account Name</td>
                        <th id="acctName"></th>
                    </tr>
                    <tr>
                        <td>Account ID</td>
                        <th id="acctId"></th>
                    </tr>
                    <tr>
                        <td>Account Type</td>
                        <th id="acctType"></th>
                    </tr>
                    <tr>
                        <td>Previous Reading</td>
                        <th>
                            <input id="prevReading" type="number" step="any" class="form-control" readonly>
                        </th>
                    </tr>
                    <tr>
                        <td>Present Reading</td>
                        <th>
                            <input id="presReading" type="number" step="any" class="form-control">
                        </th>
                    </tr>
                    <tr>
                        <td>Kwh Used</td>
                        <th>
                            <input id="kwhUsed" type="number" step="any" class="form-control">
                        </th>
                    </tr>
                    <tr>
                        <td>Remarks</td>
                        <th>
                            <input id="remarks" type="text" class="form-control">
                        </th>
                    </tr>
                </table>
                <div class="form-group row">
                    <button class="btn btn-default col-sm-3 mx-sm-3" id="prevBtn" onclick="moveCursor('backward')"><i class="fas fa-backward ico-tab"></i>Previous</button>
                    <button class="btn btn-primary col-sm-4 mx-sm-3" id="readAndBillBtn"><i class="fas fa-check-circle ico-tab"></i>Read and Bill</button>
                    <button class="btn btn-default col-sm-3 mx-sm-3" id="presBtn" onclick="moveCursor('forward')"><i class="fas fa-forward ico-tab"></i>Next</button>
                </div>
                
                <div class="divider"></div>

                <div class="row">
                    <div class="col-lg-12">
                        <p><i>Bill Preview</i></p>
                        <table class="table table-sm">
                            <tr>
                                <td>Bill Number</td>
                                <th id="billNo"></th>
                            </tr>
                            <tr>
                                <td>Service From</td>
                                <th id="svcFrom"></th>
                            </tr>
                            <tr>
                                <td>Service To</td>
                                <th id="svcTo"></th>
                            </tr>
                            <tr>
                                <td>Due Date</td>
                                <th id="dueDate"></th>
                            </tr>
                            <tr>
                                <td>Amount Due</td>
                                <th id="amnt-due"></th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- READ AND BILLED --}}
    <div class="col-lg-3">
        <div class="card" style="height: 80vh">
            <div class="card-header border-0">
                <span class="card-title" id="billed"><i class="fas fa-check-circle text-success ico-tab"></i>Accounts Already Billed</span>
            </div>
            <div class="card-body table-responsive px-0">
                <table class="table table-sm table-hover" id="billed-table">
                    <thead>
                        <th>Acct. Name</th>
                        <th>Kwh Used</th>
                        <th>Amnt. Due</th>
                        <th style="width: 30px;"></th>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page_scripts')
    <script>
        var readingList = {}
        var activeObject = {}
        var activeObjectIndex = 0
        var period = ''
        var toBill = 0
        var billed = 0

        $(document).ready(function() {
            $('#setBtn').on('click', function() {
                fetchReadables()
                $('#setBtn').attr('disabled', true)
                $('#Period').attr('disabled', true)
            })

            $('#presReading').keyup(function() {
                $('#kwhUsed').val(getKwhUsed())
                previewBill()
            })

            $('#kwhUsed').keyup(function() {
                previewBill()
            })

            $('#readAndBillBtn').on('click', function() {
                readAndBill()
            })
        })

        function fetchReadables() {
            $('#loader').removeClass('gone');
            readingList = {}
            activeObject = {}
            activeObjectIndex = 0
            toBill = 0
            billed = 0

            period = $('#Period').val()

            $('#previous-table tbody tr').remove()
            fetchBilledConsumers()

            $.ajax({
                url : "{{ route('api.readAndBillApi.get-bapa-account-list') }}",
                type : 'GET',
                data : {
                    BAPAName : "{{ urldecode($bapaName) }}",
                    ServicePeriod : period,
                },
                success : function(res) {
                    if (jQuery.isEmptyObject(res)) {

                    } else {
                        readingList = res
                        
                        $.each(res, function(index, element) {
                            $('#previous-table tbody').append(addRowToPrevTable(res[index]['id'], res[index]['ServiceAccountName'], res[index]['KwhUsed'], res[index]['AccountStatus'], res[index]['OldAccountNo']))
                        })

                        toBill = readingList.length

                        $('#toBill').html('<i class="fas fa-exclamation-circle text-danger ico-tab"></i> Accounts to be Billed (' + toBill + ')')

                        activeObject = readingList[activeObjectIndex]
                        addActiveObjectToQueue(activeObject)
                    }   
                    $('#loader').addClass('gone');                 
                },
                error : function() {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'An error occurred while fetching data. Contact support immediately!',
                        icon: 'error',
                    })
                    $('#loader').addClass('gone');
                }
            })
        }

        function addRowToPrevTable(accountNo, accountName, kwhUsed, status, oldAccountNo) {
            if (status == 'ACTIVE') {
                return '<tr id="' + accountNo + '" onclick=selectConsumer("' + accountNo + '")>' +
                            '<td>' + oldAccountNo + '</td>' +
                            '<td>' + accountName + '</td>' +
                            // '<td>' + kwhUsed + '</td>' +
                        '</tr>'
            } else {
                return '<tr class="text-danger" id="' + accountNo + '" onclick=selectConsumer("' + accountNo + '")>' +
                            '<th>' + oldAccountNo + '</th>' +
                            '<th>' + accountName + '</th>' +
                            // '<th>' + kwhUsed + '</th>' +
                        '</tr>'
            }            
        }

        function addActiveObjectToQueue(activeObject) {
            if (jQuery.isEmptyObject(activeObject)) {

            } else {
                // CLEAR READING FIELDS
                $('#presReading').val('')
                $('#kwhUsed').val('')

                $('#acctNo').text(activeObject['OldAccountNo'])
                $('#acctId').text(activeObject['id'])
                $('#acctName').text(activeObject['ServiceAccountName'])
                $('#acctType').text(activeObject['AccountType'])
                if (jQuery.isEmptyObject(activeObject['KwhUsed']) | activeObject['KwhUsed']==null) {
                    $('#prevReading').val('0')
                } else {
                    $('#prevReading').val(activeObject['KwhUsed'])
                }

                // add color to active queue
                $('#' + activeObject['id']).addClass('select')

                $('#presReading').focus()
            }
        }

        function moveCursor(direction) {
            if (direction == 'forward') {
                $('#' + activeObject['id']).removeClass('select')

                if (activeObjectIndex == readingList.length-1) {
                    activeObjectIndex = 0
                } else {
                    activeObjectIndex++
                }
                
                activeObject = readingList[activeObjectIndex]
                addActiveObjectToQueue(activeObject)
            } else { // backward
                $('#' + activeObject['id']).removeClass('select')

                if (activeObjectIndex == 0) {
                    activeObjectIndex = readingList.length-1
                } else {
                    activeObjectIndex--
                }
                
                activeObject = readingList[activeObjectIndex]
                addActiveObjectToQueue(activeObject)
            }
        }

        function getIndexOfSelected(id) {
            var index = 0
            index = readingList.findIndex(x => x.id === id)

            return index
        }

        function selectConsumer(id) {
            $('#' + activeObject['id']).removeClass('select')

            activeObjectIndex = getIndexOfSelected(id)
            activeObject = readingList[activeObjectIndex]
            addActiveObjectToQueue(activeObject)
        }

        function getKwhUsed() {
            var prev = parseFloat($('#prevReading').val())
            var pres = parseFloat($('#presReading').val())
            var usedkwh = pres - prev

            return usedkwh.toFixed(2)
        }

        // DETEC IF ENTER KEY IS PRESSED
        $(document).keypress(function(event){
            // var keycode = (event.keyCode ? event.keyCode : event.which);
            var keycode = event.keyCode
            if(keycode == '13'){
                readAndBill()
            }
        });

        $(document).keydown(function(event){
            // var keycode = (event.keyCode ? event.keyCode : event.which);
            var keycode = event.keyCode
            if (keycode == '39') { // right arrow
                moveCursor('forward')
            } else if (keycode == '37') { // left arrow
                moveCursor('backward')
            }
        });

        function readAndBill() {
            if (jQuery.isEmptyObject($('#presReading').val()) | jQuery.isEmptyObject($('#kwhUsed').val())) {
                Swal.fire({
                        title: 'Missing Reading',
                        text: 'Kindly fill in the Present Reading or the Kwh Used Fields',
                        icon: 'error',
                    })
            } else {
                var kwhConsumed = parseFloat($('#kwhUsed').val()).toFixed(2)
                if (kwhConsumed < 0) {
                    Swal.fire({
                            title: 'Invalid Reading',
                            text: 'Kwh used should not be less than Zero',
                            icon: 'error',
                        })
                } else {
                    $.ajax({
                        url : "{{ route('bills.bill-manually') }}",
                        type : 'GET',
                        data : {
                            id : activeObject['id'],
                            KwhUsed : $('#kwhUsed').val(),
                            PreviousKwh : $('#prevReading').val(),
                            PresentKwh : $('#presReading').val(),
                            Remarks : $('#remarks').val(),
                            ServicePeriod : period
                        },
                        success : function(res) {
                            // REMOVE FROM PREVIOUS QUEUE
                            $('#' + activeObject['id']).remove()

                            // ADD TO BILLED TABLE
                            $('#billed-table tbody').append(addRowToBilledTable(res[0]['id'], res[0]['ServiceAccountName'], res[0]['KwhUsed'], res[0]['NetAmount'], res[0]['BillId'], res[0]['ReadingId']))

                            toBill--
                            billed++

                            // UPDATE LABELS
                            $('#billed').html('<i class="fas fa-check-circle text-success ico-tab"></i> Accounts Already Billed (' + billed + ')')
                            $('#toBill').html('<i class="fas fa-exclamation-circle text-danger ico-tab"></i> Accounts to be Billed (' + toBill + ')')

                            if (toBill < 1) { // FINISHED BILLING
                                $('#setBtn').attr('disabled', false)
                                $('#Period').attr('disabled', false)
                                readingList = {}
                                activeObject = {}
                                activeObjectIndex = 0
                                toBill = 0
                                billed = 0
                                $('#presReading').val('')
                                $('#kwhUsed').val('')
                                $('#acctNo').text('')
                                $('#acctId').text('')
                                $('#acctName').text('')
                                $('#acctType').text('')
                                $('#prevReading').val('')                            

                                Swal.fire({
                                    title: 'Billing Complete',
                                    icon: 'success',
                                })
                            } else { // STILL BILLING
                                // ADD TO CURRENT EDIT
                                moveCursor('forward') 
                            }
                                                      
                        },
                        error : function(err) {
                            Swal.fire({
                                title: 'Error Generating Bill',
                                text: 'An error occurred while billing. Contact support immediately!',
                                icon: 'error',
                            })
                        },
                        statusCode :  {
                            404 : function() {
                                Swal.fire({
                                    title: 'Bill Not Generated',
                                    text: 'An error occurred while billing. Contact support immediately!',
                                    icon: 'error',
                                })
                            }
                        }
                    })                        
                }
                
            }            
        }

        function previewBill() {
            $.ajax({
                url : "{{ route('bills.get-bill-computation') }}",
                type : 'GET',
                data : {
                    id : activeObject['id'],
                    KwhUsed : $('#kwhUsed').val(),
                    PreviousKwh : $('#prevReading').val(),
                    PresentKwh : $('#presReading').val(),
                    ServicePeriod : period
                },
                success : function(res) {
                    if (!jQuery.isEmptyObject(res)) {
                        $('#amnt-due').text(Number(parseFloat(res['NetAmount']).toFixed(2)).toLocaleString())
                        $('#billNo').text(res['BillNumber'])
                        $('#svcFrom').text(res['ServiceDateFrom'])
                        $('#svcTo').text(res['ServiceDateTo'])
                        $('#dueDate').text(res['DueDate'])
                    } else {
                        console.log(res)
                    }
                },
                error : function(err) {
                    Swal.fire({
                        title: 'Oops...',
                        text: 'An error occurred while fetching data. Contact support immediately!',
                        icon: 'error',
                    })
                }
            })
        }

        function deleteBillAndReading(billId) {
            Swal.fire({
                title: 'Confirm Delete',
                text : 'Are you sure you want to delete this bill? (Use to Mouse To Click Yes, not the Enter Key for security purposes)',
                showCancelButton: true,
                confirmButtonText: 'Yes',
            }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url : "{{ route('bills.delete-bill-and-reading-ajax') }}",
                        type : 'GET',
                        data : {
                            id : billId,
                        },
                        success : function(res) {
                            Swal.fire('Bill Deleted', '', 'success')
                            fetchReadables()
                            $('#setBtn').attr('disabled', true)
                            $('#Period').attr('disabled', true)
                        },
                        error : function(err) {
                            Swal.fire('Error Deleting Bill', '', 'error')
                        }
                    })
                    
                }
            })
        }

        function addRowToBilledTable(accountNo, accountName, kwhUsed, amountDue, billId) {
            return '<tr id="' + billId + '" onclick=editReading("' + billId + '")>' +
                        '<td>' + accountName + '</td>' +
                        '<td>' + kwhUsed + '</td>' +
                        '<td>' + (amountDue != null ? Number(parseFloat(amountDue).toFixed(2)).toLocaleString() : 'DISCO') + '</td>' +
                        '<td><button onclick=deleteBillAndReading("' + billId + '") class="btn btn-xs btn-link text-danger"><i class="fas fa-trash"></i></button></td>' +
                    '</tr>'
        }

        function fetchBilledConsumers() {
            $.ajax({
                url : "{{ route('bills.fetch-billed-consumers-from-reading') }}",
                type : 'GET',
                data : {
                    ServicePeriod : period,
                    BAPAName : "{{ urldecode($bapaName) }}"
                },
                success : function(res) {
                    $('#billed-table tbody tr').remove()

                    billed = res.length

                    $('#billed').html('<i class="fas fa-check-circle text-success ico-tab"></i> Accounts Already Billed (' + billed + ')')

                    $.each(res, function(index, element) {
                        // ADD TO BILLED TABLE
                        $('#billed-table tbody').append(addRowToBilledTable(res[index]['AccountNumber'], res[index]['ServiceAccountName'], res[index]['KwhUsed'], res[index]['NetAmount'], res[index]['id']))
                    })
                },
                error : function(err) {
                    Swal.fire({
                        title: 'Error Fetching Bills',
                        text: 'An error occurred while fetching the billed consumers from this BAPA and Billing Month. Contact support immediately!',
                        icon: 'error',
                    })
                }
            })
        }
    </script>
@endpush