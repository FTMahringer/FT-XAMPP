<?php
namespace Dashboard;

class HtaccessEditor {
    public static function handlePost(): void {
        $baseDir = dirname(__DIR__);

        if (isset($_POST['path'], $_POST['content'])) {
            $target = realpath($_POST['path']);
            $htaccessPath = $target . '/_dashboard/default.htaccess';

            if (strpos($htaccessPath, $baseDir) === 0) {
                file_put_contents($htaccessPath, $_POST['content']);
                echo 'OK';
                return;
            }
        }

        echo 'ERROR';
    }
}
