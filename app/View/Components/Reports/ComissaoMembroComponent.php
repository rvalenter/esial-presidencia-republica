<?php

namespace App\View\Components\Reports;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ComissaoMembroComponent extends Component
{
    public $membro;

    public $mobile;

    public $classBorder;

    public function __construct($membro, $mobile, $classBorder)
    {
        $this->membro = $membro;
        $this->mobile = $mobile;
        $this->classBorder = $classBorder;
    }

    public function render(): View|Closure|string
    {
        return view('components.reports.comissao-membro-component');
    }
}
