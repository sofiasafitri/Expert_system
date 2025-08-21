<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

// Create
if (isset($_POST['create-kb'])) {
    $errors = [];
    $form_data = [];

    $disease_id = isset($_POST['disease_id']) ? htmlspecialchars($_POST['disease_id']) : NULL;
    $symptom_id = isset($_POST['symptom_id']) ? htmlspecialchars($_POST['symptom_id']) : NULL;
    $mb_value = isset($_POST['mb_value']) ? htmlspecialchars($_POST['mb_value']) : 0;
    $md_value = isset($_POST['md_value']) ? htmlspecialchars($_POST['md_value']) : 0;

    // Validasi penyakit
    if (empty($disease_id)) {
        $errors['disease_id'] = 'Panyakit wajib dipilih.';
    } else {
        $form_data['disease_id'] = $disease_id;
    }

    // Validasi gejala
    if (empty($symptom_id)) {
        $errors['symptom_id'] = 'Panyakit wajib dipilih.';
    } else {
        $form_data['symptom_id'] = $symptom_id;
    }

    // Validasi nilai MB
    if (empty($mb_value)) {
        $errors['mb_value'] = 'Nilai MB wajib dipilih.';
    } else {
        $form_data['mb_value'] = $mb_value;
    }

    // Validasi nilai MB
    if (empty($md_value)) {
        $errors['md_value'] = 'Nilai MD wajib dipilih.';
    } else {
        $form_data['md_value'] = $md_value;
    }

    // Jika tidak ada error
    if (empty($errors)) {
        // Query insert
        $query = "INSERT INTO knowledge_bases (id, disease_id, symptom_id, mb_value, md_value) VALUES (NULL, '$disease_id', '$symptom_id', '$mb_value', '$md_value')";

        $create = $connection->query($query);

        if ($create) {
            $_SESSION['success'] = "Basis pengetahuan berhasil ditambahkan.";
        } else {
            $_SESSION['error'] = "Basis pengetahuan gagal ditambahkan.";
        }

        // Redirect pengguna
        echo "<script>window.location.href = '" . base_url('dashboard/knowledge-base/show.php') . "';</script>";
        exit();
    } else {
        // Simpan error dan form_data dalam session
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $form_data;

        echo "<script>window.location.href = '" . base_url('dashboard/knowledge-base/create.php') . "';</script>";
        exit();
    }
}

// Update
if (isset($_POST['update-kb'])) {
    $errors = [];

    $kb_id = htmlspecialchars($_POST['kb_id']);
    $disease_id = isset($_POST['disease_id']) ? htmlspecialchars($_POST['disease_id']) : NULL;
    $symptom_id = isset($_POST['symptom_id']) ? htmlspecialchars($_POST['symptom_id']) : NULL;
    $mb_value = isset($_POST['mb_value']) ? htmlspecialchars($_POST['mb_value']) : 0;
    $md_value = isset($_POST['md_value']) ? htmlspecialchars($_POST['md_value']) : 0;

    // Validasi penyakit
    if (empty($disease_id)) {
        $errors['disease_id'] = 'Panyakit wajib dipilih.';
    }

    // Validasi gejala
    if (empty($symptom_id)) {
        $errors['symptom_id'] = 'Panyakit wajib dipilih.';
    }

    // Validasi nilai MB
    if (empty($mb_value)) {
        $errors['mb_value'] = 'Nilai MB wajib dipilih.';
    }

    // Validasi nilai MB
    if (empty($md_value)) {
        $errors['md_value'] = 'Nilai MD wajib dipilih.';
    }

    // Jika tidak ada error, lakukan update
    if (empty($errors)) {
        // Update
        $query = "UPDATE knowledge_bases SET 
                    disease_id='$disease_id', 
                    symptom_id='$symptom_id', 
                    mb_value='$mb_value', 
                    md_value='$md_value' 
                    WHERE id='$kb_id'";

        $update = $connection->query($query);

        if ($update) {
            $_SESSION['success'] = "Basis pengetahuan berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Basis pengetahuan gagal diperbarui.";
        }

        // Redirect pengguna
        echo "<script>window.location.href = '" . base_url('dashboard/knowledge-base/show.php') . "';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;

        echo "<script>window.location.href = '" . base_url('dashboard/knowledge-base/edit.php?kb-id=' . $kb_id) . "';</script>";
        exit();
    }
}

// Delete
if (isset($_GET['delete-kb'])) {
    $kb_id = htmlspecialchars($_GET['delete-kb']);

    // Cek data
    $check_data = $connection->query("SELECT id FROM knowledge_bases WHERE id = '$kb_id'");

    if (mysqli_num_rows($check_data) > 0) {
        // Delete
        $query = $connection->query("DELETE FROM knowledge_bases WHERE id = '$kb_id'");

        if ($query) {
            $_SESSION['success'] = "Basis pengetahuan berhasil dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/knowledge-base/show.php') . "';</script>";
        } else {
            $_SESSION['error'] = "Basis pengetahuan gagal dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/knowledge-base/show.php') . "';</script>";
        }
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan.";
        echo "<script>window.location.href = '" . base_url('dashboard/knowledge-base/show.php') . "';</script>";
    }

    exit();
}
