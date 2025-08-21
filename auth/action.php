<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Login
if (isset($_POST['login'])) {
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $errors = [];

        $email = $_POST['email'];
        $password = $_POST['password'];

        // Validasi email
        if (empty($email)) {
            $errors['email'] = 'Silakan masukkan email Anda.';
        }

        // Validasi password
        if (empty($password)) {
            $errors['password'] = 'Silakan masukkan kata sandi Anda.';
        }

        if (empty($errors)) {
            // Cek email
            $query = $connection->query("SELECT * FROM users WHERE email='$email'");
            $result = mysqli_fetch_array($query);

            if ($result) {
                // Verifikasi password
                if (password_verify($password, $result['password'])) {
                    // Cek status
                    if ($result['status'] === 'Aktif') {
                        $_SESSION['user_id'] = $result['id'];
                        $_SESSION['fullname'] = $result['fullname'];
                        $_SESSION['email'] = $result['email'];
                        $_SESSION['role'] = $result['role'];
                        $_SESSION['status'] = $result['status'];

                        // Redirect berdasarkan role pengguna
                        if ($_SESSION['role'] === 'Admin') {
                            $_SESSION['popup-success'] = "Selamat Datang " . $_SESSION['fullname'];
                            echo "<script>window.location.href = '" . base_url('dashboard/index.php') . "';</script>";
                        } elseif ($_SESSION['role'] === 'Pengguna') {
                            $_SESSION['popup-success'] = "Selamat Datang " . $_SESSION['fullname'];

                            // Check apakah sebelumnya login melalui halaman konsultasi atau tidak
                            if (isset($_SESSION['page']) && $_SESSION['page'] === 'Konsultasi') {
                                echo "<script>window.location.href = '" . base_url('user/consultation.php') . "';</script>";
                                unset($_SESSION['page']); // unset setelah redirect ke halaman konsultasi
                            } else {
                                echo "<script>window.location.href = '" . base_url() . "';</script>";
                            }
                        } else {
                            $_SESSION['error'] = "Email atau Kata Sandi salah.";
                        }
                        exit();
                    } else {
                        $_SESSION['error'] = "Akun Anda tidak aktif. Hubungi administrator.";
                    }
                } else {
                    $_SESSION['error'] = "Email atau Kata Sandi salah.";
                }
            } else {
                $_SESSION['error'] = "Email atau Kata Sandi salah.";
            }
        } else {
            $_SESSION['errors'] = $errors;
        }

        // Jika ada kesalahan, tetap di halaman login
        echo "<script>window.location.href = '" . base_url('auth/login.php') . "';</script>";
        exit();
    }
}

// Register
if (isset($_POST['register'])) {
    $errors = [];
    $form_data = [];

    // Mengambil data dari form
    $email              = htmlspecialchars($_POST['email']);
    $password           = $_POST['password'];
    $password_confirm   = $_POST['password_confirm'];
    $fullname           = htmlspecialchars($_POST['fullname']);
    $role               = 'Pengguna';
    $status             = 'Aktif';

    // Validasi email
    if (empty($email)) {
        $errors['email'] = 'Email wajib diisi.';
    } else {
        // Cek apakah email sudah terdaftar
        $check_email = $connection->query("SELECT * FROM users WHERE email='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $errors['email'] = 'Email sudah terdaftar.';
        } else {
            $form_data['email'] = $email;
        }
    }

    // Validasi password
    if (empty($password)) {
        $errors['password'] = 'Kata sandi wajib diisi.';
    } elseif (strlen($password) < 8) {
        $errors['password'] = 'Kata sandi minimal 8 karakter.';
    } else {
        // Validasi konfirmasi kata sandi
        if ($password !== $password_confirm) {
            $errors['password_confirm'] = 'Konfirmasi kata sandi tidak cocok.';
        }
    }

    // Validasi nama lengkap
    if (empty($fullname)) {
        $errors['fullname'] = 'Nama lengkap wajib diisi.';
    } else {
        $form_data['fullname'] = $fullname;
    }
    // Jika tidak ada error, lakukan pendaftaran
    if (empty($errors)) {
        // Hashing password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Query insert pengguna baru
        $query = "INSERT INTO users (id, email, password, fullname, role, status, phone) 
                         VALUES (NULL, '$email', '$hashed_password', '$fullname', '$role', '$status', NULL)";

        if ($connection->query($query)) {
            $_SESSION['success'] = "Pendaftaran berhasil, silakan masuk.";
            echo "<script>window.location.href = '" . base_url('auth/login.php') . "';</script>";
        } else {
            $_SESSION['error'] = 'Terjadi kesalahan saat mendaftar, silakan coba lagi.';
            echo "<script>window.location.href = '" . base_url('auth/register.php') . "';</script>";
        }
        exit();
    } else {
        // Simpan error dan form_data dalam session
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $form_data;

        // Redirect ke halaman register dengan mengirim error
        echo "<script>window.location.href = '" . base_url('auth/register.php') . "';</script>";
        exit();
    }
}

// Create admin account
// Hanya muncul jika tidak ada admin di tabel user
if (isset($_POST['create-admin-account'])) {
    $email              = "admin@gmail.com";
    $password           = "admin";
    $hashed_password    = password_hash($password, PASSWORD_DEFAULT);
    $fullname           = "Administrator";
    $role               = "Admin";
    $status             = "Aktif";
    $phone              = NULL;

    $create_account = $connection->query("INSERT INTO users VALUES(NULL, '$email', '$hashed_password', '$fullname', '$role', '$status', '$phone')");

    if ($create_account) {
        $_SESSION['success'] = "<small>Akun berhasil dibuat, silakan masuk dengan email '$email' dan kata sandi '$password'.</small>";
    } else {
        $_SESSION['error'] = "Akun gagal dibuat, silakan coba kembali!";
    }

    echo "<script>window.location.href = '" . base_url('auth/login.php') . "';</script>";
    exit();
}

// Cek auth
if ((isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') || (isset($_SESSION['role']) && $_SESSION['role'] === 'Pengguna')) {
    // Update profile
    if (isset($_GET['update-profile'])) {
        $errors = [];

        $user_id                = htmlspecialchars($_GET['update-profile']);
        $email                  = htmlspecialchars($_POST['email']);
        $password               = $_POST['password']; // Password lama
        $new_password           = $_POST['new_password']; // Kata sandi baru
        $new_password_confirm   = $_POST['new_password_confirm']; // Konfirmasi kata sandi baru
        $fullname               = htmlspecialchars($_POST['fullname']);
        $phone                  = htmlspecialchars($_POST['phone']);

        // Validasi email
        if (empty($email)) {
            $errors['email'] = 'Email wajib diisi.';
        } else {
            // Cek email duplikat selain pengguna yang sedang di-update
            $check_email = $connection->query("SELECT email FROM users WHERE email = '$email' AND id != '$user_id'");
            if (mysqli_num_rows($check_email) > 0) {
                $errors['email'] = 'Email sudah terdaftar.';
            }
        }

        // Validasi nama lengkap
        if (empty($fullname)) {
            $errors['fullname'] = 'Nama lengkap wajib diisi.';
        }

        // Validasi nomor HP
        if (!empty($phone) && !is_numeric($phone)) {
            $errors['phone'] = 'Nomor HP harus berupa angka.';
        }

        // Validasi kata sandi baru jika kata sandi lama diisi
        if (!empty($password)) {
            // Cek apakah kata sandi lama benar
            $query = $connection->query("SELECT password FROM users WHERE id = '$user_id'");
            $result = mysqli_fetch_assoc($query);

            if (!$result || !password_verify($password, $result['password'])) {
                $errors['password'] = 'Kata sandi lama salah.';
            } else {
                // Validasi kata sandi baru
                if (empty($new_password)) {
                    $errors['new_password'] = 'Kata sandi baru wajib diisi.';
                } elseif (strlen($new_password) < 8) {
                    $errors['new_password'] = 'Kata sandi baru minimal 8 karakter.';
                } elseif ($new_password !== $new_password_confirm) {
                    $errors['new_password_confirm'] = 'Konfirmasi kata sandi baru tidak cocok.';
                }
            }
        }

        // Jika tidak ada error, lakukan update
        if (empty($errors)) {
            // Set value untuk nomor HP
            if (!empty($phone) && is_numeric($phone)) {
                $format_phone = '+62' . $phone;
            } else {
                $format_phone = NULL;
            }

            // Update profil pengguna
            $update_query = "UPDATE users SET email = '$email', fullname = '$fullname', phone = '$format_phone'";

            // Jika kata sandi baru diisi, update kata sandi
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query .= ", password = '$hashed_password'";
            }

            $update_query .= " WHERE id = '$user_id'";
            $connection->query($update_query);

            // Perbarui session
            $_SESSION['email'] = $email;
            $_SESSION['fullname'] = $fullname;

            $_SESSION['success'] = "Profil berhasil diperbarui";

            // Redirect berdasarkan role pengguna
            if ($_SESSION['role'] === 'Admin') {
                echo "<script>window.location.href = '" . base_url('dashboard/manage-user/profile.php') . "';</script>";
            } else {
                echo "<script>window.location.href = '" . base_url('user/profile.php') . "';</script>";
            }
            exit();
        } else {
            $_SESSION['errors'] = $errors;

            // Redirect berdasarkan role pengguna
            if ($_SESSION['role'] === 'Admin') {
                echo "<script>window.location.href = '" . base_url('dashboard/manage-user/profile.php') . "';</script>";
            } else {
                echo "<script>window.location.href = '" . base_url('user/profile.php') . "';</script>";
            }
            exit();
        }
    }
}
