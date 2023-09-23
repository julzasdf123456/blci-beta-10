@php
    use App\Models\MemberConsumers;

@endphp
<style>
    @font-face {
        font-family: 'sax-mono';
        src: url('/fonts/saxmono.ttf');
    }
    html, body {
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-family: sans-serif;
        /* font-stretch: condensed; */
        font-size: .85em;
    }

    table tbody th,td,
    table thead th {
        font-family: sans-serif;
        /* font-family: sax-mono, Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        /* font-stretch: condensed; */
        /* , Consolas, Menlo, Monaco, Lucida Console, Liberation Mono, DejaVu Sans Mono, Bitstream Vera Sans Mono, Courier New, monospace, serif; */
        font-size: .72em;
    }
    @media print {
        @page {
            orientation: portrait;
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

        p {
            padding: 0px !important;
            margin: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }
    }  
    .divider {
        width: 100%;
        margin: 10px auto;
        height: 1px;
        background-color: #dedede;
    } 

    p {
        padding: 0px !important;
        margin: 0px;
        font-size: 1.2em;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }

    .text-right {
        text-align: right;
    }

    .half {
        display: inline-table; 
        width: 49%;
    }

    .thirty {
        display: inline-table; 
        width: 30%;
    }

    .seventy {
        display: inline-table; 
        width: 69%;
    }

    .watermark {
        position: fixed;
        left: 15%;
        width: 70%;
        opacity: 0.16;
        z-index: -99;
        color: white;
        user-select: none;
    }
</style>

<div id="print-area" class="content">
    <img src="{{ URL::asset('imgs/noneco-official-logo.png'); }}" class="watermark"> 
    <div style="text-align: center; display: inline;">
        <img src="{{ URL::asset('imgs/noneco-official-logo.png'); }}" width="70px;" style="position: absolute; left: 0; top: 0;"> 

        <p class="text-center" style="padding-bottom: 2px;"><strong>{{ strtoupper(env('APP_COMPANY')) }}</strong></p>
        <p class="text-center" style="padding-bottom: 2px;"><strong>{{ strtoupper(env('APP_COMPANY_ABRV')) }}</strong></p>
        <p class="text-center" style="padding-bottom: 2px;"><strong>{{ strtoupper(env('APP_ADDRESS')) }}</strong></p>
        <br>
        <br> 
    </div>

    
    <p class="text-center">Hereby Presents This</p>

    <p style="position: absolute; right: 0; top: 80;"><strong>{{ $memberConsumer->ConsumerId }}</strong></p>

    <p style="font-family: Brush Script MT, Brush Script Std, cursive; margin-top: 10px; font-size: 5.2em;" class="text-center">Certificate</p>
    <br>
    <p class="text-center">of membership with {{ env('APP_COMPANY') }}</p>
    <br>
    <br>

    <p class="text-center" style="font-size: 2.8em;"><strong>{{ $memberConsumer != null ? strtoupper(MemberConsumers::serializeMemberNameFormal($memberConsumer)) : '-' }}</strong></p>
    
    <br>

    <p class="text-center">of</p>

    <br>
    <p class="text-center" style="font-size: 1.5em;"><strong>{{ $memberConsumer != null ? strtoupper(MemberConsumers::getAddress($memberConsumer)) : '-' }}</strong></p>
    <br>
    <br>
    <p class="text-center">IN WITNESS WHEREOF, the Cooperative has caused this certificate to be signed by <br> its President and Secretary and its corporate seal to be hereunto affixed this</p>
    <br>
    <p class="text-center"><strong>{{ date('d', strtotime($memberConsumer->DateApplied)) }}</strong> of <strong>{{ date('F, Y', strtotime($memberConsumer->DateApplied)) }}</strong>.</p>

    <br>
    <br>
    <br><br><br>
    <br>
    <div class="half">
        @if ($secretary != null)
            <div style="width: 100%; text-align: center; margin-bottom: -25px;">
                <img src="{{ $secretary->Signature }}" alt="" style="height: 80px; margin: auto;">
            </div>
        @endif
        
        <p class="text-center" style="border-bottom: 1px solid #454545; padding-bottom: 3px; margin-right: 40px; margin-left: 40px;"><strong>{{ $secretary != null ? ($secretary->Name) : '' }}</strong></p>
        <p class="text-center">SECRETARY</p>
    </div>

    <div class="half">
        @if ($president != null)
            <div style="width: 100%; text-align: center; margin-bottom: -25px;">
                <img src="{{ $president->Signature }}" alt="" style="height: 80px; margin: auto;">
            </div>
        @endif

        <p class="text-center" style="border-bottom: 1px solid #454545; padding-bottom: 3px; margin-right: 40px; margin-left: 40px;"><strong>{{ $president != null ? ($president->Name) : '' }}</strong></p>
        <p class="text-center">PRESIDENT</p>
    </div>
</div>

<script type="text/javascript">
    window.print();
    
    window.setTimeout(function(){
        window.history.go(-1)
    }, 800);
</script>