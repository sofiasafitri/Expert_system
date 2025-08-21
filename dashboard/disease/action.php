<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

// Create
if (isset($_POST['create-disease'])) {
    $errors = [];
    $form_data = [];

    $disease_name = htmlspecialchars($_POST['disease_name']);
    $description = mysqli_escape_string($connection, $_POST['description']); // Tanpa htmlspecialchars()

    // Validasi nama penyakit
    if (empty($disease_name)) {
        $errors['disease_name'] = 'Nama penyakit wajib diisi.';
    } else {
        // Cek apakah nama penyakit sudah ada
        $check_disease_name = $connection->query("SELECT disease_name FROM diseases WHERE disease_name = '$disease_name'");
        if (mysqli_num_rows($check_disease_name) > 0) {
            $errors['disease_name'] = 'Nama penyakit sudah ada.';
        } else {
            $form_data['disease_name'] = $disease_name;
        }
    }

    // Validasi deskripsi
    if (empty($description)) {
        $errors['description'] = 'Deskripsi & saran wajib diisi.';
    } else {
        $form_data['description'] = $description;
    }

    // Validasi gambar
    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $file = $_FILES['img'];
        $file_type = $file['type'];
        $file_size = $file['size'];
        $valid_extensions = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($file_type, $valid_extensions)) {
            $errors['img'] = 'Format gambar tidak valid. Hanya JPG, JPEG, PNG, dan WEBP yang diperbolehkan.';
        }

        if ($file_size > 2 * 1024 * 1024) {
            $errors['img'] = 'Ukuran gambar maksimal 2 MB.';
        }
    } else {
        $errors['img'] = 'Gambar wajib diunggah.';
    }

    // Jika tidak ada error
    if (empty($errors)) {
        // Upload gambar dengan nama yang dihasilkan oleh uniqid()
        $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION); // mendapatkan ekstensi file
        $random_img_name = uniqid() . '.' . $ext; // menghasilkan nama unik dengan ekstensi yang sama

        $upload_url = base_url('assets/img/disease/' . $random_img_name);

        // Path absolut untuk pengecekan file di server
        $img_path = $_SERVER['DOCUMENT_ROOT'] . parse_url($upload_url, PHP_URL_PATH);

        $upload = move_uploaded_file($_FILES['img']['tmp_name'], $img_path);

        if ($upload) {
            // Query insert
            $query = "INSERT INTO diseases (id, disease_name, description, img) VALUES (NULL, '$disease_name', '$description', '$random_img_name')";

            $create = $connection->query($query);

            if ($create) {
                $_SESSION['success'] = "Penyakit berhasil ditambahkan.";
            } else {
                $_SESSION['error'] = "Penyakit gagal ditambahkan.";
            }

            // Redirect pengguna
            echo "<script>window.location.href = '" . base_url('dashboard/disease/show.php') . "';</script>";
            exit();
        }
    } else {
        // Simpan error dan form_data dalam session
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $form_data;

        echo "<script>window.location.href = '" . base_url('dashboard/disease/create.php') . "';</script>";
        exit();
    }
}

// Update
if (isset($_POST['update-disease'])) {
    $errors = [];

    $disease_id = htmlspecialchars($_POST['disease_id']);
    $disease_name = htmlspecialchars($_POST['disease_name']);
    $description = mysqli_escape_string($connection, $_POST['description']); // Tanpa htmlspecialchars()

    // Validasi nama penyakit
    if (empty($disease_name)) {
        $errors['disease_name'] = 'Nama penyakit wajib diisi.';
    } else {
        // Cek apakah nama penyakit sudah ada
        $check_disease_name = $connection->query("SELECT disease_name FROM diseases WHERE id != '$disease_id' AND disease_name = '$disease_name'");
        if (mysqli_num_rows($check_disease_name) > 0) {
            $errors['disease_name'] = 'Nama penyakit sudah ada.';
        } else {
            $form_data['disease_name'] = $disease_name;
        }
    }

    // Validasi deskripsi
    if (empty($description)) {
        $errors['description'] = 'Deskripsi & saran wajib diisi.';
    } else {
        $form_data['description'] = $description;
    }


    // Validasi gambar
    // Ambil data gambar lama
    $img_check = $connection->query("SELECT img FROM diseases WHERE id = '$disease_id'");
    $result = mysqli_fetch_assoc($img_check);
    $old_img = $result['img'];

    if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
        $file = $_FILES['img'];
        $file_type = $file['type'];
        $file_size = $file['size'];
        $valid_extensions = ['image/jpg', 'image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($file_type, $valid_extensions)) {
            $errors['img'] = 'Format gambar tidak valid. Hanya JPG, JPEG, PNG, dan WEBP yang diperbolehkan.';
        }

        if ($file_size > 2 * 1024 * 1024) {
            $errors['img'] = 'Ukuran gambar maksimal 2 MB.';
        }
    }

    // Jika tidak ada error, lakukan update
    if (empty($errors)) {
        $query = "UPDATE diseases SET 
                         disease_name = '$disease_name', 
                         description = '$description'";

        // Jika gambar baru diunggah, lakukan upload dan hapus gambar lama
        if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
            $ext = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
            $new_img = uniqid() . '.' . $ext;
            $new_img_url = base_url('assets/img/disease/' . $new_img);
            $new_img_path = $_SERVER['DOCUMENT_ROOT'] . parse_url($new_img_url, PHP_URL_PATH);

            $upload = move_uploaded_file($_FILES['img']['tmp_name'], $new_img_path);

            if ($upload) {
                // Hapus gambar lama jika ada
                if (!empty($old_img)) {
                    $old_img_url = base_url('assets/img/disease/' . $old_img);
                    $old_img_path = $_SERVER['DOCUMENT_ROOT'] . parse_url($old_img_url, PHP_URL_PATH);
                    if (file_exists($old_img_path)) {
                        unlink($old_img_path);
                    }
                }
                // Simpan gambar baru ke database
                $query .= ", img = '$new_img'";
            } else {
                $_SESSION['error'] = 'Gagal mengunggah gambar baru.';
                echo "<script>window.location.href = '" . base_url('dashboard/disease/edit.php?disease-id=' . $disease_id) . "';</script>";
                exit();
            }
        }

        $query .= " WHERE id = '$disease_id'";
        $update = $connection->query($query);

        if ($update) {
            $_SESSION['success'] = 'Penyakit berhasil diperbarui.';
        } else {
            $_SESSION['error'] = 'Penyakit gagal diperbarui.';
        }

        echo "<script>window.location.href = '" . base_url('dashboard/disease/show.php') . "';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;

        echo "<script>window.location.href = '" . base_url('dashboard/disease/edit.php?disease-id=' . $disease_id) . "';</script>";
        exit();
    }
}

// Delete
if (isset($_GET['delete-disease'])) {
    $disease_id = htmlspecialchars($_GET['delete-disease']);

    // Cek data
    $check_data = $connection->query("SELECT id, img FROM diseases WHERE id = '$disease_id'");

    if (mysqli_num_rows($check_data) > 0) {
        // Ambil data gambar lama dari database
        $result = mysqli_fetch_assoc($check_data);
        $img = $result['img'] ?? '';

        // Hapus gambar jika ada
        if (!empty($img)) {
            $img_url = base_url('assets/img/disease/' . $img);
            $img_path = $_SERVER['DOCUMENT_ROOT'] . parse_url($img_url, PHP_URL_PATH);
            if (file_exists($img_path)) {
                unlink($img_path);
            }
        }

        // Delete
        $query = $connection->query("DELETE FROM diseases WHERE id = '$disease_id'");

        if ($query) {
            $_SESSION['success'] = "Penyakit berhasil dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/disease/show.php') . "';</script>";
            exit();
        } else {
            $_SESSION['error'] = "Penyakit gagal dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/disease/show.php') . "';</script>";
            exit();
        }
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan.";
        echo "<script>window.location.href = '" . base_url('dashboard/symptom/show.php') . "';</script>";
        exit();
    }
}
