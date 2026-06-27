<div class="card h-100">
    <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
            <h5 class="mb-1">{{ __('admin.recent_activities') }}</h5>
            <p class="card-subtitle text-muted">{{ __('admin.latest_8_actions') }}</p>
        </div>
    </div>
    <div class="card-body">
        <ul class="timeline pb-0 mb-0">
            @foreach($recentActivityFeed as $activity)
            <li class="timeline-item timeline-item-transparent border-primary">
                <span class="timeline-point timeline-point-{{ $activity['type'] == 'user' ? 'success' : ($activity['type'] == 'request' ? 'primary' : 'warning') }}"></span>
                <div class="timeline-event">
                    <div class="timeline-header mb-1">
                        <h6 class="mb-0">
                            @if($activity['type'] == 'user')
                                {{ $activity['type_label'] }}: {{ $activity['message'] }}
                            @elseif($activity['type'] == 'request')
                                {{ $activity['type_label'] }}
                            @else
                                {{ $activity['message'] }}
                            @endif
                        </h6>
                        <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                    </div>
                    <p class="mb-2">
                        @if($activity['type'] == 'user')
                            {{ __('admin.new_user_joined_desc') }}
                        @elseif($activity['type'] == 'request')
                            {{ __('admin.new_request_published_desc') }}
                        @else
                            {{ __('admin.user_rated_experience_desc') }}
                        @endif
                    </p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
