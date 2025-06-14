<?php foreach ($projects as $project):
    $link = $project['index'] ? str_replace(dirname(__DIR__, 2), '', dirname($project['index'])) . '/' : '#';
?>
    <li class="mb-3 p-3 rounded shadow-sm bg-white">
        <a class="project-link text-decoration-none fw-bold text-primary fs-5"
           href="<?= $project['index'] ? $link : '#' ?>">📁 <?= htmlspecialchars($project['name']) ?></a>

        <div class="project-meta d-flex gap-2 flex-wrap small text-muted mt-1">
            <span class="badge bg-light text-dark">🛠️ <?= $project['type'] ?></span>
            <span class="badge bg-light text-dark">🕓 created at: <?= $project['created'] ?></span>
        </div>

        <?php if ($project['index']): ?>
            <?php if (!$project['htaccessExists']): ?>
                <div class="mt-2 text-danger fw-semibold">
                    ⚠️ .htaccess is missing<br>
                    <button class="btn btn-warning btn-sm mt-1"
							onclick="showFileEditor(this, '<?= $project['path'] ?>/.htaccess', `<?= addslashes(file_get_contents(__DIR__ . '/../default.htaccess')) ?>`, '.htaccess')">
						+ Add .htaccess
					</button>

                </div>
            <?php else: ?>
                <div class="mt-2 text-success fw-semibold">
                    ✅ .htaccess found
                </div>
            <?php endif; ?>
        <?php else: ?>
            <?php if ($project['envMissing']): ?>
				<div class="mt-2 text-danger fw-semibold">
					⚠️ Kein gültiger index.php gefunden und keine .env mit "indexpath"<br>
					<button class="btn btn-warning btn-sm mt-1"
						onclick="showFileEditor(this, '<?= $project['path'] ?>/.env', `<?= addslashes(file_get_contents(__DIR__ . '/../default.env')) ?>`, '.env')">
						+ Add .env
					</button>
				</div>
			<?php endif; ?>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
