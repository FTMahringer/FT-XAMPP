<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Local Dev Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/_dashboard/assets/style.css">
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
                        <input class="form-control w-auto search-bar" type="search" id="projectSearch" placeholder="Search Projects ..." onkeyup="filterProjects()">
                    </div>
                    <ul class="list-unstyled" id="projectList">
						<?php require_once('show_projects.php'); ?>
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
    </main>
</div>

<!-- Modal -->
<div class="modal fade" id="fileEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fileModalTitle">Edit File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cancel"></button>
            </div>
            <div class="modal-body">
                <textarea id="file-editor" class="form-control" rows="12"></textarea>
            </div>
            <div class="modal-footer">
                <button onclick="saveFile()" class="btn btn-success">✅ Save</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">❌ Cancel</button>
            </div>
        </div>
    </div>
</div>



<script>
    let currentFilePath = "";

	function showFileEditor(btn, path, defaultContent, title = "Edit File") {
		currentFilePath = path;
		document.getElementById("fileModalTitle").textContent = title;
		document.getElementById("file-editor").value = defaultContent;
		const modal = new bootstrap.Modal(document.getElementById('fileEditModal'));
		modal.show();
	}

	function saveFile() {
		const content = document.getElementById("file-editor").value;

		console.log("Saving to: ", currentFilePath); // Add this line!

		fetch('/_dashboard/FileEditor.php', {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams({
				filepath: currentFilePath,
				content: content
			})
		})
		.then(r => r.text())
		.then(result => {
			console.log(result); // See what the server really responds
			if (result === 'OK') {
				alert('File saved!');
				location.reload();
			} else {
				alert('Error saving file!\n' + result);
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
</html>
