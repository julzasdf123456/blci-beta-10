<span class="text-muted"><strong>Users</strong></span><br>
<table class="table table-hover table-bordered">
    <tbody>
        <tr>
            <td style="width: 120px;">
                <input type="number" id="PasswordDaysExpire" value="{{ $systemSettings == null ? '0' : $systemSettings->PasswordDaysExpire }}" class="form-control">
            </td>
            <td class="v-align"><strong>Number of days the user's password expires and needs to be updated. </strong><span class="text-muted"> (0 - never expires)</span></td>
        </tr>
    </tbody>
</table>