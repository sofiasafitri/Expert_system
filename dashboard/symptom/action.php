<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

// Create
if (isset($_POST['create-symptom'])) {
    $errors = [];
    $form_data = [];

    $symptom_name = htmlspecialchars($_POST['symptom_name']);

    // Validasi nama gejala
    if (empty($symptom_name)) {
        $errors['symptom_name'] = 'Nama gejala wajib diisi.';
    } else {
        // Cek apakah nama gejala sudah ada
        $check_symptom_name = $connection->query("SELECT symptom_name FROM symptoms WHERE symptom_name = '$symptom_name'");
        if (mysqli_num_rows($check_symptom_name) > 0) {
            $errors['symptom_name'] = 'Nama gejala sudah ada.';
        } else {
            $form_data['symptom_name'] = $symptom_name;
        }
    }

    // Jika tidak ada error
    if (empty($errors)) {
        // Query insert gejala baru
        $query = "INSERT INTO symptoms (id, symptom_name) VALUES (NULL, '$symptom_name')";

        $create = $connection->query($query);

        if ($create) {
            $_SESSION['success'] = "Gejala berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Gejala gagal ditambahkan.";
        }

        // Redirect pengguna
        echo "<script>window.location.href = '" . base_url('dashboard/symptom/show.php') . "';</script>";
        exit();
    } else {
        // Simpan error dan form_data dalam session
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $form_data;

        echo "<script>window.location.href = '" . base_url('dashboard/symptom/create.php') . "';</script>";
        exit();
    }
}

// Update
if (isset($_POST['update-symptom'])) {
    $errors = [];

    $symptom_id = htmlspecialchars($_POST['symptom_id']);
    $symptom_name = htmlspecialchars($_POST['symptom_name']);

    // Validasi nama gejala
    if (empty($symptom_name)) {
        $errors['symptom_name'] = 'Nama gejala wajib diisi.';
    } else {
        // Cek apakah nama gejala sudah ada
        $check_symptom_name = $connection->query("SELECT symptom_name FROM symptoms WHERE id != '$symptom_id' AND symptom_name = '$symptom_name'");
        if (mysqli_num_rows($check_symptom_name) > 0) {
            $errors['symptom_name'] = 'Nama gejala sudah ada.';
        } else {
            $form_data['symptom_name'] = $symptom_name;
        }
    }

    // Jika tidak ada error, lakukan update
    if (empty($errors)) {
        // Update gejala
        $query = "UPDATE symptoms SET symptom_name='$symptom_name' WHERE id='$symptom_id'";

        $update = $connection->query($query);

        if ($update) {
            $_SESSION['success'] = "Gejala berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Gejala gagal diperbarui.";
        }

        // Redirect pengguna
        echo "<script>window.location.href = '" . base_url('dashboard/symptom/show.php') . "';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;

        echo "<script>window.location.href = '" . base_url('dashboard/symptom/edit.php?symptom-id=' . $symptom_id) . "';</script>";
        exit();
    }
}

// Delete
if (isset($_GET['delete-symptom'])) {
    $symptom_id = htmlspecialchars($_GET['delete-symptom']);

    // Cek data
    $check_data = $connection->query("SELECT id FROM symptoms WHERE id = '$symptom_id'");

    if (mysqli_num_rows($check_data) > 0) {
        // Delete
        $query = $connection->query("DELETE FROM symptoms WHERE id = '$symptom_id'");

        if ($query) {
            $_SESSION['success'] = "Gejala berhasil dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/symptom/show.php') . "';</script>";
        } else {
            $_SESSION['error'] = "Gejala gagal dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/symptom/show.php') . "';</script>";
        }
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan.";
        echo "<script>window.location.href = '" . base_url('dashboard/symptom/show.php') . "';</script>";
    }
    exit();
}
