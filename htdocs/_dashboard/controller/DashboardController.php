<?php
namespace Dashboard\Controller;

use Dashboard\Model\ProjectModel;

class DashboardController {
    public function showDashboard(): void {
        $projects = ProjectModel::fetchProjects();
        include __DIR__ . '/../view/dashboard-template.php';
    }
}
