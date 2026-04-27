@php
    $t = fn(string $key, array $replace = []) => __('ui.notifications.'.$key, $replace);
@endphp
@switch($type)
    @case('donor_approved')
        {{ $t('donor_approved.message') }} @break
    @case('donor_rejected')
        {{ $t('donor_rejected.message') }} @break
    @case('donor_banned')
        {{ $t('donor_banned.message') }} @break

    @case('blood_request_updated')
        @php $status = $data['status'] ?? 'approved'; @endphp
        {{ $t('blood_request_updated.'.$status, ['blood_group' => $data['blood_group'] ?? '']) }} @break

    @case('donation_verified')
        {{ $t('donation_verified.message', ['blood_group' => $data['blood_group'] ?? '']) }} @break
    @case('donation_rejected')
        {{ $t('donation_rejected.message', ['blood_group' => $data['blood_group'] ?? '']) }} @break

    @case('new_donor_registered')
        {{ $t('new_donor_registered.message', ['name' => $data['donor_name'] ?? '', 'blood_group' => $data['blood_group'] ?? '']) }} @break

    @case('new_blood_request')
        {{ $t('new_blood_request.message', ['name' => $data['requester'] ?? '', 'blood_group' => $data['blood_group'] ?? '', 'urgency' => $data['urgency'] ?? '']) }} @break

    @case('new_donation_submitted')
        {{ $t('new_donation_submitted.message', ['name' => $data['donor_name'] ?? '', 'blood_group' => $data['blood_group'] ?? '']) }} @break

    @case('claim_approved')
        {{ $t('claim_approved.message', ['cert' => $data['certificate_number'] ?? '']) }} @break
    @case('claim_rejected')
        {{ $t('claim_rejected.message') }} @break
    @case('new_claim_submitted')
        {{ $t('new_claim_submitted.message', ['name' => $data['donor_name'] ?? '']) }} @break

    @default
        {{ $data['message'] ?? '' }}
@endswitch
