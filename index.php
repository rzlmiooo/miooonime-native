<?php
include "koneksi.php";

$q = isset($_GET['q']) ? $_GET['q'] : '';
$result = null;

if ($q !== '') {
    $q_safe = mysqli_real_escape_string($koneksi, $q);

    $query = "
        SELECT *,
        MATCH(title, content) AGAINST ('$q_safe' IN NATURAL LANGUAGE MODE) AS score
        FROM articles
        WHERE MATCH(title, content)
        AGAINST ('$q_safe' IN NATURAL LANGUAGE MODE)
        ORDER BY score DESC
    ";
    $result = mysqli_query($koneksi, $query);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Miooonime - Review Anime Terlengkap</title>
        <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css') ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
        <header>
            <nav class="topbar">
                <li>
                    <a href="/">
                        <h1>Miooonime</h1>
                        <p>ミオオオにめ</p>
                        <small>Review Anime Terlengkap</small>
                    </a>
                </li>
                <form method="GET">
                    <div class="searchbar">
                        <input type="text" name="q" placeholder="&#128270; Cari anime..." 
                        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button type="submit">Cari</button>
                    </div>
                </form>
                <ul>
                    <li><a href="https://github.com/rzlmiooo" target="_blank"><img class="social" src="assets/github.svg" alt="github.svg"></a></li>
                    <li><a href="https://www.facebook.com/profile.php?id=100021594110029" target="_blank"><img class="social" src="assets/facebook.svg" alt="facebook.svg"></a></li>
                    <li><a href="https://www.instagram.com/rzl_miooo" target="_blank"><img class="social" src="assets/instagram.svg" alt="instagram.svg"></a></li>
                </ul>
            </nav>
            <nav class="tabs">
                <a href="" id="home">Beranda</a>
                <a href="series.php" id="series">Anime Series</a>
                <a href="movie.php" id="movie">Anime Movie</a>
                <a href="manga.php" id="manga">Manga</a>
            </nav>
        </header>
        <main>
            <?php if ($result): ?>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <article>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <a href="article.php?id=<?php echo $row['id'] ?>">
                                <img src="uploads/<?php echo $row['thumbnail'] ?>" alt="<?php echo $row['thumbnail'] ?>">
                                <li>
                                    <h3><?= htmlspecialchars($row['title']) ?></h3>
                                    <p><?= substr(strip_tags($row['content']), 0, 500) ?>...</p>
                                </li>
                            </a>
                        <?php endwhile; ?>
                        <a href="/">Tutup</a>
                    </article>
                <?php else: ?>
                    <div id="empty">
                        Hasil tidak ditemukan.
                        <a href="/">Tutup</a></div>
                <?php endif; ?>
            <?php endif; ?>
            <section>
                <?php 
                include "koneksi.php";
                
                $query = mysqli_query($koneksi, "SELECT * FROM articles");
                
                while($data = mysqli_fetch_array($query)) {
                ?>
                
                <a href="article.php?id=<?php echo $data['id'] ?>" class="card">
                    <img src="uploads/<?php echo $data['thumbnail'] ?>" alt="<?php echo $data['thumbnail'] ?>">
                    <div class="detail">
                        <h1><?php echo $data['title'] ?></h1>
                        <div class="rating">
                            <img src="assets/star.svg" alt="star">
                            <p><?php echo $data['rating'] ?></p>
                        </div>
                        <p class="category"><?php echo $data['category'] ?></p>
                        <p class="excerpt"><?php echo substr(strip_tags($data['content']), 0, 150) ?></p>
                    </div>
                </a>
                    
                <?php 
                } 
                ?>
            </section>
            <aside>
                <h3>Anime Movie Terbaru</h3>
                <?php 
                include "koneksi.php";
                
                $query = mysqli_query($koneksi, "SELECT * FROM articles");
                
                while($data = mysqli_fetch_array($query)) {
                ?>
                <a href="article.php?id=<?php echo $data['id'] ?>">

                    <img src="uploads/<?php echo $data['thumbnail'] ?>" alt="<?php echo $data['thumbnail'] ?>">
                    <p><?php echo $data['title'] ?></p>

                <?php 
                } 
                ?>
                </a>
            </aside>
        </main>
        <footer>
            <h6>
                (c) Miooonime - Web Review Anime Terlengkap
            </h6>
            <a href="admin/">Admin Page</a>
        </footer>
    </body>
</html>