<?php 
    include "koneksi.php";
    $query = mysqli_query($koneksi, "SELECT thumbnail FROM articles ORDER BY RAND() LIMIT 1");
    $data = mysqli_fetch_assoc($query);
    $thumbnail = $data['thumbnail'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="container">
        <h2>Miooonime - Admin</h2>
        <h3>Login</h3>

        <form action="login_process.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Username" required>
            </div>

            <div class="form-group">
                <label>Kata Sandi</label>
                <input type="password" name="password" placeholder="Password" required>
            </div>

            <button type="submit">Login</button>
            <p>atau</p>
            <a href="/">Kembali ke Beranda</a>
        </form>
    </div>
    <img src="uploads/<?php echo $thumbnail ?>" alt="<?php echo $thumbnail ?>">
</body>
</html>
