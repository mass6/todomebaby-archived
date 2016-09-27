@if (env('GOOGLE_ANALYTICS_ENABLED'))
    {!! App\Libraries\GoogleAnalyticsTracking::getTrackingCode() !!}
@endif