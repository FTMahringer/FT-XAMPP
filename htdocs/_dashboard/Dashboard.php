<?php
namespace Dashboard;

use Dashboard\Controller\DashboardController;

class Dashboard {
    public function render(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            HtaccessEditor::handlePost();
            exit;
        }

        $controller = new DashboardController();
        $controller->showDashboard();
    }
}
