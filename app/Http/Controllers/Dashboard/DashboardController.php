<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\UserDashboardController;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $adminDashboard;
    protected $userDashboard;

    public function __construct(
        AdminDashboardController $adminDashboard,
        UserDashboardController $userDashboard
    ) {
        $this->adminDashboard = $adminDashboard;
        $this->userDashboard = $userDashboard;
    }

    public function __invoke(Request $request)
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard->__invoke($request);
        }

        return $this->userDashboard->__invoke($request);
    }
}
