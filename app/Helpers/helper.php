<?php

use App\Models\Page;
use App\Models\User;
use App\Models\Profile;

if (!function_exists('getPageBySlug')) {
    function getPageBySlug($slug)
    {
        return Page::where('slug', $slug)->first();
    }
}

if (!function_exists('footer_pages')) {
    function footer_pages()
    {
        return cache()->rememberForever('footer_pages', function () {
            return Page::select('title', 'slug')->get();
        });
    }
}

if (!function_exists('get_online_users_count')) {
    function get_online_users_count()
    {
        // Get session data
        $sessionKey = 'online_users_count';
        $sessionTimestampKey = 'online_users_count_timestamp';
        $updateInterval = 5; // Update every 5 minutes
        
        // Check if we have data in session and if it's still valid
        if (session()->has($sessionKey) && session()->has($sessionTimestampKey)) {
            $lastUpdate = session($sessionTimestampKey);
            $timeDiff = now()->diffInMinutes($lastUpdate);
            
            // If less than update interval has passed, return cached data
            if ($timeDiff < $updateInterval) {
                return session($sessionKey);
            }
        }
        
        // Generate new random numbers
        // Generate realistic random numbers (between 50-500 for males, 30-400 for females)
        $baseMale = rand(50, 500);
        $baseFemale = rand(30, 400);
        
        // Add some variation (±10%)
        $maleVariation = (int)($baseMale * 0.1);
        $femaleVariation = (int)($baseFemale * 0.1);
        
        $maleCount = $baseMale + rand(-$maleVariation, $maleVariation);
        $femaleCount = $baseFemale + rand(-$femaleVariation, $femaleVariation);
        
        // Ensure minimum values
        $maleCount = max(20, $maleCount);
        $femaleCount = max(15, $femaleCount);
        
        $result = [
            'male' => $maleCount,
            'female' => $femaleCount,
            'total' => $maleCount + $femaleCount
        ];
        
        // Store in session with timestamp
        session([$sessionKey => $result]);
        session([$sessionTimestampKey => now()]);
        
        return $result;
    }
}
