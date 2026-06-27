<?php

namespace App\View\Components\Website;

use App\Models\SuccessPartner;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SuccessPartners extends Component
{
    public $partners;
    public $title;

    /**
     * Create a new component instance.
     */
    public function __construct($title = 'نفخر بالشراكة مع عملاء من الطراز الأول')
    {
        $this->partners = SuccessPartner::active()->ordered()->get();
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.website.success-partners');
    }
}
