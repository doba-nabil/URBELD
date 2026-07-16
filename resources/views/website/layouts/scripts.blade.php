<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('website/assets/lib/wow/wow.min.js') }}"></script>
<script src="{{ asset('website/assets/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('website/assets/lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('website/assets/lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Template Javascript -->
<script src="{{ asset('website/assets/js/main.js') }}"></script>
<script>
$(document).ready(function() {
    $('select[name="region_id"]').each(function() {
        const regionSelect = $(this);
        const form = regionSelect.closest('form, .filter-wrapper, .search-section, .contact-form-section, .filter-card');
        let citySelect = form.length ? form.find('select[name="city_id"]') : $('select[name="city_id"]');
        
        if (!citySelect.length) {
            citySelect = form.length ? form.find('#city_id') : $('#city_id');
        }

        if (regionSelect.length && citySelect.length) {
            if (regionSelect.data('linked-city')) return;
            regionSelect.data('linked-city', true);

            const originalCities = citySelect.find('option').clone();
            
            regionSelect.on('change', function() {
                const regionId = $(this).val();
                const currentCityVal = citySelect.val();
                
                citySelect.empty();
                
                const placeholder = originalCities.filter(function() { return !$(this).val(); }).first().clone();
                if(placeholder.length) {
                    citySelect.append(placeholder);
                } else {
                    citySelect.append('<option value="">اختر المدينة</option>');
                }
                
                if (regionId) {
                    citySelect.prop('disabled', false);
                    originalCities.each(function() {
                        if ($(this).val() && $(this).data('region') == regionId) {
                            citySelect.append($(this).clone());
                        }
                    });
                } else {
                    citySelect.prop('disabled', true);
                }
                
                if (citySelect.find(`option[value="${currentCityVal}"]`).length) {
                    citySelect.val(currentCityVal);
                } else {
                    citySelect.val('');
                }
                
                if (citySelect.hasClass('select2-hidden-accessible') || citySelect.hasClass('select2')) {
                    citySelect.trigger('change.select2');
                } else {
                    citySelect.trigger('change');
                }
            });
            
            setTimeout(() => {
                if (regionSelect.val()) {
                    regionSelect.trigger('change');
                } else {
                    citySelect.prop('disabled', true);
                    if (citySelect.hasClass('select2-hidden-accessible') || citySelect.hasClass('select2')) {
                        citySelect.trigger('change.select2');
                    }
                }
            }, 100);
        }
    });
});
</script>
@stack('scripts')
