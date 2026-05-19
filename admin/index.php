<?php 
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit;
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
                        <h3>Daftar Postingan</h3>
                        <a href="create.php">+ Buat Postingan Baru</a>
                    </ul>
                    <?php 
                    include "../koneksi.php";
                    
                    $query = mysqli_query($koneksi, "SELECT * FROM articles");
                    
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
                                <li><a href="edit.php?id=<?php echo $data['id'] ?>" id="edit">Edit</a></li>
                                <li><a href="delete.php?id=<?php echo $data['id'] ?>" id="hapus">Hapus</a></li>
                                <li><a href="draf.php?id=<?php echo $data['id'] ?>" id="draf">Draf</a></li>
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