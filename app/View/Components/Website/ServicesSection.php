<?php

namespace App\View\Components\Website;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServicesSection extends Component
{
    public $categories;
    public $title;
    public $subtitle;

    /**
     * Create a new component instance.
     */
    public function __construct($title = 'نظرة سريعة على بعض الخدمات التي نقدمها', $subtitle = 'ما نقدمه لك')
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->categories = Category::whereNull('parent_id')
                                    ->where('is_active', true)
                                    ->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.website.services-section');
    }
}
