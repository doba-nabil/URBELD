<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\{
    IsAdminMiddleware, RedirectIfAdminAuthenticated
};
use App\Http\Controllers\Admin\{
    LoginController as AdminLoginController,
    SettingController,
    UserController,
    CompanyClassificationController,
    ProfileController,
    RoleController,
    AdminController,
    AuditController,
    CountryController,
    CityController,
    PageController,
    TestController,
    ContactController,
    SearchLogController,
    NotificationController,
    CategoryController,
    MembershipController,
    ServiceRequestController,
    ServiceController,
    SuccessPartnerController,
    UserMembershipHistoryController,
    SubscriptionPackageController,
    SubscriptionTypeController,
    ChatController,
    FaqController};

Route::get('admin-panel/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['admin_locale' => $locale]);
    }
    return redirect()->back();
})->name('admin.lang');

Route::group(['prefix' => 'admin-panel', 'middleware' => [IsAdminMiddleware::class, 'set.locale:admin', 'admin.permission']], function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin-panel');
    Route::post('/change-theme', [SettingController::class, 'changeTheme'])->name('theme.change');
    
    // Reports
    Route::get('/reports', [App\Http\Controllers\Admin\DashboardController::class, 'reports'])->name('admin.reports.index');
    Route::get('/reports/export', [App\Http\Controllers\Admin\DashboardController::class, 'exportReports'])->name('admin.reports.export');

    Route::resource('users', UserController::class);
    Route::get('users/{id}/profile', [UserController::class, 'show'])->name('users.show');

    // Suppliers
    Route::resource('supplier-products', \App\Http\Controllers\Admin\SupplierProductController::class)->except(['show']);
    Route::resource('supplier-offers', \App\Http\Controllers\Admin\SupplierOfferController::class)->except(['show']);

    Route::get('profile', [ProfileController::class , 'profile_page'])->name('profile.get');
    Route::post('profile', [ProfileController::class , 'profile_page_post'])->name('profile.post');

    Route::get('settings', [SettingController::class , 'get_settings'])->name('settings.get');
    Route::post('settings', [SettingController::class , 'update'])->name('settings.post');
    Route::post('settings/upload-media', [SettingController::class, 'uploadMedia'])->name('settings.upload_media');

    Route::resource('pages', PageController::class);
    Route::get('pages/{id}/delete_video', [PageController::class, 'delete_video'])->name('admin.delete_page_video');

    Route::resource('roles', RoleController::class);

    Route::resource('admins', AdminController::class);

    Route::resource('countries', CountryController::class);
    
    Route::get('regions/by-country/{countryId}', [\App\Http\Controllers\Admin\RegionController::class, 'getByCountry'])->name('regions.by-country');
    Route::resource('regions', \App\Http\Controllers\Admin\RegionController::class);
    
    Route::get('cities/by-country/{countryId}', [CityController::class, 'getByCountry'])->name('cities.by-country');
    Route::resource('cities', CityController::class);

    Route::get('categories/{id}/children', [CategoryController::class, 'getChildren'])->name('categories.children');
    Route::resource('categories', CategoryController::class);

    // Memberships
    Route::get('memberships/{id}/sub-categories', [MembershipController::class, 'getSubCategories'])->name('memberships.sub-categories');
    Route::get('memberships/{id}/certificates', [MembershipController::class, 'getCertificates'])->name('memberships.certificates');
    Route::get('memberships/{id}/service-requests', [MembershipController::class, 'serviceRequests'])->name('memberships.service-requests');
    Route::put('memberships/{id}/update-status', [MembershipController::class, 'updateStatus'])->name('memberships.update-status');
    Route::post('memberships/{id}/toggle-featured', [MembershipController::class, 'toggleFeatured'])->name('admin.memberships.toggle-featured');
    Route::resource('memberships', MembershipController::class);

    // Services
    Route::resource('services', ServiceController::class);
    Route::resource('company_classifications', CompanyClassificationController::class);

    // Success Partners
    Route::resource('success-partners', SuccessPartnerController::class);

    // Environmental Activity Types
    Route::resource('activity-types', \App\Http\Controllers\Admin\ActivityTypeController::class);

    // Tenders
    Route::resource('tenders', \App\Http\Controllers\Admin\TenderController::class)->only(['index', 'show']);
    Route::post('tenders/{tender}/approve', [\App\Http\Controllers\Admin\TenderController::class, 'approve'])->name('tenders.approve');
    Route::post('tenders/{tender}/reject', [\App\Http\Controllers\Admin\TenderController::class, 'reject'])->name('tenders.reject');

    // Banners
    Route::resource('banners', \App\Http\Controllers\Admin\BannerController::class)->except(['show']);

    // Service Requests
    Route::get('service-requests/provider-categories/{id}', [ServiceRequestController::class, 'getProviderCategories'])->name('service-requests.provider-categories');
    Route::resource('service-requests', ServiceRequestController::class);
    Route::post('service-requests/{id}/change-status', [ServiceRequestController::class, 'changeStatus'])->name('service-requests.change-status');
    Route::post('service-requests/update-expired', [ServiceRequestController::class, 'updateExpired'])->name('service-requests.update-expired');
    Route::post('service-requests/{id}/accept-response/{responseId}', [ServiceRequestController::class, 'acceptResponse'])->name('service-requests.accept-response');
    Route::post('service-requests/{id}/schedule-inspection', [ServiceRequestController::class, 'scheduleInspection'])->name('service-requests.schedule-inspection');
    Route::post('service-requests/{id}/complete-inspection/{inspectionId}', [ServiceRequestController::class, 'completeInspection'])->name('service-requests.complete-inspection');
    Route::post('service-requests/{id}/agree', [ServiceRequestController::class, 'agree'])->name('service-requests.agree');
    Route::post('service-requests/{id}/complete', [ServiceRequestController::class, 'complete'])->name('service-requests.complete');
    Route::post('service-requests/{id}/update-response/{responseId}', [ServiceRequestController::class, 'updateResponse'])->name('service-requests.update-response');

    // Supply Requests
    Route::resource('supply-requests', \App\Http\Controllers\Admin\SupplyRequestController::class)->only(['index', 'show', 'destroy']);

    // Tender Payments
    Route::resource('tender-payments', \App\Http\Controllers\Admin\TenderPaymentController::class)->only(['index', 'show']);


    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{id}', [ContactController::class, 'show'])->name('contacts.show');
    Route::delete('contacts/{id}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('search-logs', [SearchLogController::class, 'index'])->name('search-logs.index');
    Route::delete('search-logs/{id}', [SearchLogController::class, 'destroy'])->name('search-logs.destroy');

    Route::get('chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('chats/{uuid}/messages', [ChatController::class, 'getMessages'])->name('chats.messages');
    Route::delete('chats/{id}', [ChatController::class, 'destroy'])->name('chats.destroy');


    Route::get('notifications', [NotificationController::class, 'index'])->name('admin.notifications.index');
    Route::post('notifications/{id}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::get('notifications/unread-count', [NotificationController::class, 'getUnreadCount'])->name('notifications.unread-count');
    Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    // User Membership History
    Route::get('user-membership-history', [UserMembershipHistoryController::class, 'index'])->name('user-membership-history.index');
    Route::get('user-membership-history/{id}', [UserMembershipHistoryController::class, 'show'])->name('user-membership-history.show');

    // Membership History (Messages)
    Route::get('membership-history', [UserMembershipHistoryController::class, 'index'])->name('membership-history.index');

    // Subscription Packages
    Route::post('subscription-packages/{package}/toggle-recommended', [SubscriptionPackageController::class, 'toggleRecommended'])->name('subscription-packages.toggle-recommended');
    Route::resource('subscription-packages', SubscriptionPackageController::class);

    // Subscription Types
    Route::resource('subscription-types', SubscriptionTypeController::class);

    // FAQs
    Route::resource('faqs', FaqController::class);

    Route::group(['prefix' => 'audits', 'as' => 'audits.'], function () {
        Route::get('/', [AuditController::class, 'index'])->name('index');
        Route::get('/{audit}', [AuditController::class, 'show'])->name('show');
    });

    // Server Command Runner
    Route::get('commands', [App\Http\Controllers\Admin\CommandController::class, 'index'])->name('admin.commands.index');
    Route::post('commands/execute', [App\Http\Controllers\Admin\CommandController::class, 'execute'])->name('admin.commands.execute');
    Route::post('sync-admin-permissions', [App\Http\Controllers\Admin\CommandController::class, 'syncAdminPermissions'])->name('admin.sync-permissions');

    // Landing Page
    Route::get('landing-page', [App\Http\Controllers\Admin\LandingPageController::class, 'index'])->name('admin.landing-page.index');
    Route::post('landing-page/settings', [App\Http\Controllers\Admin\LandingPageController::class, 'updateSettings'])->name('admin.landing-page.settings.update');
    Route::get('landing-page/features/create', [App\Http\Controllers\Admin\LandingPageController::class, 'createFeature'])->name('admin.landing-page.features.create');
    Route::post('landing-page/features', [App\Http\Controllers\Admin\LandingPageController::class, 'storeFeature'])->name('admin.landing-page.features.store');
    Route::get('landing-page/features/{feature}/edit', [App\Http\Controllers\Admin\LandingPageController::class, 'editFeature'])->name('admin.landing-page.features.edit');
    Route::put('landing-page/features/{feature}', [App\Http\Controllers\Admin\LandingPageController::class, 'updateFeature'])->name('admin.landing-page.features.update');
    Route::delete('landing-page/features/{feature}', [App\Http\Controllers\Admin\LandingPageController::class, 'destroyFeature'])->name('admin.landing-page.features.destroy');


    Route::any('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

Route::group(['prefix' => 'admin-panel', 'middleware' => [RedirectIfAdminAuthenticated::class]], function () {
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login']);
});
