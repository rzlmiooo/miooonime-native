<?php
    include "../koneksi.php";
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: auth/login.php");
        exit;
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

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $title = $_POST['title'];
        $category = $_POST['category'];
        $excerpt = $_POST['excerpt'];
        $content = $_POST['content'];
        $thumbnail = $_FILES['thumbnail'];

        $target_dir = "../uploads/";
        $nama_file = time() . "_" . basename($thumbnail["name"]);
        $upload = "../uploads/" . $nama_file;

        if (move_uploaded_file($thumbnail["tmp_name"], $upload)) {
            compressImageToMaxSize($upload, $upload, 3 * 1024 * 1024);
            $query = mysqli_query(
                $koneksi,
                "INSERT INTO articles (title, content, excerpt, category, thumbnail)
                VALUES ('$title', '$content', '$excerpt', '$category', '$upload')"
            );
            header("Location: index.php");
            exit();
        } else {
            die("Upload gagal");
        }
    }
 ?>

<!DOCTYPE html>
<html>
    <head>
        <title>Buat Post Baru</title>
        <link rel="stylesheet" href="create.css">
        <script>
            window.onload = () => {
                if (window.history.replaceState) {
                    window.history.replaceState({}, document.title, window.location.pathname)
                }
            }
        </script>
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
                <h1>Buat Postingan Baru</h1>
                <form method="post" class="container" enctype="multipart/form-data">
                    <li>
                        <label for="title">Judul</label>
                        <input type="text" name="title" value="Judul">
                    </li>
                    <li>
                        <label for="category">Kategori</label>
                        <select name="category">
                            <option value="series">Series</option>
                            <option value="movie">Movie</option>
                            <option value="manga">Manga</option>
                        </select>
                    </li>
                    <li>
                        <label for="excerpt">Ringkasan</label>
                        <input type="text" name="excerpt" value="Masukkan ringkasan artikel">
                    </li>
                    <li>
                        <label for="content">Paragraf Konten</label>
                        <input type="text" name="content" value="Masukkan isi artikel">
                    </li>
                    <li>
                        <label for="thumbnail">Thumbnail Artikel</label>
                        <input type="file" name="thumbnail" accept="image/*">
                    </li>
                    <input id="submit" type="submit" name="submit" value="Publish Artikel">
                </form>
            </section>
        </main>
    </body>
</html>