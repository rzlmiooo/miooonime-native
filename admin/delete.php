<?php
    include "../koneksi.php";

    session_start();

    if (!isset($_SESSION['user_id'])) {
        header("Location: auth/login.php");
        exit;
    }

    if (isset($_POST['delete'])) {
        mysqli_query($koneksi, "DELETE FROM articles WHERE id=" . intval($_POST['id']));
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Miooonime - Admin</title>
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
                <h1>Dashboard</h1>
                <div class="container">
                    <ul>
                        <h3>Apakah Anda yakin ingin menghapus artikel berikut?</h3>
                    </ul>
                    <?php 
                    include "../koneksi.php";
                    
                    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
                        die("ID tidak valid!");
                    }
                    
                    $id = $_GET['id'];

                    $query = mysqli_query($koneksi, "SELECT * FROM articles WHERE id=$id");
                    
                    while($data = mysqli_fetch_array($query)) {
                        ?>
                        
                    <div class="item">
                        <img src="../uploads/<?php echo $data['thumbnail'] ?>" alt="<?php echo $data['thumbnail'] ?>">
                        <div class="title">
                            <p>Judul</p>
                            <h3><?php echo $data['title'] ?></h3>
                        </div>
                        <div class="link">
                            <p>Preview Halaman</p>
                            <a href="../article.php?id=<?php echo $data['id'] ?>">Kunjungi Laman</a>
                        </div>
                        <div class="time">
                            <p>Waktu Posting</p>
                            <h3><?php echo $data['published_at'] ?></h3>
                        </div>
                        <div class="aksi">
                            <p>Aksi</p>
                            <ul>
                                <li>
                                    <form method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                                        <button name="delete">Hapus</button>
                                    </form>
                                </li>    
                            </ul>
                        </div>
                    </div>

                    <?php 
                    } 
                    ?>
                </div>
            </section>
        </main>
    </body>
</html>