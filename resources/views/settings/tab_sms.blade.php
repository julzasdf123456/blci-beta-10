<p class="text-muted">Configure when to send SMS notifications to customers</p>

<span class="text-muted"><strong>CRM - Applications</strong></span><br>
<table class="table table-hover table-bordered">
    <tbody>
        <tr>
            <td style="width: 120px;">
                <input type="checkbox" id="ServiceConnectionReception" {{ $smsSettings == null ? '' : ($smsSettings->ServiceConnectionReception=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
            </td>
            <td class="v-align"><strong>Upon Receiving Application</strong></td>
        </tr>
        <tr>
            <td style="width: 120px;">
                <input type="checkbox" id="InspectionCreation" {{ $smsSettings == null ? '' : ($smsSettings->InspectionCreation=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
            </td>
            <td class="v-align"><strong>Upon Creation of Inspection Schedule</strong></td>
        </tr>
        <tr>
            <td style="width: 120px;">
                <input type="checkbox" id="InspectionToday" {{ $smsSettings == null ? '' : ($smsSettings->InspectionToday=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
            </td>
            <td class="v-align"><strong>During Inspection Day (upon downloading of inspection schedule by the inspectors)</strong></td>
        </tr>
        <tr>
            <td style="width: 120px;">
                <input type="checkbox" id="PaymentApproved" {{ $smsSettings == null ? '' : ($smsSettings->PaymentApproved=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
            </td>
            <td class="v-align"><strong>Upon Approval of Payment</strong></td>
        </tr>
    </tbody>
</table>

<span class="text-muted"><strong>Billing</strong></span><br>
<table class="table table-hover table-bordered">
    <tbody>
        <tr>
            <td style="width: 120px;">
                <input type="checkbox" id="Bills" {{ $smsSettings == null ? '' : ($smsSettings->Bills=="Yes" ? "checked" : '') }} data-bootstrap-switch data-off-color="default" data-on-color="success"/>
            </td>
            <td class="v-align"><strong>Auto-send SMS Once Bill is Available</strong></td>
        </tr>
    </tbody>
</table>