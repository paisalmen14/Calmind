<?php

namespace App\View\Components\Layouts;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Psychologist extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        // This tells Laravel to use the 'psychologist.blade.php' file in the 'layouts' folder.
        return view('layouts.psychologist');
    }
}
