<?php
    // $koneksi = mysqli_connect("localhost", "root", "", "miooonime");
    // $koneksi = mysqli_connect("sql312.infinityfree.com", "if0_40860262", "Hento123", "if0_40737632_miooonime");
    // $koneksi = mysqli_connect("miooonime-miooonime.e.aivencloud.com", "avnadmin", "AVNS_vRDEfDx2mNRr6O3HT_K", "miooonime");
    // $koneksi = mysqli_connect("sql106.infinityfree.com", "if0_40737632", "Miooo123", "if0_40737632_miooonime");
    $dbHostName = "miooonime-miooonime.e.aivencloud.com";
    $dbUserName = "avnadmin";
    $dbPassword = "AVNS_vRDEfDx2mNRr6O3HT_K";
    $dataBaseName = "miooonime";
    $port = 15218;
    $ssl_ca_path = "ca.pem";

    $koneksi = mysqli_init();

    if (!$koneksi) {
        die("mysqli_init failed");
    }

    mysqli_ssl_set($koneksi, NULL, NULL, $ssl_ca_path, NULL, NULL);

    mysqli_options($koneksi, MYSQLI_OPT_SSL_VERIFY_SERVER_CERT, true);

    if (!mysqli_real_connect($koneksi, $dbHostName, $dbUserName, $dbPassword, $dataBaseName, $port, NULL, MYSQLI_CLIENT_SSL)) {
        die("Connect Error (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
    }
?>