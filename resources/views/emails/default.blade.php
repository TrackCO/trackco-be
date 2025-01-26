<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <title>@yield('subject', 'Default Email Title')</title>
    <style>
        .Button:hover {
            background-color: #2c642ee1;
            border: 1px solid #006D04;
            box-shadow: green;
        }
        @media (min-width: 768px) and (max-width: 992px) {
            .email-subject {
                margin-top: 50px;
            }
        }
        @media (max-width: 767px) {
            .email-subject {
                margin-top: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="email-subject" style="margin: auto; font-family: 'Inter', sans-serif; width: 640px; padding: 32px; gap: 16px; border-radius: 4px; background: #F5F5F5;">
        <header class="mb-4">
            <img src="{{ asset('Socials/logo.png') }}" alt="Logo" width="94" height="41">
        </header>
        <main style="background-color: #fff; width: 576px; height: 472px; padding: 32px 32px 0px; gap: 16px; border-radius: 4px; margin-bottom: 0;">
            @yield('content')
        </main>
        <footer class="footer" style="width: 576px; height: 160px; padding: 32px; gap: 16px; border-radius: 4px;">
            <p style="font-size: 14px; font-weight: 400; line-height: 20px; text-align: left; margin-bottom: 35px;">
                This email was sent to <span id="email" class="span1" style="color: #006D04; text-decoration: underline;">{{ $notifiable->email }}</span>. If you’d rather not receive this kind
                of email, you can <span style="color: #006D04;">unsubscribe or manage your email preferences.</span>
            </p>
            <div style="display: flex; justify-content: space-between; margin-top: 32px;">
                <img src="{{ asset('Socials/logo.png') }}" alt="Logo" style="width: 94px; height: 40px;">
                <div style="display: flex; gap: 16px;">
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('Socials/facebook.png') }}" alt="Facebook" style="width: 20px; height: 20px;">
                    </a>
                    <a href="https://www.instagram.com/borderlesshr" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('Socials/insta.png') }}" alt="Instagram" style="width: 20px; height: 20px;">
                    </a>
                    <a href="https://www.linkedin.com/company/borderlesshr" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('Socials/linkedin.png') }}" alt="LinkedIn" style="width: 20px; height: 20px;">
                    </a>
                    <a href="#" target="_blank" rel="noopener noreferrer">
                        <img src="{{ asset('Socials/x.png') }}" alt="Twitter" style="width: 20px; height: 20px;">
                    </a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
