<?php
declare(strict_types=1);

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{
    public function index(): RedirectResponse
    {
        $user = Auth::user();
        $agent = new Agent();

        if ($user->hasPermission([8, 9, 15]) && ! $agent->isMobile()) {
            return redirect()->route('nota-tecnica.index');
        }

        if ($user->hasPermission([19]) && ! $agent->isMobile()) {
            return redirect()->route('manager.position.index');
        }

        if ($user->hasPermission([20]) && ! $agent->isMobile()) {
            return redirect()->route('manager.agenda.index');
        }

        return redirect()->route('parlamentares.index');
    }
}
