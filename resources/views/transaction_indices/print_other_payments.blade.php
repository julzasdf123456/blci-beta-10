@php
    use App\Models\ServiceAccounts;
    use App\Models\Bills;
@endphp
<style>
@font-face {
    font-family: 'sax-mono';
    src: url('/fonts/saxmono.ttf');
}
html, body {
    font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
    /* font-stretch: condensed; */
    font-size: .88em;
}

table tbody th,td,
table thead th {
    font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif;
    /* font-stretch: condensed; */
    /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
    font-size: .76em;
    margin: 0px;
    padding: 0px;
}
    @media print {
        @page {
            /* margin: 10px; */
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

        .left-indent {
            margin-left: 30px;
        }
    }  
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

</style>


{{-- <link rel="stylesheet" href="{{ URL::asset('adminlte.min.css') }}"> --}}

<div id="print-area" class="content" style="padding-top: 19px;">
    <span style="margin-left: 20px;">{{ $account != null ? $account->OldAccountNo : '-' }}</span>
    <span style="margin-left: 240px;">{{ $transactionIndex != null ? $transactionIndex->ORNumber : '-' }}</span><br>
    <span style="margin-left: 20px;">{{ $transactionIndex != null ? $transactionIndex->PaymentTitle : '-' }}</span><br>
    <span style="margin-left: 20px;">{{ $transactionIndex != null ? $transactionIndex->Source : '-' }}</span><br>
    
    <br>
    <span style="float: right;">{{ $transactionIndex != null ? date('h:i:s A m/d/Y', strtotime($transactionIndex->created_at)) : '-' }}</span><br>
    <br>
    <table style="margin-top: 15px; width: 100%;">
        <tbody>    
            @php
                $i = 0;
                $others = 0.0;
            @endphp 
            @foreach ($transactionDetails as $item)
                @if ($i < 5)
                    <tr>
                        {{-- <td>{{ $item->AccountCode }}</td> --}}
                        <td style="padding-left: 4px;">{{ $item->Particular }}</td>
                        <td style="padding-left: 10px; text-align: right;"></td>
                        {{-- <td style="padding-left: 10px; text-align: right;">VAT: {{ number_format($item->VAT, 2) }}</td> --}}
                        <td style="padding-left: 10px; text-align: right;">{{ number_format($item->Total, 2) }}</td>
                    </tr>

                @else
                    @php
                        $others = $others + floatval($item->Total);
                    @endphp
                @endif
                
                @php
                    $i++;
                @endphp
            @endforeach   

            @if ($others > 0)
            <tr>
                <td>Other Items</td>
                <td style="padding-left: 4px;"></td>
                <td style="padding-left: 10px; text-align: right;"></td>
                {{-- <td style="padding-left: 10px; text-align: right;"></td> --}}
                <td style="padding-left: 10px; text-align: right;">{{ number_format($others, 2) }}</td>
            </tr>
            @endif 
        </tbody>
    </table>
    <div style="position: absolute; bottom: 15px; width: 100%; left: 10px;">
        <span>{{ $user != null ? $user->name : 'Teller: n/a' }}</span>
        <span style="float: right; margin-right: 50px;">{{ number_format($transactionIndex->Total, 2) }}</span>
    </div>
    
</div>
<script type="text/javascript">
    window.print();

    window.setTimeout(function(){
        window.location.href = "{{ route('transactionIndices.other-payments') }}";
    }, 800);
</script>