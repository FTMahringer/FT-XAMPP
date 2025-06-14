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
			$envPath = $path . '/.env';
            $publicIndex = $path . '/public/index.php';
            $rootIndex = $path . '/index.php';

            $ignoredFolders = ['.git'];
            $ignoredFoldersStartsWith = ['.', '_'];

            // Skip if the folder is ignored
            if (in_array($folder, $ignoredFolders) ||
                array_filter($ignoredFoldersStartsWith, fn($prefix) => strpos($folder, $prefix) === 0)) {
                continue;
            }
		
			$indexPath = null;
			$custom = null;
			if (file_exists($path . '/public/index.php')) {
				$indexPath = $path . '/public/index.php';
			} elseif (file_exists($path . '/index.php')) {
				$indexPath = $path . '/index.php';
			} elseif (file_exists($path . '/.dashboardpath')) {
				$custom = trim(file_get_contents($path . '/.dashboardpath'));
				if (file_exists($path . '/' . $custom . '/index.php')) {
					$indexPath = $path . '/' . $custom . '/index.php';
				}
			} elseif (file_exists($envPath)) {
				$envLines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				foreach ($envLines as $line) {
					if (strpos($line, 'indexpath=') === 0) {
						$custom = trim(substr($line, strlen('indexpath=')));
						if (file_exists($path . '/' . $custom . '/index.php')) {
							$indexPath = $path . '/' . $custom . '/index.php';
						}
						break;
					}
				}
			}

            $htaccessPath = $indexPath ? dirname($indexPath) . '/.htaccess' : null;

            $projects[] = [
                'name' => $folder,
                'index' => $indexPath,
				'path' => $path,
                'htaccessExists' => $htaccessPath && file_exists($htaccessPath),
                'htaccessPath' => $htaccessPath,
                'type' => file_exists($path . '/bin/console') ? 'Symfony App' : 'Unknown',
                'created' => date("d.m.Y H:i", filectime($path)),
				'envMissing' => !file_exists($envPath),
				'custom' => $custom,
            ];
        }

        return $projects;
    }
}
