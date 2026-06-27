<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\ServiceRequest;
use App\Models\ServiceRequestResponse;
use App\Models\Category;

class DebugRequests extends Command
{
    protected $signature = 'debug:requests';
    protected $description = 'Debug service request routing';

    public function handle()
    {
        $this->info('=== ALL PROVIDERS ===');
        $providers = User::serviceProviders()->get();
        foreach ($providers as $p) {
            $cats = $p->categories->pluck('id')->toArray();
            $this->line("ID:{$p->id} | Name:{$p->name} | Active:{$p->active} | MembershipID:{$p->membership_id} | CategoryIDs:" . json_encode($cats));
        }

        $this->info("\n=== LATEST 3 SERVICE REQUESTS ===");
        $requests = ServiceRequest::latest()->take(3)->get();
        foreach ($requests as $r) {
            $this->line("ReqID:{$r->id} | CatID:{$r->category_id} | SubCatID:{$r->sub_category_id} | ProviderID:{$r->provider_id} | Status:{$r->status}");
            $responses = ServiceRequestResponse::where('service_request_id', $r->id)->get();
            $this->line("  Responses: {$responses->count()}");
            foreach ($responses as $resp) {
                $this->line("    -> UserID:{$resp->user_id} | Status:{$resp->status}");
            }
        }

        $this->info("\n=== CATEGORIES TREE ===");
        $categories = Category::whereNull('parent_id')->with('children')->get();
        foreach ($categories as $c) {
            $this->line("Main: ID:{$c->id} | Name:{$c->name}");
            foreach ($c->children as $child) {
                $this->line("  Sub: ID:{$child->id} | Name:{$child->name}");
            }
        }

        $this->info("\n=== SIMULATED QUERY FOR LATEST REQUEST ===");
        $latest = ServiceRequest::latest()->first();
        if ($latest) {
            $categoryId = $latest->category_id;
            $subCategoryId = $latest->sub_category_id;
            $matchIds = array_filter([$categoryId, $subCategoryId]);
            $this->line("Matching against category IDs: " . json_encode($matchIds));

            $matched = User::serviceProviders()
                ->where('active', 'active')
                ->whereNotNull('membership_id')
                ->whereHas('categories', function ($q) use ($matchIds) {
                    $q->whereIn('categories.id', $matchIds);
                })
                ->get();
            $this->line("Matched providers: {$matched->count()}");
            foreach ($matched as $m) {
                $this->line("  -> ID:{$m->id} | Name:{$m->name}");
            }

            // Debug each condition separately
            $this->info("\n=== CONDITION BREAKDOWN ===");
            $step1 = User::serviceProviders()->count();
            $this->line("1. serviceProviders() total: {$step1}");

            $step2 = User::serviceProviders()->where('active', 'active')->count();
            $this->line("2. + where active='active': {$step2}");

            $step3 = User::serviceProviders()
                ->where('active', 'active')
                ->whereHas('categories', function ($q) use ($matchIds) {
                    $q->whereIn('categories.id', $matchIds);
                })->count();
            $this->line("3. + whereHas categories in " . json_encode($matchIds) . ": {$step3}");
        }
    }
}
