<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubscriptionPackage;

class CheckoutController extends Controller
{
    /**
     * Show the placeholder checkout page for a package.
     */
    public function package(SubscriptionPackage $package)
    {
        return view('website.checkout.package', compact('package'));
    }

    /**
     * Process the payment for a package (Mock).
     */
    public function process(Request $request, SubscriptionPackage $package)
    {
        $user = $request->user();
        
        $user->subscription_package_id = $package->id;
        $user->subscription_start_at = now();
        $user->subscription_end_at = now()->addDays($package->duration_days);
        $user->save();

        return redirect()->route('profile.subscription')->with('success', 'تم تأكيد الدفع وتفعيل الباقة بنجاح!');
    }
}
