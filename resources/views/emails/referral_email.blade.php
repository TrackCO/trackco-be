@extends('emails.default', ['notifiable' => $notifiable])

@section('subject', 'You Have Been Referred to Join')

@section('content')
    <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">You have an Amazing friend!</p>
    <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">Dear {{ ucwords($recipient['name']) }},</p>
    <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">
        You’ve been invited to offset together with your friend {{ ucwords($referrerName) }}, who thinks you’ll like LOT.
    </p>
    <div>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">Note from {{ ucwords($referrerName) }}:</p>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">
            Hey, have you heard of LOT? They offer carbon offsetting projects that’ll allow us to offset our carbon footprint and work towards a sustainable ecosystem in the future.
        </p>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">You can start by using the link below:</p>
    </div>
    <a href="{{ $referralLink }}" target="_blank" rel="noopener noreferrer" style="color: #006D04; font-size: 14px; font-weight: 700; line-height: 24px; text-align: left; text-decoration: none;">letsoffsettogether.com</a>
    <div>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">Warm regards,</p>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">The LOT Team</p>
    </div>
@endsection
