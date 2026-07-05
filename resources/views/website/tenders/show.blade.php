@extends('layouts.website')

@section('title', 'تصميم مخططات هندسية لمبنى سكني')

@section('content')
<!-- Header Start -->
<div class="category-header-section text-center services-header-section without-search">
    <div class="container" style="max-width: 1320px;">
        <h1 class="fw-bold mb-3 wow fadeInUp" data-wow-delay="0.1s">تصميم مخططات هندسية لمبنى سكني من 4 طوابق — حي الشاطئ، جدة</h1>
        
        <div class="d-flex justify-content-center gap-2 flex-wrap wow fadeInUp" data-wow-delay="0.2s">
            <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 8px 15px; font-size: 13px; font-weight: 500; border-radius: 20px;">
                <i class="bi bi-circle-fill" style="font-size: 10px; color: #22c55e;"></i> مفتوحة
            </span>
            <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 8px 15px; font-size: 13px; font-weight: 500; border-radius: 20px;">
                <i class="bi bi-file-earmark-text"></i> تصميم معماري
            </span>
            <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 8px 15px; font-size: 13px; font-weight: 500; border-radius: 20px;">
                <i class="bi bi-geo-alt-fill text-danger"></i> جدة
            </span>
            <span class="badge" style="background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2); padding: 8px 15px; font-size: 13px; font-weight: 500; border-radius: 20px;">
                <i class="bi bi-calendar-event"></i> تنتهي: 26 / 7 / 1446
            </span>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- PAGE BODY -->
<div class="page-wrap" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

  <!-- MAIN -->
  <div>
    <div class="main-card">

      <!-- INFO GRID -->
      <div class="section">
        <div class="section-title"><i class="bi bi-bar-chart-fill text-primary"></i> تفاصيل المناقصة</div>
        <div class="info-grid">
          <div class="info-box highlight">
            <div class="ib-icon"><i class="bi bi-cash-stack text-success"></i></div>
            <div class="ib-label">الميزانية التقديرية</div>
            <div class="ib-value">45,000 ريال</div>
          </div>
          <div class="info-box warn">
            <div class="ib-icon"><i class="bi bi-calendar-event text-danger"></i></div>
            <div class="ib-label">تاريخ الانتهاء</div>
            <div class="ib-value">26 / 7 / 1446</div>
          </div>
          <div class="info-box">
            <div class="ib-icon"><i class="bi bi-geo-alt-fill text-danger"></i></div>
            <div class="ib-label">الموقع</div>
            <div class="ib-value">جدة — حي الشاطئ</div>
          </div>
          <div class="info-box">
            <div class="ib-icon"><i class="bi bi-file-earmark-text text-secondary"></i></div>
            <div class="ib-label">التخصص</div>
            <div class="ib-value">تصميم معماري</div>
          </div>
          <div class="info-box">
            <div class="ib-icon"><i class="bi bi-building text-primary"></i></div>
            <div class="ib-label">نوع المشروع</div>
            <div class="ib-value">مبنى سكني</div>
          </div>
          <div class="info-box">
            <div class="ib-icon"><i class="bi bi-calendar-check text-success"></i></div>
            <div class="ib-label">تاريخ النشر</div>
            <div class="ib-value">10 / 7 / 1446</div>
          </div>
        </div>
      </div>

      <!-- DESCRIPTION -->
      <div class="section">
        <div class="section-title"><i class="bi bi-journal-text text-warning"></i> وصف المناقصة</div>
        <div class="desc-text">
          <p>نطلب من المكاتب الهندسية المتخصصة وذوي الخبرة المعمارية تقديم عروضهم لتنفيذ تصميم معماري متكامل لمبنى سكني مكوّن من <strong>4 طوابق + دور أرضي</strong> في حي الشاطئ بمدينة جدة.</p>
          <p>يشمل نطاق العمل ما يلي:</p>
          <ul>
            <li>المخططات المعمارية الكاملة (مسقط أفقي، واجهات، قطاعات)</li>
            <li>مخططات الموقع العام والمحيط</li>
            <li>مخططات التنسيق بين التخصصات (معماري، إنشائي، ميكانيكي)</li>
            <li>حزمة مواد التقديم لاستكمال رخصة البناء البلدية</li>
            <li>الامتثال الكامل لكود البناء السعودي وأنظمة أمانة جدة</li>
          </ul>
          <p>المساحة الإجمالية للأرض <strong>400 م²</strong>، والمساحة البنائية لكل طابق <strong>320 م²</strong> تقريباً. يُفضّل التصميم المتوافق مع المناخ الساحلي.</p>
        </div>
      </div>

      <!-- REQUIREMENTS -->
      <div class="section">
        <div class="section-title"><i class="bi bi-check-circle-fill text-success"></i> متطلبات التأهل</div>
        <ul class="req-list">
          <li><i class="bi bi-check-lg text-success" style="margin-top: 3px;"></i> ترخيص ممارسة المهنة من هيئة المهندسين السعوديين (سعودي أو معتمد)</li>
          <li><i class="bi bi-check-lg text-success" style="margin-top: 3px;"></i> خبرة لا تقل عن 5 سنوات في تصميم المباني السكنية</li>
          <li><i class="bi bi-check-lg text-success" style="margin-top: 3px;"></i> تقديم محفظة أعمال تتضمن مشاريع مماثلة (3 مشاريع على الأقل)</li>
          <li><i class="bi bi-check-lg text-success" style="margin-top: 3px;"></i> القدرة على التسليم خلال 3 أسابيع من توقيع العقد</li>
          <li><i class="bi bi-check-lg text-success" style="margin-top: 3px;"></i> وجود مكتب مرخص وعنوان وطني معتمد</li>
        </ul>
      </div>

      <!-- FILES DOWNLOAD -->
      <div class="section">
        <div class="section-title"><i class="bi bi-paperclip text-secondary"></i> ملفات المناقصة</div>
        <div class="files-grid">
          <div class="file-item">
            <div class="file-info">
              <div class="file-icon"><i class="bi bi-file-earmark-pdf-fill text-danger"></i></div>
              <div>
                <div class="file-name">كراسة الشروط والمواصفات.pdf</div>
                <div class="file-size">2.4 MB</div>
              </div>
            </div>
            <button class="btn-dl"><i class="bi bi-download"></i> تحميل</button>
          </div>
          <div class="file-item">
            <div class="file-info">
              <div class="file-icon"><i class="bi bi-rulers text-primary"></i></div>
              <div>
                <div class="file-name">مخطط الأرض والموقع.dwg</div>
                <div class="file-size">1.1 MB</div>
              </div>
            </div>
            <button class="btn-dl"><i class="bi bi-download"></i> تحميل</button>
          </div>
          <div class="file-item">
            <div class="file-info">
              <div class="file-icon"><i class="bi bi-images text-info"></i></div>
              <div>
                <div class="file-name">صور الموقع الحالي.zip</div>
                <div class="file-size">8.7 MB</div>
              </div>
            </div>
            <button class="btn-dl"><i class="bi bi-download"></i> تحميل</button>
          </div>
        </div>
      </div>

      <!-- UPLOAD OFFER FILES -->
      <div class="section">
        <div class="section-title"><i class="bi bi-cloud-upload-fill text-success"></i> رفع ملفات عرضك</div>
        <p style="font-size:13px;color:#6b7280;margin-bottom:.5rem;">ارفع ملفات محفظة أعمالك أو العرض الفني والمالي (PDF, DWG, ZIP — بحد أقصى 20MB لكل ملف)</p>
        <div class="upload-area" onclick="document.getElementById('fileInput').click()">
          <div class="upload-icon"><i class="bi bi-cloud-arrow-up text-success"></i></div>
          <div class="upload-text">اسحب الملفات هنا أو اضغط للرفع</div>
          <div class="upload-sub">PDF · DWG · ZIP · JPG — حتى 20MB</div>
          <input type="file" id="fileInput" multiple style="display:none" onchange="handleFiles(this.files)">
        </div>
        <div class="upload-list" id="uploadList"></div>
      </div>

    </div>
  </div>

  <!-- SIDEBAR -->
  <div class="sidebar">

    <!-- CTA -->
    <div class="cta-card">
      <div class="cta-status"><i class="bi bi-circle-fill" style="font-size: 10px; color: #22c55e;"></i> المناقصة مفتوحة</div>
      <button class="btn-main-offer" onclick="submitOffer()">
        <i class="bi bi-rocket-takeoff-fill"></i> شارك بتقديم عرضك الآن
      </button>
      <button class="btn-save"><i class="bi bi-bookmark-fill"></i> حفظ المناقصة</button>
      <p class="cta-note">بتقديم عرضك توافق على شروط<br>وأحكام منصة أوربلد السعودية</p>
    </div>

    <!-- DEADLINE -->
    <div class="deadline-card">
      <div class="dl-title"><i class="bi bi-hourglass-split"></i> الوقت المتبقي لانتهاء المناقصة</div>
      <div class="dl-timer">
        <div class="dl-unit"><div class="dl-num" id="days">16</div><div class="dl-label">يوم</div></div>
        <div class="dl-unit"><div class="dl-num" id="hours">08</div><div class="dl-label">ساعة</div></div>
        <div class="dl-unit"><div class="dl-num" id="mins">34</div><div class="dl-label">دقيقة</div></div>
        <div class="dl-unit"><div class="dl-num" id="secs">51</div><div class="dl-label">ثانية</div></div>
      </div>
    </div>

    <!-- POSTER -->
    <div class="poster-card">
      <h4><i class="bi bi-person-circle text-secondary"></i> صاحب المناقصة</h4>
      <div class="poster-info">
        <div class="poster-avatar">ق</div>
        <div>
          <div class="poster-name">شركة القزاز للاستشارات</div>
          <div class="poster-city"><i class="bi bi-geo-alt-fill text-danger"></i> جدة، المملكة العربية السعودية</div>
        </div>
      </div>
      <div class="rating"><i class="bi bi-star-fill text-warning"></i> 5.0 / 5.0 &nbsp;·&nbsp; <span style="color:#6b7280;font-weight:400;">طلب مكتمل: 1</span></div>
      <button class="btn-contact"><i class="bi bi-chat-dots-fill text-primary"></i> التواصل مع العميل</button>
    </div>

    <!-- SHARE -->
    <div style="background:#fff;border-radius:16px;border:1.5px solid #e5e7eb;padding:1.25rem;">
      <div style="font-size:14px;font-weight:700;color:#1a3a2a;margin-bottom:10px;"><i class="bi bi-link-45deg"></i> مشاركة المناقصة</div>
      <div style="display:flex;gap:8px;">
        <button onclick="copyLink(event)" style="flex:1;padding:9px;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:8px;font-size:12px;font-weight:600;font-family:'Tajawal',sans-serif;cursor:pointer;color:#374151;"><i class="bi bi-clipboard"></i> نسخ الرابط</button>
        <button style="flex:1;padding:9px;background:#dcfce7;border:1.5px solid #b6ddc8;border-radius:8px;font-size:12px;font-weight:600;font-family:'Tajawal',sans-serif;cursor:pointer;color:#15803d;"><i class="bi bi-whatsapp text-success"></i> واتساب</button>
      </div>
    </div>

  </div>
</div>

@endsection

@push('js')
<script>
// COUNTDOWN
const deadline = new Date();
deadline.setDate(deadline.getDate() + 16);
deadline.setHours(deadline.getHours() + 8);

function updateTimer() {
  const now = new Date();
  const diff = deadline - now;
  if(diff <= 0) return;
  const d = Math.floor(diff / 86400000);
  const h = Math.floor((diff % 86400000) / 3600000);
  const m = Math.floor((diff % 3600000) / 60000);
  const s = Math.floor((diff % 60000) / 1000);
  const elDays = document.getElementById('days');
  const elHours = document.getElementById('hours');
  const elMins = document.getElementById('mins');
  const elSecs = document.getElementById('secs');
  if(elDays) elDays.textContent = String(d).padStart(2,'0');
  if(elHours) elHours.textContent = String(h).padStart(2,'0');
  if(elMins) elMins.textContent = String(m).padStart(2,'0');
  if(elSecs) elSecs.textContent = String(s).padStart(2,'0');
}
setInterval(updateTimer, 1000);
updateTimer();

// FILE UPLOAD
let uploadedFiles = [];
function handleFiles(files) {
  Array.from(files).forEach(f => {
    if (!uploadedFiles.find(u => u.name === f.name)) uploadedFiles.push(f);
  });
  renderUploaded();
}
function renderUploaded() {
  const list = document.getElementById('uploadList');
  if(!list) return;
  list.innerHTML = uploadedFiles.map((f,i) => `
    <div class="upload-item">
      <div>
        <div class="upload-item-name"><i class="bi bi-file-earmark-text"></i> ${f.name}</div>
        <div class="upload-item-size">${(f.size/1024/1024).toFixed(2)} MB</div>
      </div>
      <span class="upload-item-remove" onclick="removeFile(${i})">✕</span>
    </div>`).join('');
}
function removeFile(i) { uploadedFiles.splice(i,1); renderUploaded(); }

// SUBMIT
window.submitOffer = function() {
  alert('سيتم توجيهك لنموذج تقديم العرض ✅');
}

// COPY LINK
window.copyLink = function(event) {
  navigator.clipboard.writeText(window.location.href).catch(()=>{});
  const btn = event.target;
  const origText = btn.innerHTML;
  btn.innerHTML = '<i class="bi bi-check-lg text-success"></i> تم النسخ';
  setTimeout(()=>{ btn.innerHTML = origText; }, 2000);
}

// Drag & drop
const ua = document.querySelector('.upload-area');
if(ua) {
    ua.addEventListener('dragover', e=>{ e.preventDefault(); ua.style.background='#d1fae5'; });
    ua.addEventListener('dragleave', ()=>{ ua.style.background='#f0fdf4'; });
    ua.addEventListener('drop', e=>{ e.preventDefault(); ua.style.background='#f0fdf4'; handleFiles(e.dataTransfer.files); });
}
</script>
@endpush
