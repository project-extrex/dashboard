<?php
namespace App\Admin\Controllers;

class DashboardController extends AdminBaseController {
    public function index(): void {
        $this->render('dashboard/index', [
            'title' => 'Dashboard'
        ]);
    }
}
