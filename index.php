<?php
// Koneksi ke database
$host = 'localhost';
$dbname = 'ujian4';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Query untuk mendapatkan data artikel untuk proyek
$project_query = "SELECT * FROM articles WHERE id IN (1, 2)";
$project_statement = $pdo->prepare($project_query);
$project_statement->execute();
$projects = $project_statement->fetchAll(PDO::FETCH_ASSOC);

// Data proyek diurutkan
$project1 = $projects[0] ?? null;
$project2 = $projects[1] ?? null;

// Pagination untuk artikel blog (sama seperti sebelumnya)
$articles_per_page = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $articles_per_page;

$query = "SELECT * FROM articles ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$statement = $pdo->prepare($query);
$statement->bindValue(':limit', $articles_per_page, PDO::PARAM_INT);
$statement->bindValue(':offset', $offset, PDO::PARAM_INT);
$statement->execute();
$articles = $statement->fetchAll(PDO::FETCH_ASSOC);

$total_query = "SELECT COUNT(*) FROM articles";
$total_articles = $pdo->query($total_query)->fetchColumn();
$total_pages = ceil($total_articles / $articles_per_page);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portofolio & Blog Video Editing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-dark text-white text-center py-3">
        <h1>Portofolio Editing Video</h1>
        <p>Menjelajahi Dunia Kreativitas Visual</p>
    </header>

    <div class="container mt-4">
        <h2>Contoh Karya</h2>
        <div class="row">
            <?php if ($project1): ?>
                <div class="col-md-6 mb-4">
                    <h4>Proyek 1: <?= htmlspecialchars($project1['title']); ?></h4>
                    <iframe src="https://www.youtube.com/embed/5S8l9HfkCiU" allowfullscreen style="width:100%; height:300px;"></iframe>
                    <p>Deskripsi: <?= htmlspecialchars($project1['content']); ?></p>
                    <a href="detail.php?id=<?= $project1['id']; ?>" class="btn btn-primary">Baca Selengkapnya</a>
                </div>
            <?php endif; ?>

            <?php if ($project2): ?>
                <div class="col-md-6 mb-4">
                    <h4>Proyek 2: <?= htmlspecialchars($project2['title']); ?></h4>
                    <iframe src="https://www.youtube.com/embed/SR__amDl1c8" allowfullscreen style="width:100%; height:300px;"></iframe>
                    <p>Deskripsi: <?= htmlspecialchars($project2['content']); ?></p>
                    <a href="detail.php?id=<?= $project2['id']; ?>" class="btn btn-primary">Baca Selengkapnya</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="container mt-5">
        <h2>Artikel Blog</h2>
        <div class="row">
            <?php foreach ($articles as $article): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="detail.php?id=<?= $article['id']; ?>">
                                    <?= htmlspecialchars($article['title']); ?>
                                </a>
                            </h5>
                            <p class="card-text"><?= substr(htmlspecialchars($article['content']), 0, 100); ?>...</p>
                            <a href="detail.php?id=<?= $article['id']; ?>" class="btn btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <nav>
            <ul class="pagination">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?= $i == $page ? 'active' : ''; ?>">
                        <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>

    <footer class="bg-dark text-white text-center py-3 mt-4">
        &copy; 2024 Mahes - Semua Hak Dilindungi.
    </footer>
</body>
</html>
