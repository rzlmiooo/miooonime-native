<?php
include "../koneksi.php";

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid!");
}

$id = $_GET['id'];

$res = mysqli_query($koneksi, "SELECT * FROM articles WHERE id = $id");
$data = mysqli_fetch_assoc($res);

if (!$data) {
    die("Artikel tidak ditemukan!");
}

function compressImageToMaxSize($sourcePath, $destPath, $maxSize = 3145728) {
    $info = getimagesize($sourcePath);
    if (!$info) return false;

    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($sourcePath);
            break;
        case 'image/png':
            $image = imagecreatefrompng($sourcePath);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }

    $quality = 90;

    do {
        imagejpeg($image, $destPath, $quality);
        $quality -= 5;
    } while (filesize($destPath) > $maxSize && $quality > 30);

    imagedestroy($image);
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $excerpt = $_POST['excerpt'];
    $content = $_POST['content'];

    $thumbnail = $data['thumbnail'];

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['size'] > 0) {

        $file = $_FILES['thumbnail'];
        $newName = time() . "_" . basename($file['name']);
        $target = "../uploads/" . $newName;
    
        if (move_uploaded_file($file['tmp_name'], $target)) {
            compressImageToMaxSize($target, $target, 3 * 1024 * 1024);
    
            $thumbnail = $newName;
    
        } else {
            die("Gagal upload file!");
        }
    }    

    $sql = "
        UPDATE articles SET 
            title='$title',
            category='$category',
            excerpt='$excerpt',
            content='$content',
            thumbnail='$thumbnail'
        WHERE id=$id
    ";

    $query = mysqli_query($koneksi, $sql);

    if (!$query) {
        die("SQL Error: " . mysqli_error($koneksi));
    }

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Edit Post</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <main>
            <aside>
                <h1>Miooonime</h1>
                <h3>Admin</h3>
                <li><a href="./">Dashboard</a></li>
                <li><a href="create.php">Buat artikel baru</a></li>
                <a href="../logout.php" id="logout">logout</a>
            </aside>

            <section>
                <h1>Edit: <?php echo $data['title']; ?></h1>

                <form method="post" enctype="multipart/form-data" class="container">

                    <li class="form-group">
                        <label>Judul</label>
                        <input type="text" name="title" value="<?php echo $data['title']; ?>" required>
                    </li>

                    <li class="form-group">
                        <label>Kategori</label>
                        <select name="category">
                            <option value="series" <?= $data['category']=='series'?'selected':'' ?>>Series</option>
                            <option value="movie" <?= $data['category']=='movie'?'selected':'' ?>>Movie</option>
                            <option value="manga" <?= $data['category']=='manga'?'selected':'' ?>>Manga</option>
                        </select>
                    </li>

                    <li class="form-group">
                        <label>Ringkasan</label>
                        <input type="text" name="excerpt" value="<?php echo $data['excerpt']; ?>">
                    </li>

                    <li class="form-group">
                        <label>Konten</label>
                        <textarea name="content" rows="5"><?php echo $data['content']; ?></textarea>
                    </li>

                    <li class="form-group">
                        <label>Thumbnail Baru (opsional)</label>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*">
                        <br>
                        <small>Thumbnail sekarang: <?php echo $data['thumbnail']; ?></small>
                    </li>

                    <input id="submit" type="submit" value="Simpan Perubahan">
                </form>
            </section>
        </main>
    </body>
</html>