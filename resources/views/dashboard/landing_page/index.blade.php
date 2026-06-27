@extends('dashboard.layout.master')

@section('dashboard-main')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">{{ __('admin.home') }} /</span> {{ __('admin.landing_page') }}
    </h4>

    <div class="row">
        <div class="col-md-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-settings" aria-controls="navs-top-settings" aria-selected="true">
                            <i class="menu-icon icon-base ti tabler-settings me-1"></i> {{ __('admin.settings') }}
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-features" aria-controls="navs-top-features" aria-selected="false">
                            <i class="menu-icon icon-base ti tabler-list me-1"></i> {{ __('admin.features') }}
                        </button>
                    </li>
                </ul>
                <div class="tab-content">
                    <!-- Settings Tab -->
                    <div class="tab-pane fade show active" id="navs-top-settings" role="tabpanel">
                        <form action="{{ route('admin.landing-page.settings.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!-- Section 0: Hero Section -->
                                <div class="col-12"><h5 class="mb-3 text-primary"><i class="ti tabler-target me-1"></i> سكشن الهيدر (Hero)</h5></div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">عنوان الرئيسي (عربي)</label>
                                    <input type="text" name="landing_hero_title[ar]" class="form-control" value="{{ App\Models\Setting::getValue('landing_hero_title', 'ar') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">عنوان الرئيسي (EN)</label>
                                    <input type="text" name="landing_hero_title[en]" class="form-control" value="{{ App\Models\Setting::getValue('landing_hero_title', 'en') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">العنوان الفرعي (عربي)</label>
                                    <textarea name="landing_hero_subtitle[ar]" class="form-control" rows="2">{{ App\Models\Setting::getValue('landing_hero_subtitle', 'ar') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">خلفية سكشن الهيرو (صورة)</label>
                                    <input type="file" name="landing_hero_bg" class="form-control" accept="image/*">
                                    @if($heroBgUrl)
                                        <div class="mt-2">
                                            <img src="{{ $heroBgUrl }}" width="120" class="rounded border">
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12"><hr class="my-4"></div>

                                <!-- Section 1: Video Showcase -->
                                <div class="col-12"><h5 class="mb-3 text-primary"><i class="ti tabler-video me-1"></i> سكشن الفيديو</h5></div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">عنوان سكشن الفيديو (عربي)</label>
                                    <input type="text" name="landing_video_title[ar]" class="form-control" value="{{ App\Models\Setting::getValue('landing_video_title', 'ar') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">عنوان سكشن الفيديو (EN)</label>
                                    <input type="text" name="landing_video_title[en]" class="form-control" value="{{ App\Models\Setting::getValue('landing_video_title', 'en') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.video') }}</label>
                                    <input type="file" name="landing_video" class="form-control" accept="video/*">
                                    @if($videoUrl)
                                        <div class="mt-2 text-primary small">
                                            <a href="{{ $videoUrl }}" target="_blank"><i class="ti tabler-external-link"></i> {{ __('admin.view_video') }}</a>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">صورة غلاف الفيديو</label>
                                    <input type="file" name="landing_video_cover" class="form-control" accept="image/*">
                                    @if($videoCoverUrl)
                                        <div class="mt-2">
                                            <img src="{{ $videoCoverUrl }}" width="120" class="rounded border">
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12"><hr class="my-4"></div>

                                <!-- Section 2: About Us -->
                                <div class="col-12"><h5 class="mb-3 text-primary"><i class="ti tabler-info-circle me-1"></i> سكشن "من نحن"</h5></div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.main_title_ar') }}</label>
                                    <input type="text" name="landing_about_title[ar]" class="form-control" value="{{ App\Models\Setting::getValue('landing_about_title', 'ar') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.main_title_en') }}</label>
                                    <input type="text" name="landing_about_title[en]" class="form-control" value="{{ App\Models\Setting::getValue('landing_about_title', 'en') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.description_ar') }}</label>
                                    <textarea name="landing_about_description[ar]" class="form-control" rows="3">{{ App\Models\Setting::getValue('landing_about_description', 'ar') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.description_en') }}</label>
                                    <textarea name="landing_about_description[en]" class="form-control" rows="3">{{ App\Models\Setting::getValue('landing_about_description', 'en') }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">نقطة تعريفية 1 (عربي)</label>
                                    <input type="text" name="landing_about_point_1[ar]" class="form-control" value="{{ App\Models\Setting::getValue('landing_about_point_1', 'ar') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">نقطة تعريفية 1 (EN)</label>
                                    <input type="text" name="landing_about_point_1[en]" class="form-control" value="{{ App\Models\Setting::getValue('landing_about_point_1', 'en') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">نقطة تعريفية 2 (عربي)</label>
                                    <input type="text" name="landing_about_point_2[ar]" class="form-control" value="{{ App\Models\Setting::getValue('landing_about_point_2', 'ar') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">نقطة تعريفية 2 (EN)</label>
                                    <input type="text" name="landing_about_point_2[en]" class="form-control" value="{{ App\Models\Setting::getValue('landing_about_point_2', 'en') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">صورة "من نحن"</label>
                                    <input type="file" name="landing_about_image" class="form-control" accept="image/*">
                                    @if($aboutImageUrl)
                                        <div class="mt-2 text-center border p-2 rounded" style="width: 150px;">
                                            <img src="{{ $aboutImageUrl }}" class="img-fluid rounded shadow-sm">
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12"><hr class="my-4"></div>

                                <!-- Section 3: Features Side Image -->
                                <div class="col-12"><h5 class="mb-3 text-primary"><i class="ti tabler-photo me-1"></i> سكشن المميزات</h5></div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">عنوان سكشن المميزات (عربي)</label>
                                    <input type="text" name="landing_features_title[ar]" class="form-control" value="{{ App\Models\Setting::getValue('landing_features_title', 'ar') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">عنوان سكشن المميزات (EN)</label>
                                    <input type="text" name="landing_features_title[en]" class="form-control" value="{{ App\Models\Setting::getValue('landing_features_title', 'en') }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">العنوان الفرعي للمميزات (عربي)</label>
                                    <textarea name="landing_features_subtitle[ar]" class="form-control" rows="2">{{ App\Models\Setting::getValue('landing_features_subtitle', 'ar') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">العنوان الفرعي للمميزات (EN)</label>
                                    <textarea name="landing_features_subtitle[en]" class="form-control" rows="2">{{ App\Models\Setting::getValue('landing_features_subtitle', 'en') }}</textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الصورة الجانبية للمميزات</label>
                                    <input type="file" name="landing_features_image" class="form-control" accept="image/*">
                                    @if($featuresImageUrl)
                                        <div class="mt-2 text-center border p-2 rounded" style="width: 150px;">
                                            <img src="{{ $featuresImageUrl }}" class="img-fluid rounded shadow-sm">
                                        </div>
                                    @endif
                                    <small class="text-muted">نوصي بصورة شفافة أو بخلفية متناسقة.</small>
                                </div>

                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="ti tabler-device-floppy me-1"></i> {{ __('admin.save') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Features Tab -->
                    <div class="tab-pane fade" id="navs-top-features" role="tabpanel">
                        <div class="card">
                            <h5 class="card-header d-flex justify-content-between align-items-center">
                                {{ __('admin.features') }}
                                <a href="{{ route('admin.landing-page.features.create') }}" class="btn btn-primary">
                                    <i class="ti tabler-plus me-1"></i> {{ __('admin.add') }}
                                </a>
                            </h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>{{ __('admin.image') }}</th>
                                            <th>{{ __('admin.title') }}</th>
                                            <th>{{ __('admin.status') }}</th>
                                            <th>{{ __('admin.order') }}</th>
                                            <th class="text-center">{{ __('admin.actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        @forelse($features as $feature)
                                            <tr>
                                                <td>
                                                    <img src="{{ $feature->getFirstMediaUrl('image') }}" width="40" class="rounded-circle shadow-sm border">
                                                </td>
                                                <td class="fw-bold">{{ $feature->title }}</td>
                                                <td>
                                                    <span class="badge bg-label-{{ $feature->is_active ? 'success' : 'danger' }}">
                                                        {{ $feature->is_active ? __('admin.active') : __('admin.unactive') }}
                                                    </span>
                                                </td>
                                                <td><span class="badge bg-label-secondary">{{ $feature->order }}</span></td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <a href="{{ route('admin.landing-page.features.edit', $feature->id) }}" class="btn btn-sm btn-icon item-edit" title="{{ __('admin.edit') }}">
                                                            <i class="ti tabler-edit text-primary"></i>
                                                        </a>
                                                        <form action="{{ route('admin.landing-page.features.destroy', $feature->id) }}" method="POST" onsubmit="return confirm('{{ __('admin.sure') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-icon" title="{{ __('admin.delete') }}">
                                                                <i class="ti tabler-trash text-danger"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5">
                                                    <div class="text-muted">
                                                        <i class="ti tabler-database-off fs-1 d-block mb-2"></i>
                                                        {{ __('admin.no_data') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
