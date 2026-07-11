<!-- Subscription / Payment Popup -->
<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="subscriptionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="subscriptionModalLabel" style="color: var(--primary);">
            {{ __('tenders.subscription_or_payment_required') ?? 'يجب الاشتراك في باقة أو دفع الرسوم' }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center pt-2 pb-4">
        <i class="bi bi-exclamation-triangle text-warning mb-3 d-block" style="font-size: 3rem;"></i>
        <p class="text-muted mb-4">
            {{ __('tenders.sub_required_desc') ?? 'عفواً، لا تملك صلاحية للقيام بهذا الإجراء. يمكنك الاشتراك في باقة سنوية للحصول على كافة المميزات أو الدفع لمرة واحدة لهذه العملية.' }}
        </p>
        <div class="d-grid gap-3">
            <a href="{{ route('website.subscription-packages.index') }}" class="btn btn-primary rounded-pill py-2 fw-bold">
                <i class="bi bi-star me-2"></i> {{ __('tenders.subscribe_now') ?? 'الاشتراك في باقة' }}
            </a>
            
            @php
                $applyFee = \App\Models\Setting::getValue('tender_apply_fee', null, '0');
            @endphp
            @if($applyFee > 0)
                <a href="#" class="btn btn-outline-primary rounded-pill py-2 fw-bold" onclick="alert('سيتم توجيهك لصفحة الدفع ({{ $applyFee }} ريال)')">
                    <i class="bi bi-credit-card me-2"></i> {{ __('tenders.pay_once') ?? 'دفع رسوم لمرة واحدة' }} ({{ $applyFee }} ر.س)
                </a>
            @endif
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    function showSubscriptionPopup() {
        var myModal = new bootstrap.Modal(document.getElementById('subscriptionModal'), {
            keyboard: false
        });
        myModal.show();
    }
</script>
