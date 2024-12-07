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

// Ambil ID artikel dari parameter URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Query untuk mendapatkan artikel berdasarkan ID
$query = "SELECT * FROM articles WHERE id = :id";
$statement = $pdo->prepare($query);
$statement->bindValue(':id', $id, PDO::PARAM_INT);
$statement->execute();
$article = $statement->fetch(PDO::FETCH_ASSOC);

// Jika artikel tidak ditemukan
if (!$article) {
    die("Artikel tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($article['title']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1><?= htmlspecialchars($article['title']); ?></h1>
        <p><small>Ditulis pada: <?= htmlspecialchars($article['created_at']); ?></small></p>
        <p><?= nl2br(htmlspecialchars($article['content'])); ?></p>
        <a href="index.php" class="btn btn-secondary">Kembali ke Beranda</a>
    </div>
</body>
</html>
