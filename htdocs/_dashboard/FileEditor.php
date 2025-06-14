<?php
namespace Dashboard;

class FileEditor {
    public static function handlePost(): void {
        $baseDir = realpath(dirname(__DIR__)); // Your htdocs root (e.g. /srv/www/htdocs)

        if (isset($_POST['filepath'], $_POST['content'])) {
            $filePath = $_POST['filepath'];
            $dirPath = dirname($filePath);
            $realDir = realpath($dirPath);

            if (!$realDir) {
                echo 'ERROR: Directory does not exist';
                return;
            }

            if (strpos($realDir, $baseDir) !== 0) {
                echo 'ERROR: Directory outside base';
                return;
            }

            if (!is_writable($realDir)) {
                echo 'ERROR: Directory not writable';
                return;
            }

            if (@file_put_contents($filePath, $_POST['content']) !== false) {
                echo 'OK';
            } else {
                echo 'ERROR: Cannot write to file';
            }
            return;
        }

        echo 'ERROR: Invalid POST parameters';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    \Dashboard\FileEditor::handlePost();
}
