<?php
namespace Dashboard\Model;

class ProjectModel {
    public static function fetchProjects(): array {
        $baseDir = dirname(__DIR__, 2);
        $folders = array_filter(scandir($baseDir), function ($item) use ($baseDir) {
            $fullPath = $baseDir . DIRECTORY_SEPARATOR . $item;
            return $item[0] !== '.' && is_dir($fullPath);
        });

        $projects = [];
        foreach ($folders as $folder) {
            $path = $baseDir . '/' . $folder;
            $publicIndex = $path . '/public/index.php';
            $rootIndex = $path . '/index.php';

            $ignoredFolders = ['.git'];
            $ignoredFoldersStartsWith = ['.', '_'];

            // Skip if the folder is ignored
            if (in_array($folder, $ignoredFolders) ||
                array_filter($ignoredFoldersStartsWith, fn($prefix) => strpos($folder, $prefix) === 0)) {
                continue;
            }

            $indexPath = file_exists($publicIndex) ? $publicIndex : (file_exists($rootIndex) ? $rootIndex : null);
            $htaccessPath = $indexPath ? dirname($indexPath) . '/.htaccess' : null;

            $projects[] = [
                'name' => $folder,
                'index' => $indexPath,
                'htaccessExists' => $htaccessPath && file_exists($htaccessPath),
                'htaccessPath' => $htaccessPath,
                'type' => file_exists($path . '/bin/console') ? 'Symfony App' : 'Unknown',
                'created' => date("d.m.Y H:i", filectime($path)),
            ];
        }

        return $projects;
    }
}
