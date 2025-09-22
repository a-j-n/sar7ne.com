{{-- filepath: resources/views/public/privacy.blade.php --}}
@extends('layouts.app')

@section('title', 'Privacy Policy')

@section('content')
    <article class="prose max-w-none text-slate-100">
        <h1>Privacy Policy</h1>
        <p>Last updated: September 22, 2025</p>

        <p>This Privacy Policy explains how {{ env('APP_NAME', 'our application') }} collects, uses, and discloses information when you use our website or services. By using {{ env('APP_NAME', 'our application') }}, you agree to the collection and use of information in accordance with this policy.</p>

        <h2>Information We Collect</h2>
        <ul>
            <li>Information you provide directly, such as account details or profile information.</li>
            <li>Usage information about how you interact with the site, including pages visited and actions taken.</li>
            <li>Technical information from your device, such as IP address, browser type, and operating system.</li>
        </ul>

        <h2>How We Use Information</h2>
        <p>We use the information we collect to operate, maintain, and improve our services, to provide customer support, and to communicate with you about updates and promotional materials where permitted.</p>

        <h2>Cookies and Tracking</h2>
        <p>We may use cookies and similar tracking technologies to track activity on our site and hold certain information. You can control cookie preferences through your browser settings.</p>

        <h2>Third-Party Services</h2>
        <p>We may rely on third-party services (such as analytics or hosting providers) that collect, store, and use your information as described in their own policies.</p>

        <h2>Data Retention</h2>
        <p>We retain personal information for as long as necessary to provide the service and comply with legal obligations.</p>

        <h2>Your Rights</h2>
        <p>Depending on your jurisdiction, you may have rights to access, correct, or delete your personal information. To exercise these rights, please contact us using the details below.</p>

        <h2>Contact</h2>
        <p>If you have questions or concerns about this policy, contact us at: <a href="mailto:{{ env('MAIL_FROM_ADDRESS') }}">{{ env('MAIL_FROM_ADDRESS') }}</a></p>

        <h2>Changes to This Policy</h2>
        <p>We may update this Privacy Policy from time to time. We will post any changes on this page with an updated effective date.</p>
    </article>
@endsection

