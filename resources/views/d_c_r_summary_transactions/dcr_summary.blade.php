<table class="table table-hover table-sm table-borderless">
    <thead>
        <th>GL Code</th>
        <th>Description</th>
        <th class="text-right">Amount</th>
    </thead>
    <tbody>
        @php
            $total = 0.0;
        @endphp
        @foreach ($data as $item)
            @if (floatval($item->Amount) == 0)
                
            @else
                <tr>
                    <td onclick="showDetails('{{ $item->GLCode }}')" class="text-primary"><strong>{{ $item->GLCode }}</strong></td>
                    <td>{{ $item->Description }}</td>
                    <td class="text-right">{{ number_format($item->Amount, 2) }}</td>
                </tr>
                @php
                    $total += floatval($item->Amount);
                @endphp
            @endif
            
        @endforeach
        <tr>
            <th>Total</th>
            <td></td>
            <th class="text-right">{{ number_format($total, 2) }}</th>
        </tr>
    </tbody>
</table>

@include('d_c_r_summary_transactions.gl_code_modal_expand')

@push('page_scripts')
    <script>
        function showDetails(glCode) {
            $('#modal-gl-code').modal('show')
            $('#gl-code-table tbody tr').remove()
            $('#loader-gl').removeClass('gone')
            $('#gl-title').text('Transactions Paid For ' + glCode)

            $.ajax({
                url : "{{ route('dCRSummaryTransactions.get-gl-code-payment-details') }}",
                type : 'GET',
                data : {
                    From : $('#From').val(),
                    To : $('#To').val(),
                    GLCode : glCode,
                },
                success : function(res) {
                    $('#gl-code-table tbody').append(res)
                    $('#loader-gl').addClass('gone')
                },
                error : function(err) {
                    $('#loader-gl').addClass('gone')
                    Swal.fire({
                        icon : 'error',
                        text : 'Error getting payment details'
                    })
                }
            })
        }
    </script>
@endpush