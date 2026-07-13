@extends('dashboard.layouts.master')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 border-bottom">
                        <h6>{{ __('admin.home_settings') }}</h6>
                    </div>
                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success text-white">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('dashboard.settings.homepage.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <h5 class="mt-4 mb-3 text-primary">{{ __('admin.hero_section') }}</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.main_title') }}</label>
                                    <input type="text" name="home_hero_title[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('home_hero_title', 'ar', 'نصنع المستقبل من خلال التميز') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.description') }}</label>
                                    <textarea name="home_hero_desc[ar]" class="form-control" rows="3" required>{{ \App\Models\Setting::getValue('home_hero_desc', 'ar', 'نحن من أفضل 25 شركة بناء وتطوير ملتزمون بالكامل بعملائنا. نوفر أفضل الحلول العقارية المبتكرة') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.extras') }}</label>
                                    <textarea name="home_memmbership[ar]" class="form-control" rows="3" required>{{ \App\Models\Setting::getValue('home_memmbership', 'ar', 'قمنا بتطوير مشاريع عقارية رائدة تقدم قيمة دائمة للمستثمرين والمجتمعات.') }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.btn_text') }}</label>
                                    <input type="text" name="home_hero_btn_text[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('home_hero_btn_text', 'ar', 'اعرض الخدمات') }}"
                                        required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">{{ __('admin.hero_image') }}</label>
                                    @if (\App\Models\Setting::getMediaUrl('home_hero_image'))
                                        <div class="mb-2">
                                            <img src="{{ \App\Models\Setting::getMediaUrl('home_hero_image') }}"
                                                alt="Current Hero" style="height: 100px; border-radius:8px;">
                                        </div>
                                    @endif
                                    <input type="file" name="home_hero_image" class="form-control" accept="image/*">
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3 text-primary">{{ __('admin.video_section') }}</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.small_title') }}</label>
                                    <input type="text" name="home_video_label[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('home_video_label', 'ar', 'كيف نعمل') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.main_title') }}</label>
                                    <input type="text" name="home_video_title[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('home_video_title', 'ar', 'تصاميم مبتكرة، انطباعات دائمة') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.cover_image') }}</label>
                                    @if (\App\Models\Setting::getMediaUrl('home_video_cover'))
                                        <div class="mb-2">
                                            <img src="{{ \App\Models\Setting::getMediaUrl('home_video_cover') }}"
                                                alt="Video Thumbnail" style="height: 100px; border-radius:8px;">
                                        </div>
                                    @endif
                                    <input type="file" name="home_video_cover" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.video') }}</label>
                                    @if (\App\Models\Setting::getMediaUrl('home_video'))
                                        <div class="mb-2 text-info">
                                            {{ __('admin.video_uploaded') }}
                                        </div>
                                    @endif
                                    <input type="file" name="home_video" class="form-control"
                                        accept="video/mp4,video/x-m4v,video/*">
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3 text-primary">{{ __('admin.contact_section') }}</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.small_title') }}</label>
                                    <input type="text" name="home_contact_badge[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('home_contact_badge', 'ar', 'استفسار سريع') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.title') }}</label>
                                    <input type="text" name="home_contact_title[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('home_contact_title', 'ar', 'احصل على استشارة متخصصة للعقارات السكنية أو التجارية') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.footer_text_contact') }}</label>
                                    <textarea name="home_contact_desc[ar]" class="form-control" rows="3" required>{{ \App\Models\Setting::getValue('home_contact_desc', 'ar', "نحن متحمسون للتواصل معك!\n<span>الحقول المطلوبة مميزة بـ *</span>") }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.btn_text') }}</label>
                                    <input type="text" name="home_contact_btn_text[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('home_contact_btn_text', 'ar', 'اطلب اتصال') }}"
                                        required>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3 text-primary">{{ __('admin.commitments_section') }}</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.small_title') }}</label>
                                    <input type="text" name="home_commitments_badge[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('home_commitments_badge', 'ar', 'التزامنا') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.main_title') }}</label>
                                    <input type="text" name="home_commitments_title[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('home_commitments_title', 'ar', 'ما يجعلنا مختلفين') }}"
                                        required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">{{ __('admin.description') }}</label>
                                    <textarea name="home_commitments_desc[ar]" class="form-control" rows="3" required>{{ \App\Models\Setting::getValue('home_commitments_desc', 'ar', 'الأمر لا يتعلق فقط بإنشاء شيء جيد. بل يتعلق بالتصميم والابتكار والتعاون لصنع تجارب رائعة لا مثيل لها.') }}</textarea>
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="form-label">{{ __('admin.features_list') }}</label>
                                    @php
                                        $commitmentsList = json_decode(
                                            \App\Models\Setting::getValue('home_commitments_list', 'ar', '[]'),
                                            true,
                                        );
                                        if (empty($commitmentsList) || count($commitmentsList) < 3) {
                                            $commitmentsList = [
                                                [
                                                    'title' => 'المسؤولية المؤسسية',
                                                    'description' =>
                                                        'هدفنا هو تحقيق صفر حوادث ومعدل تكرار فقدان الوقت لدينا يقود الصناعة.',
                                                ],
                                                [
                                                    'title' => 'خبراء بروح الفريق',
                                                    'description' =>
                                                        'هدفنا هو تحقيق صفر حوادث ومعدل تكرار فقدان الوقت لدينا يقود الصناعة.',
                                                ],
                                                [
                                                    'title' => 'التنوع والمساواة والشمول',
                                                    'description' =>
                                                        'هدفنا هو تحقيق صفر حوادث ومعدل تكرار فقدان الوقت لدينا يقود الصناعة.',
                                                ],
                                            ];
                                        }
                                    @endphp
                                    @foreach ($commitmentsList as $index => $item)
                                        <div class="p-3 border rounded mb-3 bg-light">
                                            <h6>{{ __('admin.feature') }} {{ $index + 1 }}</h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <input type="text"
                                                        name="home_commitments_list[{{ $index }}][title]"
                                                        class="form-control" placeholder="{{ __('admin.title') }}"
                                                        value="{{ $item['title'] ?? '' }}" required>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <input type="text"
                                                        name="home_commitments_list[{{ $index }}][description]"
                                                        class="form-control" placeholder="{{ __('admin.description') }}"
                                                        value="{{ $item['description'] ?? '' }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <h5 class="mb-3 text-primary">{{ __('admin.slider_section') }}</h5>
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <label class="form-label">{{ __('admin.slides') }}</label>
                                    @php
                                        $aboutList = json_decode(
                                            \App\Models\Setting::getValue('home_about_list', 'ar', '[]'),
                                            true,
                                        );
                                        if (empty($aboutList) || count($aboutList) < 3) {
                                            $aboutList = [
                                                [
                                                    'title' => 'المكان الأول للعثور على العقار المثالي',
                                                    'description' =>
                                                        'نحن نساعدك في العثور على منزل أحلامك من خلال خدماتنا المتميزة. نوفر لك مجموعة واسعة من الخيارات العقارية التي تناسب احتياجاتك وميزانيتك. نلتزم بتقديم أفضل تجربة عقارية لعملائنا',
                                                    'points' =>
                                                        "خدمات عقارية متكاملة\nفريق محترف وخبير\nأفضل الأسعار والعروض",
                                                ],
                                                [
                                                    'title' => 'استشارات هندسية احترافية',
                                                    'description' =>
                                                        'نقدم خدمات استشارية هندسية متخصصة لمساعدتك في اتخاذ قرارات مستنيرة بشأن استثماراتك العقارية. فريقنا من المحترفين يضمن الجودة والتميز في كل مشروع نقوم به.',
                                                    'points' =>
                                                        "حلول هندسية احترافية\nضمان الجودة والتفتيش\nاستشارات فنية متخصصة",
                                                ],
                                                [
                                                    'title' => 'تميز في إدارة المشاريع الإنشائية',
                                                    'description' =>
                                                        'من التخطيط إلى التنفيذ، نقوم بإدارة كل جانب من جوانب مشروعك الإنشائي بدقة واهتمام. نهجنا الشامل يضمن التسليم في الوقت المحدد ونتائج متميزة.',
                                                    'points' =>
                                                        "إدارة مشاريع كاملة\nتسليم في الوقت المحدد\nتحسين الميزانية",
                                                ],
                                            ];
                                        }
                                    @endphp
                                    @foreach ($aboutList as $index => $item)
                                        <div class="p-3 border rounded mb-3 bg-light">
                                            <h6>{{ __('admin.slide') }} {{ $index + 1 }}</h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <input type="text"
                                                        name="home_about_list[{{ $index }}][title]"
                                                        class="form-control" placeholder="{{ __('admin.slide_title') }}"
                                                        value="{{ $item['title'] ?? '' }}" required>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <input type="file" name="home_about_image_{{ $index }}"
                                                        class="form-control" accept="image/*">
                                                    @if (\App\Models\Setting::getMediaUrl('home_about_image_' . $index))
                                                        <div class="mt-2 text-info">{{ __('admin.slide_image_uploaded') }}</div>
                                                    @endif
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <input type="text"
                                                        name="home_about_list[{{ $index }}][description]"
                                                        class="form-control" placeholder="{{ __('admin.slide_desc') }}"
                                                        value="{{ $item['description'] ?? '' }}" required>
                                                </div>
                                                <div class="col-md-12 mb-2">
                                                    <label class="form-label text-muted small">{{ __('admin.points_hint') }}</label>
                                                    <textarea name="home_about_list[{{ $index }}][points]" class="form-control" rows="3"
                                                        placeholder="..." required>{{ $item['points'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3 text-primary">{{ __('admin.suppliers_card_section') ?? 'قسم بطاقة شركات التوريد' }}</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.show_section') ?? 'إظهار القسم' }}</label>
                                    <select name="show_suppliers_card[ar]" class="form-select">
                                        <option value="1" {{ \App\Models\Setting::getValue('show_suppliers_card', 'ar', '1') == '1' ? 'selected' : '' }}>{{ __('admin.yes') }}</option>
                                        <option value="0" {{ \App\Models\Setting::getValue('show_suppliers_card', 'ar', '1') == '0' ? 'selected' : '' }}>{{ __('admin.no') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.image') }}</label>
                                    @if (\App\Models\Setting::getMediaUrl('suppliers_card_image'))
                                        <div class="mb-2">
                                            <img src="{{ \App\Models\Setting::getMediaUrl('suppliers_card_image') }}"
                                                alt="Suppliers Card Image" style="height: 100px; border-radius:8px;">
                                        </div>
                                    @endif
                                    <input type="file" name="suppliers_card_image" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.title') }} (عربي)</label>
                                    <input type="text" name="suppliers_card_title[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('suppliers_card_title', 'ar', 'شركات التوريد والمواد') }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.title') }} (English)</label>
                                    <input type="text" name="suppliers_card_title[en]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('suppliers_card_title', 'en', 'Suppliers and Materials') }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.link') ?? 'الرابط' }} (عربي)</label>
                                    <input type="text" name="suppliers_card_link[ar]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('suppliers_card_link', 'ar', route('website.suppliers.index')) }}"
                                        required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('admin.link') ?? 'الرابط' }} (English)</label>
                                    <input type="text" name="suppliers_card_link[en]" class="form-control"
                                        value="{{ \App\Models\Setting::getValue('suppliers_card_link', 'en', route('website.suppliers.index')) }}">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">{{ __('admin.description') }} (عربي)</label>
                                    <textarea name="suppliers_card_desc[ar]" class="form-control" rows="2" required>{{ \App\Models\Setting::getValue('suppliers_card_desc', 'ar', 'نربطك بأفضل موردي مواد البناء والمعدات الهندسية في المملكة، قارن الأسعار واطلب عروضاً مباشرةً من خلال المنصة.') }}</textarea>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">{{ __('admin.description') }} (English)</label>
                                    <textarea name="suppliers_card_desc[en]" class="form-control" rows="2">{{ \App\Models\Setting::getValue('suppliers_card_desc', 'en', 'We connect you with the best suppliers of building materials and engineering equipment in the Kingdom.') }}</textarea>
                                </div>
                            </div>

                            <div class="col-12 text-end mt-4">
                                <button type="submit" class="btn btn-primary">{{ __('admin.save_changes') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
