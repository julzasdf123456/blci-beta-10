@php
    use App\Models\IDGenerator;
@endphp
<div class="table-responsive">
    <table class="table" id="thirdPartyTokens-table">
        <thead>
        <tr>
        <th>Company</th>
        <th>Code</th>
        <th>Token</th>
        <th>Expires In</th>
        <th>Days Remaining</th>
        <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($thirdPartyTokens as $thirdPartyTokens)
            <tr>
            <td>{{ $thirdPartyTokens->ThirdPartyCompany }}</td>
            <td>{{ $thirdPartyTokens->ThirdPartyCode }}</td>
            <td>
                {{ $thirdPartyTokens->ThirdPartyToken }}
                <a href="{{ route('thirdPartyTokens.regenerate-token', [$thirdPartyTokens->id]) }}" class="btn btn-xs btn-warning float-right"><i class="fas fa-sync ico-tab-mini"></i>Regenerate Token</a>
            </td>
            <td>{{ $thirdPartyTokens->Notes != null ? date('F d, Y', strtotime($thirdPartyTokens->Notes)) : '' }}</td>
            <td>
                {{ $thirdPartyTokens->Notes != null ? (IDGenerator::getDaysDifference(date('Y-m-d'), date('Y-m-d', strtotime($thirdPartyTokens->Notes)))) . ' days' : '-' }}
            </td>
            <td width="120">
                {!! Form::open(['route' => ['thirdPartyTokens.destroy', $thirdPartyTokens->id], 'method' => 'delete']) !!}
                <div class='btn-group'>
                    <a href="{{ route('thirdPartyTokens.show', [$thirdPartyTokens->id]) }}"
                        class='btn btn-default btn-xs'>
                        <i class="far fa-eye"></i>
                    </a>
                    <a href="{{ route('thirdPartyTokens.edit', [$thirdPartyTokens->id]) }}"
                        class='btn btn-default btn-xs'>
                        <i class="far fa-edit"></i>
                    </a>
                    {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                </div>
                {!! Form::close() !!}
            </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
