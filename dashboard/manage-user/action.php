<?php
// Menyertakan konfigurasi
include('../../config/config.php');

// Memulai sesi
session_start();

// Cek login dan role
isAdmin($connection);

// Create
if (isset($_POST['create-user'])) {
    $errors = [];
    $form_data = [];

    $email      = htmlspecialchars($_POST['email']);
    $fullname   = htmlspecialchars($_POST['fullname']);
    $role       = isset($_POST['role']) ? htmlspecialchars($_POST['role']) : '';
    $status     = isset($_POST['status']) ? htmlspecialchars($_POST['status']) : '';
    $phone      = htmlspecialchars($_POST['phone']);

    // Validasi email
    if (empty($email)) {
        $errors['email'] = 'Email wajib diisi.';
    } else {
        // Cek apakah email sudah terdaftar
        $check_email = $connection->query("SELECT email FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $errors['email'] = 'Email sudah terdaftar.';
        } else {
            $form_data['email'] = $email;
        }
    }

    // Validasi nama lengkap
    if (empty($fullname)) {
        $errors['fullname'] = 'Nama lengkap wajib diisi.';
    } else {
        $form_data['fullname'] = $fullname;
    }

    // Validasi nomor HP
    if (!empty($phone) && !is_numeric($phone)) {
        $errors['phone'] = 'Nomor HP harus berupa angka.';
    } else {
        $form_data['phone'] = $phone;
    }

    // Validasi role
    if (empty($role)) {
        $errors['role'] = 'Peran wajib dipilih.';
    } else {
        $form_data['role'] = $role;
    }

    // Validasi status
    if (empty($status)) {
        $errors['status'] = 'Status wajib dipilih.';
    } else {
        $form_data['status'] = $status;
    }

    // Validasi nomor hp
    if (!empty($phone) && !is_numeric($phone)) {
        $errors['phone'] = 'Nomor HP harus berupa angka.';
    } else {
        $form_data['phone'] = $phone;
    }

    // Jika tidak ada error
    if (empty($errors)) {
        // Gunakan password default bedasarkan role
        if ($role === 'Admin') {
            $password = 'admin';
            $message = "Pengguna berhasil ditambahkan dengan email <span class='badge bg-light'>$email</span> dan kata sandi <span class='badge bg-light'>$password</span>";
        } elseif ($role === 'Pakar') {
            $password = 'pakar';
            $message = "Pengguna berhasil ditambahkan.";
        }

        // Hashing password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Set value untuk nomor HP
        if (empty($phone)) {
            $format_phone = NULL;
        } else {
            $format_phone = '+62' . $phone;
        }

        // Query insert pengguna baru
        $query = "INSERT INTO users (id, email, password, fullname, role, status, phone) 
                VALUES (NULL, '$email', '$hashed_password', '$fullname', '$role', '$status', '$format_phone')";

        $create_user = $connection->query($query);

        if ($create_user) {
            $_SESSION['success'] = $message;
        } else {
            $_SESSION['error'] = "Pengguna gagal ditambahkan.";
        }

        // Redirect pengguna
        echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
        exit();
    } else {
        // Simpan error dan form_data dalam session
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $form_data;

        echo "<script>window.location.href = '" . base_url('dashboard/manage-user/create.php') . "';</script>";
        exit();
    }
}

// Update
if (isset($_POST['update-user'])) {
    $errors = [];

    $user_id    = htmlspecialchars($_POST['user_id']);
    $email      = htmlspecialchars($_POST['email']);
    $fullname   = htmlspecialchars($_POST['fullname']);
    $role       = isset($_POST['role']) ? htmlspecialchars($_POST['role']) : '';
    $status     = isset($_POST['status']) ? htmlspecialchars($_POST['status']) : '';
    $phone      = htmlspecialchars($_POST['phone']);


    // Validasi email
    if (empty($email)) {
        $errors['email'] = 'Email wajib diisi.';
    } else {
        // Cek email duplikat selain pengguna yang sedang di-update
        $check_email = $connection->query("SELECT email FROM users WHERE id != '$user_id' AND email = '$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $errors['email'] = 'Email sudah terdaftar.';
        }
    }

    // Validasi nama lengkap
    if (empty($fullname)) {
        $errors['fullname'] = 'Nama lengkap wajib diisi.';
    }

    // Validasi role
    if (empty($role)) {
        $errors['role'] = 'Role wajib dipilih.';
    }

    // Validasi status
    if (empty($status)) {
        $errors['status'] = 'Status wajib dipilih.';
    }

    // Validasi nomor hp
    if (!empty($phone) && !is_numeric($phone)) {
        $errors['phone'] = 'Nomor HP harus berupa angka.';
    }

    // Jika tidak ada error, lakukan update
    if (empty($errors)) {
        // Set value untuk nomor HP
        if (empty($phone)) {
            $format_phone = NULL;
        } else {
            $format_phone = '+62' . $phone;
        }

        // Update pengguna
        $query = "UPDATE users SET email='$email', fullname='$fullname', role='$role', status='$status', phone='$format_phone' WHERE id='$user_id'";

        $update_user = $connection->query($query);

        if ($update_user) {
            if ($user_id === $_SESSION['user_id']) {
                // Perbarui session
                $_SESSION['email'] = $email;
                $_SESSION['fullname'] = $fullname;
                $_SESSION['role'] = $role;
                $_SESSION['status'] = $status;
            }

            $_SESSION['success'] = "Pengguna berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Pengguna gagal diperbarui.";
        }

        // Redirect pengguna berdasarkan role
        echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
        exit();
    } else {
        $_SESSION['errors'] = $errors;

        echo "<script>window.location.href = '" . base_url('dashboard/manage-user/edit.php?user-id=' . $user_id) . "';</script>";
        exit();
    }
}

// Delete
if (isset($_GET['delete-user'])) {
    $user_id = htmlspecialchars($_GET['delete-user']);

    // Cek data
    $check_data = $connection->query("SELECT id FROM users WHERE id = '$user_id'");

    if (mysqli_num_rows($check_data) > 0) {
        // Delete
        $query = $connection->query("DELETE FROM users WHERE id = '$user_id'");

        if ($query) {
            if ($user_id === $_SESSION['user_id']) {
                // Logout
                echo "<script>window.location.href = '" . base_url('auth/logout.php') . "';</script>";
            } else {
                $_SESSION['success'] = "Pengguna berhasil dihapus.";
                echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
            }

            exit();
        } else {
            $_SESSION['error'] = "Pengguna gagal dihapus.";
            echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
        }
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan.";
        echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
    }
    exit();
}

// Reset password
if (isset($_GET['reset-password-user'])) {
    $user_id = htmlspecialchars($_GET['reset-password-user']);

    // Cek data
    $check_data = $connection->query("SELECT fullname, role FROM users WHERE id = '$user_id'");

    if (mysqli_num_rows($check_data) > 0) {
        $result = mysqli_fetch_assoc($check_data);
        $fullname = $result['fullname'];
        $role = $result['role'];

        // Gunakan password default bedasarkan role
        if ($role === 'Admin') {
            $password = 'admin';

            // Hashing password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "UPDATE users SET password='$hashed_password' WHERE id='$user_id'";

            $reset_password = $connection->query($query);

            if ($reset_password) {
                $_SESSION['success'] = "Kata sandi $fullname berhasil direset menjadi <span class='badge bg-light'>$password</span>";
                echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
                exit();
            } else {
                $_SESSION['error'] = "Kata sandi gagal direset.";
                echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
                exit();
            }
        } else {
            $_SESSION['error'] = "Terjadi kesalahan, silakan coba kembali.";
            echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
            exit();
        }
    } else {
        $_SESSION['warning'] = "Data tidak ditemukan.";
        echo "<script>window.location.href = '" . base_url('dashboard/manage-user/show.php') . "';</script>";
        exit();
    }
}
