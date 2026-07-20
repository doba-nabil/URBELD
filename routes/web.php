<?php
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\{
RedirectIfUserAuthenticated,
EnsureProfileIsCompleted,
CheckWebAuth
};
use App\Http\Controllers\Website\HomeController;
use App\Http\Controllers\Website\ProfileController;
use App\Http\Controllers\Website\PageController;
use App\Http\Controllers\Website\CategoryController;
use App\Http\Controllers\Website\ServiceController;
use App\Http\Controllers\Website\ProviderSearchController;
use App\Http\Controllers\Website\SubscriptionPackageController;
use Illuminate\Support\Facades\Artisan;
// API & Maintenance Tools
Route::get('/api/migrate/run', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return response()->json([
            'status' => 'success',
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session(['website_locale' => $locale]);
    }
    return redirect()->back();
})->name('website.lang');
Route::group(
    [
        'middleware' => [ 'set.locale:website', 'check.account.status' ]
    ], function() {
Route::get('/', [HomeController::class , 'index'])->name('home');
// Static Pages
Route::get('/about', [PageController::class , 'about'])->name('about');
Route::get('/faq', [PageController::class , 'faq'])->name('website.faq');
Route::get('/contact', [PageController::class , 'contact'])->name('contact');
Route::post('/contact', [PageController::class , 'storeContact'])->name('website.contact.store');
// Categories
Route::get('/categories', [CategoryController::class , 'index'])->name('website.categories.index');
Route::get('/categories/{category}', [CategoryController::class , 'show'])->name('website.category.show');
// Services
Route::get('/services', [ServiceController::class , 'index'])->name('website.services.index');
Route::get('/services/{service}', [ServiceController::class , 'show'])->name('website.services.show');
// Provider Search (the dynamic version of companies.html)
Route::get('/suppliers', [\App\Http\Controllers\Website\SupplierController::class, 'index'])->name('website.suppliers.index');
Route::get('/providers/search', [ProviderSearchController::class , 'index'])->name('providers.search');
Route::get('/tenders', [\App\Http\Controllers\Website\TenderController::class, 'index'])->name('website.tenders.index');
Route::get('/tenders/{id}', [\App\Http\Controllers\Website\TenderController::class, 'show'])->name('website.tenders.show')->where('id', '[0-9]+');
Route::get('/supply-requests', [\App\Http\Controllers\Website\SupplyRequestController::class, 'index'])->name('website.supply-requests.index');
Route::get('/supply-requests/{id}', [\App\Http\Controllers\Website\SupplyRequestController::class, 'show'])->name('website.supply-requests.show')->where('id', '[0-9]+');
// Subscription Packages
Route::get('/subscription-packages', [\App\Http\Controllers\Website\SubscriptionPackageController::class, 'index'])->name('website.subscription-packages.index');
// Member Profile (viewable by anyone)
Route::get('/member/{user}', [ProfileController::class , 'showPublic'])->name('member.public');
Route::get('/member/{user}/certificates', [ProfileController::class , 'showCertificates'])->name('member.certificates');
Route::get('/member/{user}/services', [ProfileController::class , 'showServices'])->name('member.services');
Route::get('/member/{user}/works', [ProfileController::class , 'showWorks'])->name('member.works');
Route::get('/user/{user}', function ($user) {
    return redirect()->route('member.public', $user);
});
Route::middleware('auth')->group(function () {
    // Tenders Authenticated Actions
    Route::get('/tenders/create', [\App\Http\Controllers\Website\TenderController::class, 'create'])->name('website.tenders.create');
    Route::get('/checkout/package/{package}', [\App\Http\Controllers\Website\CheckoutController::class, 'package'])->name('checkout.package');
    Route::post('/checkout/package/{package}/process', [\App\Http\Controllers\Website\CheckoutController::class, 'process'])->name('checkout.package.process');
    Route::get('/tenders/pay/{type}/{id?}', [\App\Http\Controllers\Website\TenderController::class, 'paymentPage'])->name('website.tenders.paymentPage');
    Route::post('/tenders/{id}/pay', [\App\Http\Controllers\Website\TenderController::class, 'pay'])->name('website.tenders.pay');
    Route::post('/tenders', [\App\Http\Controllers\Website\TenderController::class, 'store'])->name('website.tenders.store');
    Route::post('/tenders/pay-to-add', [\App\Http\Controllers\Website\TenderController::class, 'payToAdd'])->name('website.tenders.payToAdd');
    Route::get('/tenders/{id}/apply', [\App\Http\Controllers\Website\TenderController::class, 'apply'])->name('website.tenders.apply');
    Route::post('/tenders/{id}/apply', [\App\Http\Controllers\Website\TenderController::class, 'storeApplication'])->name('website.tenders.storeApplication');
    Route::post('/tenders/{id}/save', [\App\Http\Controllers\Website\TenderController::class, 'toggleSave'])->name('website.tenders.save');
    Route::post('/tenders/{id}/accept/{applicationId}', [\App\Http\Controllers\Website\TenderController::class, 'acceptApplication'])->name('website.tenders.acceptApplication');
    Route::post('/tenders/{id}/complete', [\App\Http\Controllers\Website\TenderController::class, 'completeWork'])->name('website.tenders.completeWork');
    Route::post('/tenders/{tender}/rate', [\App\Http\Controllers\Website\RatingController::class, 'storeTender'])->name('website.tenders.rate');
    
    // Supply Requests Authenticated Actions
    Route::get('/supply-requests/create', [\App\Http\Controllers\Website\SupplyRequestController::class, 'create'])->name('website.supply-requests.create');
    Route::post('/supply-requests', [\App\Http\Controllers\Website\SupplyRequestController::class, 'store'])->name('website.supply-requests.store');
    Route::post('/supply-requests/{id}/apply', [\App\Http\Controllers\Website\SupplyRequestController::class, 'storeApplication'])->name('website.supply-requests.storeApplication');
    Route::post('/supply-requests/{id}/accept/{applicationId}', [\App\Http\Controllers\Website\SupplyRequestController::class, 'acceptApplication'])->name('website.supply-requests.acceptApplication');
    Route::post('/supply-requests/{id}/complete', [\App\Http\Controllers\Website\SupplyRequestController::class, 'completeWork'])->name('website.supply-requests.completeWork');
    Route::post('/supply-requests/{supplyRequest}/rate', [\App\Http\Controllers\Website\RatingController::class, 'storeSupplyRequest'])->name('website.supply-requests.rate');
    Route::get('/profile', [ProfileController::class , 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class , 'destroy'])->name('profile.destroy');
    // Notification Settings
    Route::post('/profile/notification-settings', [\App\Http\Controllers\NotificationSettingsController::class , 'update'])->name('profile.notifications.update');
    // Profile Completion & Media
    Route::get('/profile/complete', [ProfileController::class , 'complete'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class , 'completeStore'])->name('profile.complete.store');
    Route::get('/profile/requests', [ProfileController::class , 'requests'])->name('profile.requests');
    Route::get('/profile/reports', [ProfileController::class , 'reports'])->name('profile.reports');
    Route::get('/profile/tenders', [ProfileController::class , 'tenders'])->name('profile.tenders');
    Route::get('/profile/subscription', [ProfileController::class , 'subscription'])->name('profile.subscription');
    Route::delete('/profile/media/{media}', [ProfileController::class , 'destroyMedia'])->name('profile.media.destroy');
    Route::post('/profile/photo', [ProfileController::class , 'updatePhoto'])->name('profile.photo.update');
    // Service Requests
    Route::resource('requests', \App\Http\Controllers\Website\ServiceRequestController::class)->only(['create', 'store', 'show', 'destroy']);
    Route::get('requests/provider/{user}/categories', [\App\Http\Controllers\Website\ServiceRequestController::class, 'getProviderCategories'])->name('requests.provider-categories');
    Route::post('requests/{serviceRequest}/respond', [\App\Http\Controllers\Website\ServiceRequestController::class , 'respond'])->name('requests.respond');
    Route::post('responses/{response}/accept', [\App\Http\Controllers\Website\ServiceRequestController::class , 'accept'])->name('requests.accept');
    Route::post('requests/{serviceRequest}/confirm-seeker', [\App\Http\Controllers\Website\ServiceRequestController::class , 'confirmSeeker'])->name('requests.confirm-seeker');
    Route::post('requests/{serviceRequest}/schedule', [\App\Http\Controllers\Website\ServiceRequestController::class , 'scheduleInspection'])->name('requests.schedule');
    Route::post('inspections/{inspection}/complete', [\App\Http\Controllers\Website\ServiceRequestController::class , 'completeInspection'])->name('requests.inspections.complete');
    Route::post('requests/{serviceRequest}/complete-work', [\App\Http\Controllers\Website\ServiceRequestController::class , 'completeWork'])->name('requests.complete-work');
    Route::post('/requests/{serviceRequest}/agree', [\App\Http\Controllers\Website\ServiceRequestController::class , 'confirmAgreement'])->name('requests.agree');
    // User / Seeker Specific Routes
    Route::post('/requests/{serviceRequest}/accept-provider/{provider}', [\App\Http\Controllers\Website\ServiceRequestController::class , 'acceptProvider'])->name('requests.accept-provider');
    Route::post('/requests/{serviceRequest}/reject-provider/{provider}', [\App\Http\Controllers\Website\ServiceRequestController::class , 'rejectOffer'])->name('requests.reject-provider');
    // Chat Routes (Accessible by both seeker and provider if they are participants)
    Route::get('/chats', [\App\Http\Controllers\Website\ChatController::class , 'index'])->name('chat.index');
    Route::get('chat/start/{user}', [\App\Http\Controllers\Website\ChatController::class , 'startChat'])->name('chat.start');
    Route::get('chat/{chat}', [\App\Http\Controllers\Website\ChatController::class , 'show'])->name('dashboard.chat.index'); // using the same name for now to avoid breaking existing links or dashboard.chat.show
    Route::get('chat/{chat}/show', [\App\Http\Controllers\Website\ChatController::class , 'show'])->name('dashboard.chat.show');
    Route::get('chat/{chat}/messages', [\App\Http\Controllers\Website\ChatController::class , 'getMessages'])->name('chat.messages');
    Route::post('chat/{chat}/send', [\App\Http\Controllers\Website\ChatController::class , 'sendMessage'])->name('chat.send');
    // Rating Route
    Route::post('requests/{serviceRequest}/rate', [\App\Http\Controllers\Website\RatingController::class , 'store'])->name('requests.rate');
    Route::middleware(['user.type:service_provider', 'provider.approved', EnsureProfileIsCompleted::class])->group(function () {
            Route::get('provider/requests', [\App\Http\Controllers\Website\ProviderRequestResponseController::class , 'index'])->name('provider.requests.index');
            Route::post('provider/requests/{response}/accept', [\App\Http\Controllers\Website\ProviderRequestResponseController::class , 'accept'])->name('provider.requests.accept');
            Route::post('provider/requests/{response}/reject', [\App\Http\Controllers\Website\ProviderRequestResponseController::class , 'reject'])->name('provider.requests.reject');
            // New route for provider scheduling inspection
            Route::post('requests/{serviceRequest}/schedule', [\App\Http\Controllers\Website\ProviderRequestResponseController::class , 'scheduleInspection'])->name('provider.requests.schedule');
            Route::post('requests/{serviceRequest}/ignore', [\App\Http\Controllers\Website\ProviderRequestResponseController::class , 'ignore'])->name('requests.ignore');
            // Provider Services Map
            Route::resource('provider/services', \App\Http\Controllers\Website\ProviderServiceController::class)->names([
                'index' => 'provider.services.index',
                'create' => 'provider.services.create',
                'store' => 'provider.services.store',
                'edit' => 'provider.services.edit',
                'update' => 'provider.services.update',
                'destroy' => 'provider.services.destroy',
            ]);
            // Provider Works Map
            Route::resource('provider/works', \App\Http\Controllers\Website\ProviderWorkController::class)->names([
                'index' => 'provider.works.index',
                'create' => 'provider.works.create',
                'store' => 'provider.works.store',
                'edit' => 'provider.works.edit',
                'update' => 'provider.works.update',
                'destroy' => 'provider.works.destroy',
            ]);
            Route::delete('provider/works/{work}/media/{media}', [\App\Http\Controllers\Website\ProviderWorkController::class, 'destroyImage'])->name('provider.works.media.destroy');
            // Supplier Routes
            Route::resource('supplier/products', \App\Http\Controllers\Website\Supplier\ProductController::class)->names([
                'index' => 'supplier.products.index',
                'create' => 'supplier.products.create',
                'store' => 'supplier.products.store',
                'edit' => 'supplier.products.edit',
                'update' => 'supplier.products.update',
                'destroy' => 'supplier.products.destroy',
            ]);
            Route::delete('supplier/products/{product}/media/{media}', [\App\Http\Controllers\Website\Supplier\ProductController::class, 'destroyImage'])->name('supplier.products.media.destroy');
            Route::resource('supplier/offers', \App\Http\Controllers\Website\Supplier\SupplierOfferController::class)->names([
                'index' => 'supplier.offers.index',
                'create' => 'supplier.offers.create',
                'store' => 'supplier.offers.store',
                'edit' => 'supplier.offers.edit',
                'update' => 'supplier.offers.update',
                'destroy' => 'supplier.offers.destroy',
            ]);
            Route::delete('supplier/offers/{offer}/media/{media}', [\App\Http\Controllers\Website\Supplier\SupplierOfferController::class, 'destroyImage'])->name('supplier.offers.media.destroy');
            Route::get('supplier/delivery-cities', [\App\Http\Controllers\Website\Supplier\DeliveryCityController::class, 'index'])->name('supplier.cities.index');
            Route::post('supplier/delivery-cities', [\App\Http\Controllers\Website\Supplier\DeliveryCityController::class, 'store'])->name('supplier.cities.store');
        }
        );
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\Website\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/latest', [App\Http\Controllers\Website\NotificationController::class, 'getLatest'])->name('notifications.latest');
    Route::post('/notifications/{id}/mark-as-read', [App\Http\Controllers\Website\NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [App\Http\Controllers\Website\NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    });
require __DIR__ . '/auth.php';
// Run Migrate via Browser (for shared hosting without SSH)
Route::get('/run-migrate', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        return '<pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    }
    catch (\Exception $e) {
        return '<pre>Error: ' . $e->getMessage() . '</pre>';
    }
});
// Run Schedule via Browser
Route::get('/run-schedule', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('schedule:run');
        return '<pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    }
    catch (\Exception $e) {
        return '<pre>Error: ' . $e->getMessage() . '</pre>';
    }
});
// Run Queue via Browser
Route::get('/run-queue', function () {
    try {
        // Run the worker and stop when the queue is empty
        \Illuminate\Support\Facades\Artisan::call('queue:work', ['--stop-when-empty' => true]);
        return '<pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    }
    catch (\Exception $e) {
        return '<pre>Error: ' . $e->getMessage() . '</pre>';
    }
});
// Run Permission Seeder via Browser
Route::get('/run-permission-seed', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--class' => 'PermissionSeeder', '--force' => true]);
        return '<pre>' . \Illuminate\Support\Facades\Artisan::output() . '</pre>';
    }
    catch (\Exception $e) {
        return '<pre>Error: ' . $e->getMessage() . '</pre>';
    }
});
// Landing Page
Route::get('/ERSAA', [\App\Http\Controllers\Website\LandingPageController::class, 'index'])->name('landing_page');
// Dynamic Pages (Must be at the bottom)
Route::get('/{slug}', [App\Http\Controllers\Website\PageController::class , 'show'])->name('website.page.show');
}); // End of Localization Route Group
