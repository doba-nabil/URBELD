<table>
    <thead>
        <tr>
            <th colspan="2" style="font-weight: bold; font-size: 16px; text-align: center;">{{ __('admin.platform_reports') }}</th>
        </tr>
        <tr>
            <th style="font-weight: bold;">{{ __('admin.period_label') }}</th>
            <th>
                @if($data['dateRange'] == 'daily') {{ __('admin.daily') }}
                @elseif($data['dateRange'] == 'weekly') {{ __('admin.weekly') }}
                @elseif($data['dateRange'] == 'monthly') {{ __('admin.monthly') }}
                @else {{ __('admin.yearly') }} @endif
                ({{ __('admin.starts_from', ['date' => $data['startDate']->format('Y-m-d')]) }})
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold;">{{ __('admin.export_date') }}</th>
            <th>{{ now()->format('Y-m-d H:i') }}</th>
        </tr>
        <tr><th colspan="2"></th></tr>

        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #f0f0f0;">{{ __('admin.kpi_header') }}</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="font-weight: bold;">{{ __('admin.new_requests_created') }}</td>
            <td>{{ $data['newRequests'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">{{ __('admin.completed_requests_done') }}</td>
            <td>{{ $data['completedRequests'] }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">{{ __('admin.total_revenue_label') }}</td>
            <td>{{ $data['revenue'] }} {{ __('admin.sar') }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold;">{{ __('admin.new_registrations') }}</td>
            <td>{{ $data['newUsers'] }}</td>
        </tr>
        
        <tr><td colspan="2"></td></tr>
        
        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #f0f0f0;">{{ __('admin.top_providers') }}</th>
        </tr>
        <tr style="font-weight: bold;">
            <td>{{ __('admin.provider_name_col') }}</td>
            <td>{{ __('admin.accepted_offers_col') }}</td>
        </tr>
        @foreach($data['topProviders'] as $provider)
        <tr>
            <td>{{ $provider->name }} ({{ $provider->provider_type == 'company' ? __('admin.company') : __('admin.individual') }})</td>
            <td>{{ $provider->accepted_responses }}</td>
        </tr>
        @endforeach

        <tr><td colspan="2"></td></tr>

        <tr>
            <th colspan="2" style="font-weight: bold; background-color: #f0f0f0;">{{ __('admin.top_seekers') }}</th>
        </tr>
        <tr style="font-weight: bold;">
            <td>{{ __('admin.client_name_col') }}</td>
            <td>{{ __('admin.created_requests_col') }}</td>
        </tr>
        @foreach($data['topSeekers'] as $seeker)
        <tr>
            <td>{{ $seeker->name }}</td>
            <td>{{ $seeker->total_requests }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
