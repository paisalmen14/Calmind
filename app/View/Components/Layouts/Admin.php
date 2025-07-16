<?php

namespace App\View\Components\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Admin extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        // Arahkan ke file layout yang sudah kita buat sebelumnya
        return view('layouts.admin');
    }
}