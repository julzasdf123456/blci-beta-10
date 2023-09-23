
<style>
    @media print {
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
            background-color: #dedede;
        }

        .map {
            width: 90% auto;
            height: 400px;
        }

        .left-indent {
            margin-left: 30px;
        }
    }  

    .left-indent {
        margin-left: 30px;
    }

    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

    .map {
        width: 90% auto;
        height: 400px;
    }
</style>

<div id="print-area" class="content">
    <br>
    <br>
    <br>
    <br>
    <span style="margin-left: 140px;">{{ $transactionIndex->TransactionNumber }}</span>
    <span style="margin-left: 90px;">{{ $transactionIndex->ORDate }}</span>
    <br>
    <table style="margin-top: 30px;">
        <tbody>
            @foreach ($transactionDetails as $item)
                <tr>
                    <td style="padding-left: 20px;">{{ $item->Particular }}</td>
                    <td style="padding-left: 150px; float: right;">{{ $item->Total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <span style="margin-left: 320px;">{{ $transactionIndex->Total }}</span>
    <br>
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.location.href = "{{ route('transactionIndices.service-connection-collection') }}";
    }, 800);
</script>