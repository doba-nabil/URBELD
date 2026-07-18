        <div class="card mb-3 border-0 shadow-sm" style="border-right: 4px solid #d97706;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-1">
                            @if($sr->request_key)
                                <span class="badge bg-light text-dark border me-2">{{ $sr->request_key }}</span>
                            @endif
                            {{ $sr->title }}
                        </h6>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i>{{ $sr->city->name ?? '' }}
                            @if($sr->delivery_date)
                            &nbsp;|&nbsp; <i class="bi bi-calendar me-1"></i> التسليم: {{ $sr->delivery_date->format('Y-m-d') }}
                            @endif
                        </p>
                        <p class="text-muted small mb-0">{{ Str::limit($sr->description, 100) }}</p>
                    </div>
                    <div class="text-end ms-3">
                        @php
                            $srStatusMap = [
                                'open' => ['label' => 'مفتوح', 'class' => 'bg-success'],
                                'in_progress' => ['label' => 'قيد التنفيذ', 'class' => 'bg-primary'],
                                'completed' => ['label' => 'مكتمل', 'class' => 'bg-secondary'],
                                'closed' => ['label' => 'مغلق', 'class' => 'bg-dark'],
                            ];
                            $srBadge = $srStatusMap[$sr->status] ?? ['label' => $sr->status, 'class' => 'bg-secondary'];
                        @endphp
                        <span class="badge {{ $srBadge['class'] }} mb-2">{{ $srBadge['label'] }}</span>
                        <br>
                        <small class="text-muted">{{ $sr->responses->count() }} عرض</small>
                    </div>
                </div>
                <div class="mt-3 d-flex gap-2">
                    <a href="{{ route('website.supply-requests.show', $sr->id) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-eye me-1"></i> التفاصيل والعروض
                    </a>
                </div>
            </div>
        </div>
