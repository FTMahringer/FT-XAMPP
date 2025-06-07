<?php
$dir = __DIR__;
$defaultHtaccessPath = $dir . '/.htaccess';
$folders = array_filter(scandir($dir), function($item) use ($dir) {
    $fullPath = $dir . DIRECTORY_SEPARATOR . $item;
    return $item !== '.' && $item !== '..' && $item !== '.assets' && is_dir($fullPath);
});

function findIndexAndHtaccess(string $folderPath): array {
    $publicPath = $folderPath . '/public';
    $indexPath = is_file($publicPath . '/index.php') ? $publicPath . '/index.php' : (
        is_file($folderPath . '/index.php') ? $folderPath . '/index.php' : null
    );

    $htaccessPath = $indexPath ? dirname($indexPath) . '/.htaccess' : null;
    return [
        'index' => $indexPath,
        'htaccessExists' => $htaccessPath && file_exists($htaccessPath),
        'htaccessPath' => $htaccessPath
    ];
}

function getProjectInfo($folderPath): array {
    return [
        'type' => file_exists($folderPath . '/bin/console') ? 'Symfony App' : 'Unknown',
        'created' => date("d.m.Y H:i", filectime($folderPath)),
    ];
}

// Handle AJAX request to create .htaccess
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['path'], $_POST['content'])) {
    $targetPath = realpath($_POST['path']);
    $htaccessPath = $targetPath . '/.htaccess';

    if (strpos($htaccessPath, $dir) === 0) {
        file_put_contents($htaccessPath, $_POST['content']);
        echo 'OK';
        exit;
    }
    echo 'ERROR';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Local Dev Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="/style.css">
</head>
<body>
  <div class="container-fluid">
    <header>
      <h1><i class="bi bi-rocket"></i> My Local Dev Dashboard</h1>
      <button class="btn-toggle-theme" onclick="toggleTheme()" title="Toggle dark mode">
        <i class="bi bi-moon-fill"></i>
      </button>
    </header>

    <main class="container">
      <div class="row g-4 flex-column-mobile">
        <div class="col-lg-8">
          <div class="dashboard-column projects-column">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
              <h2><i class="bi bi-folder"></i> Projects</h2>
              <input class="form-control w-auto search-bar" type="search" id="projectSearch" placeholder="Seach Projects ..." onkeyup="filterProjects()">
            </div>
            <ul class="list-unstyled" id="projectList">
            <?php foreach ($folders as $folder): 
              $projectPath = $dir . '/' . $folder;
              $info = getProjectInfo($projectPath);
              $paths = findIndexAndHtaccess($projectPath);
              $link = $paths['index'] ? str_replace($dir, '', dirname($paths['index'])) . '/' : '#';
            ?>
              <li class="mb-3 p-3 rounded shadow-sm bg-white">
                <a class="project-link text-decoration-none fw-bold text-primary fs-5" href="<?= $link ?>">📁 <?= $folder ?></a>
                <div class="project-meta d-flex gap-2 flex-wrap small text-muted mt-1">
                  <span class="badge bg-light text-dark">🛠️ <?= $info['type'] ?></span>
                  <span class="badge bg-light text-dark">🕓 created at: <?= $info['created'] ?></span>
                </div>
                <?php if ($paths['index']): ?>
                  <?php if (!$paths['htaccessExists']): ?>
                    <div class="mt-2 text-danger fw-semibold">
                      ⚠️ .htaccess is missing<br>
                      <button class="btn btn-warning btn-sm mt-1"
                        onclick="showEditor(this, '<?= dirname($paths['index']) ?>', `<?= addslashes(file_get_contents($defaultHtaccessPath)) ?>`)">
                        + Add .htaccess
                      </button>
                    </div>
                  <?php else: ?>
                    <div class="mt-2 text-success fw-semibold">
                      ✅ .htaccess found
                    </div>
                  <?php endif; ?>
                <?php endif; ?>
              </li>
            <?php endforeach; ?>
		    </ul>
		</div>
	  </div>
      <div class="col-lg-4">
          <div class="dashboard-column services-column">
            <h2><i class="bi bi-tools"></i> Services</h2>
            <ul class="list-unstyled">
              <li class="mb-3 p-3 rounded shadow-sm bg-white">
                <a href="http://localhost:8080" target="_blank" class="fw-bold text-primary text-decoration-none">
                  <i class="bi bi-bar-chart"></i> PHPMyAdmin
                </a>
                <div class="d-flex gap-2 mt-2 small">
                  <span class="badge bg-light text-dark">🚪 Port: 8080</span>
                  <span class="badge bg-light text-dark">📦 MariaDB</span>
                </div>
              </li>
              <li class="mb-3 p-3 rounded shadow-sm bg-white">
                <a href="http://localhost:5540" target="_blank" class="fw-bold text-primary text-decoration-none">
                  <i class="bi bi-lightning"></i> Redis Insight
                </a>
                <div class="d-flex gap-2 mt-2 small">
                  <span class="badge bg-light text-dark">🚪 Port: 5540</span>
                  <span class="badge bg-light text-dark">⚡ Redis</span>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modal -->
  <div class="modal fade" id="htaccessModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">edit .htaccess</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Abbrechen"></button>
        </div>
        <div class="modal-body">
          <textarea id="htaccess-editor" class="form-control" rows="12"></textarea>
        </div>
        <div class="modal-footer">
          <button onclick="saveHtaccess()" class="btn btn-success">✅ Save</button>
          <button class="btn btn-secondary" data-bs-dismiss="modal">❌ Cancel</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    let currentHtaccessPath = "";

    function showEditor(btn, path, defaultContent) {
      currentHtaccessPath = path;
      document.getElementById("htaccess-editor").value = defaultContent;
      const modal = new bootstrap.Modal(document.getElementById('htaccessModal'));
      modal.show();
    }

    function saveHtaccess() {
      const content = document.getElementById("htaccess-editor").value;

      fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          path: currentHtaccessPath,
          content: content
        })
      }).then(r => r.text()).then(result => {
        if (result === 'OK') {
          alert('.htaccess erfolgreich hinzugefügt!');
          location.reload();
        } else {
          alert('Fehler beim Speichern!');
        }
      });
    }
	function toggleTheme() {
      const isDark = document.documentElement.getAttribute("data-theme") === "dark";
      document.documentElement.setAttribute("data-theme", isDark ? "light" : "dark");
      localStorage.setItem("theme", isDark ? "light" : "dark");
    }

    function loadTheme() {
      const savedTheme = localStorage.getItem("theme") || "dark";
      document.documentElement.setAttribute("data-theme", savedTheme);
    }

    function filterProjects() {
      const query = document.getElementById("projectSearch").value.toLowerCase();
      const items = document.querySelectorAll("#projectList > li");
      items.forEach(li => {
        li.style.display = li.textContent.toLowerCase().includes(query) ? "block" : "none";
      });
    }

    document.addEventListener("DOMContentLoaded", loadTheme);
  </script>
</body>

