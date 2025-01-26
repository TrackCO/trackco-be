@extends('emails.default', ['notifiable' => $notifiable])

@section('subject', 'Invitation to Join the Team')

@section('content')
    <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">Hello {{ ucwords($user->full_name) }},</p>
    <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">Ever wondered how you can create a balance in the ecosystem?</p>
    <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">
        {{ $company }} wants you to join hands to create a greener path and build
        a sustainable future for everyone. You’ve been invited to support
        activities that will reduce the emissions you create in your daily
        activities. By offsetting together, you can:
    </p>
    <ul>
        <li>Reduce your climate impact</li>
        <li>Support sustainable development projects</li>
        <li>Encourage innovation in clean energy solutions</li>
    </ul>
    <button class="Button" style="margin: 10px 0 15px; color: #fff; background-color: #006D04; padding: 10px 15px; border: none; border-radius: 5px; transition: 0.5s;">
        <a href="{{ $url }}" target="_blank" style="color: white; text-decoration: none;">Complete Sign Up</a>
    </button>
    <div>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">Warm regards,</p>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">The LOT Team</p>
    </div>
@endsection
