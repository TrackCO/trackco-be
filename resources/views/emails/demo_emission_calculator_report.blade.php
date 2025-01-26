@extends('emails.default', ['notifiable' => $notifiable])

@section('subject', 'You Have Been Referred to Join')

@section('content')
    <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">Dear {{ ucwords($data['first_name']) }},</p>
    <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">

    </p>
    <div>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">
            Kindly find your calculated footprint emission below:
        </p>
    </div>
    <div>
        <table align="center" width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; background-color: #ffffff; border: 1px solid #e0e0e0; border-radius: 8px; margin: 20px auto;">
            <tr>
                <td style="padding: 20px;">
                    <h1 style="font-size: 24px; margin: 0; color: #333;">Your Carbon Footprint</h1>
                    <p style="font-size: 16px; color: #777; margin: 8px 0 20px;">Results based on your energy input</p>

                    <div style="background-color: #f0f7f3; border-radius: 8px; padding: 16px; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center;">
                            <span style="font-size: 20px; color: #4CAF50; margin-right: 10px;">⚡</span>
                            <span style="font-size: 16px; color: #333;">Energy</span>
                        </div>
                        <span style="font-size: 16px; color: #4CAF50; font-weight: bold;">{{ $data['total'] }} metric tonnes of CO2e</span>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <p style="font-size: 14px; color: #333; margin: 20px 0 0;">{{ $data['country'] }} Country Average</p>
                        <p style="font-size: 14px; color: #333; font-weight: bold; margin: 20px 0 0;"> | {{ $data['countryEmissionFactor'] }} tonnes of CO2e</p>

                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">Warm regards,</p>
        <p style="width: 504px; gap: 0px; font-size: 14px; font-weight: 400; line-height: 24px; text-align: left;">The LOT Team</p>
    </div>

@endsection
