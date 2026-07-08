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
}
