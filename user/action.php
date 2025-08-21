<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Analisis menggunakan metode certainty factor
if (isset($_POST['send-consultation'])) {
    if (!isset($_SESSION['email'])) {
        $_SESSION['page'] = 'Konsultasi'; // Memudahkan pengguna redirect langsung ke halaman konsultasi setelah login
    }

    // Cek auth
    checkLogin($connection);

    $selected_conditions = $_POST['condition']; // Data gejala yang dipilih
    $user_id = $_SESSION['user_id']; // ID user (sesuaikan dengan sesi login)

    if (empty($selected_conditions)) {
        // echo "<script>alert('Silakan pilih setidaknya satu gejala!'); window.history.back();</script>";

        $_SESSION['popup-warning'] = "Silakan pilih setidaknya satu gejala!";
        echo "<script>window.location.href = '" . base_url('user/consultation.php') . "';</script>";
        exit;
    }

    // Ambil nilai kondisi dari database
    $conditions_query = "SELECT * FROM conditions";
    $conditions_result = $connection->query($conditions_query);
    $condition_values = [];
    while ($row = $conditions_result->fetch_assoc()) {
        $condition_values[$row['id']] = $row['condition_value'];
    }

    // Ambil gejala yang dipilih beserta nilai kondisi
    $symptoms = [];
    foreach ($selected_conditions as $symptom_id => $condition_id) {
        if ($condition_id != "") {
            $symptoms[$symptom_id] = $condition_values[$condition_id];
        }
    }

    if (empty($symptoms)) {
        // echo "<script>alert('Silakan pilih setidaknya satu gejala dengan kondisi valid!'); window.history.back();</script>";

        $_SESSION['popup-warning'] = "Silakan pilih setidaknya satu gejala dengan kondisi valid!";
        echo "<script>window.location.href = '" . base_url('user/consultation.php') . "';</script>";
        exit;
    }

    // Ambil basis pengetahuan
    $knowledge_query = "SELECT * FROM knowledge_bases";
    $knowledge_result = $connection->query($knowledge_query);

    $disease_cf = []; // Menyimpan hasil perhitungan CF untuk setiap penyakit

    while ($row = $knowledge_result->fetch_assoc()) {
        $disease_id = $row['disease_id'];
        $symptom_id = $row['symptom_id'];
        $mb = $row['mb_value'];
        $md = $row['md_value'];

        if (isset($symptoms[$symptom_id])) {
            $cf_user = $symptoms[$symptom_id];

            // Hitung CF untuk gejala ini
            $cf_gejala = ($mb - $md) * $cf_user;

            if (!isset($disease_cf[$disease_id])) {
                $disease_cf[$disease_id] = $cf_gejala;
            } else {
                $disease_cf[$disease_id] = $disease_cf[$disease_id] + ($cf_gejala * (1 - $disease_cf[$disease_id]));
            }

            // Pastikan nilai CF tetap dalam rentang [-1, 1]
            $disease_cf[$disease_id] = max(-1, min(1, $disease_cf[$disease_id]));
        }
    }

    if (empty($disease_cf)) {
        // echo "<script>alert('Tidak ada penyakit yang cocok dengan gejala yang dipilih!'); window.history.back();</script>";

        $_SESSION['popup-warning'] = "Tidak ada penyakit yang cocok dengan gejala yang dipilih!";
        echo "<script>window.location.href = '" . base_url('user/consultation.php') . "';</script>";
        exit;
    }

    // Simpan hasil konsultasi ke database
    $diseases_result = [];
    foreach ($disease_cf as $disease_id => $confidence) {
        $diseases_result[] = [
            'disease_id' => $disease_id,
            'confidence' => round($confidence, 2)
        ];
    }

    // Urutkan berdasarkan confidence tertinggi
    usort($diseases_result, function ($a, $b) {
        return $b['confidence'] <=> $a['confidence'];
    });

    // Ambil nama penyakit dari database
    foreach ($diseases_result as &$result) {
        $query = "SELECT disease_name FROM diseases WHERE id = " . $result['disease_id'];
        $disease_name = $connection->query($query)->fetch_assoc();
        $result['disease_name'] = $disease_name['disease_name'];
    }

    // Simpan ke tabel consultations
    $disease_json = json_encode($diseases_result);
    $symptoms_json = json_encode(array_map(fn($id, $value) => ['symptom_id' => $id, 'condition_value' => $value], array_keys($symptoms), $symptoms));

    $insert_consultation = "INSERT INTO consultations (consultation_date, disease, symptom) VALUES (NOW(), '$disease_json', '$symptoms_json')";
    $connection->query($insert_consultation);
    $consultation_id = $connection->insert_id;

    // Ambil data penyakit dari JSON yang sudah disimpan di tabel consultations
    $diseases_result = json_decode($disease_json, true);

    // Simpan ke tabel histories
    foreach ($diseases_result as $result) {
        $disease_id = $result['disease_id'];
        $confidence = $result['confidence'];

        // Pastikan tidak ada penyakit yang terlewat saat insert ke histories
        $insert_history = "INSERT INTO histories (user_id, consultation_id, disease_id, accuracy) VALUES ($user_id, $consultation_id, $disease_id, $confidence)";
        $connection->query($insert_history);
    }

    // Simpan untuk notifikasi terkait konsultasi ke dashboard
    $insert_consultation_notification = "INSERT INTO notifications (consultation_id, message_id, testimonial_id, status) VALUES ('$consultation_id', NULL, NULL, 'Belum Dilihat')";
    $connection->query($insert_consultation_notification);

    // Redirect ke halaman hasil
    echo "<script>window.location.href = '" . base_url('user/consultation-result.php?consultation-id=' . $consultation_id) . "';</script>";
    exit;
}

// Kirim Pesan
if (isset($_POST['send-message'])) {
    $errors = [];
    $form_data = [];

    // Mengambil data dari form
    $email          = htmlspecialchars($_POST['email']);
    $fullname       = htmlspecialchars($_POST['fullname']);
    $subject        = mysqli_real_escape_string($connection, $_POST['subject']);
    $message        = mysqli_real_escape_string($connection, $_POST['message']);
    $message_date   = date('Y-m-d H:i:s');
    $status         = 'Belum Dibaca';

    // Validasi email
    if (empty($email)) {
        $errors['email'] = 'Email wajib diisi.';
    } else {
        $form_data['email'] = $email;
    }

    // Validasi nama lengkap
    if (empty($fullname)) {
        $errors['fullname'] = 'Nama wajib diisi.';
    } else {
        $form_data['fullname'] = $fullname;
    }

    // Validasi subjek
    if (empty($subject)) {
        $errors['subject'] = 'Subjek wajib diisi.';
    } else {
        $form_data['subject'] = $subject;
    }

    // Validasi pesan
    if (empty($message)) {
        $errors['message'] = 'Pesan wajib diisi.';
    } else {
        $form_data['message'] = $message;
    }

    // Jika tidak ada error
    if (empty($errors)) {
        // Query insert
        $query = "INSERT INTO messages (id, email, fullname, subject, message, message_date, status) 
                         VALUES (NULL, '$email', '$fullname', '$subject', '$message', '$message_date', '$status')";

        $create = $connection->query($query);

        if ($create) {
            // Ambil ID pesan yang baru saja dimasukkan
            $message_id = $connection->insert_id;

            // Simpan untuk notifikasi terkait pesan ke dashboard
            $insert_message_notification = "INSERT INTO notifications (consultation_id, message_id, testimonial_id, status) VALUES (NULL, '$message_id', NULL, 'Belum Dilihat')";
            $connection->query($insert_message_notification);

            $_SESSION['popup-success'] = "Pesan berhasil dikirim.";
            echo "<script>window.location.href = '" . base_url('user/index.php#contact') . "';</script>";
        } else {
            $_SESSION['popup-error'] = 'Pesan gagal dikirim.';
            echo "<script>window.location.href = '" . base_url('user/index.php#contact') . "';</script>";
        }
        exit();
    } else {
        // Simpan error dan form_data dalam session
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = $form_data;

        // Redirect dengan mengirim error
        echo "<script>window.location.href = '" . base_url('user/index.php#contact') . "';</script>";
        exit();
    }
}

// Cek auth
if ((isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') || (isset($_SESSION['role']) && $_SESSION['role'] === 'Pengguna')) {
    // Buat ulasan
    if (isset($_POST['create-review'])) {
        $errors = [];
        $form_data = [];

        $user_id = htmlspecialchars($_POST['user_id']);
        $review_date = date('Y-m-d H:i:s');
        $rating = htmlspecialchars($_POST['rating']);
        $review = mysqli_real_escape_string($connection, $_POST['review']);

        // Validasi rating
        if (empty($rating)) {
            $errors['rating'] = 'Pilih salah satu angka.';
        }

        // Validasi review
        if (empty($review)) {
            $errors['review'] = 'Ulasan wajib diisi.';
        }

        // Simpan form data jika ada error
        $form_data['rating'] = $rating;
        $form_data['review'] = $review;

        // Jika tidak ada error
        if (empty($errors)) {
            // Query insert
            $query = "INSERT INTO testimonials (id, user_id, review_date, rating, review) VALUES (NULL, '$user_id', '$review_date', '$rating', '$review')";

            $create = $connection->query($query);

            if ($create) {
                // Ambil ID testimoni yang baru saja dimasukkan
                $testimonial_id = $connection->insert_id;

                // Simpan untuk notifikasi terkait testimoni ke dashboard
                $insert_testimonial_notification = "INSERT INTO notifications (consultation_id, message_id, testimonial_id, status) VALUES (NULL, NULL, '$testimonial_id', 'Belum Dilihat')";
                $connection->query($insert_testimonial_notification);

                $_SESSION['success'] = "Testimoni berhasil dibuat.";
            } else {
                $_SESSION['error'] = "Testimoni gagal dibuat.";
            }

            // Redirect pengguna
            echo "<script>window.location.href = '" . base_url('user/testimonial.php') . "';</script>";
            exit();
        } else {
            // Simpan error dan form_data dalam session
            $_SESSION['errors'] = $errors;
            $_SESSION['form_data'] = $form_data;

            echo "<script>window.location.href = '" . base_url('user/testimonial.php') . "';</script>";
            exit();
        }
    }


    // Ubah ulasan
    if (isset($_POST['update-review'])) {
        $errors = [];

        // Ambil data dari form
        $testimonial_id = htmlspecialchars($_POST['testimonial_id']);
        $user_id = htmlspecialchars($_POST['user_id']);
        $rating = htmlspecialchars($_POST['rating']);
        $review = htmlspecialchars($_POST['review']);
        $review_date = date('Y-m-d H:i:s'); // Tanggal update (bisa disesuaikan jika dibutuhkan)

        // Validasi rating
        if (empty($rating)) {
            $errors['rating'] = 'Pilih salah satu angka.';
        }

        // Validasi review
        if (empty($review)) {
            $errors['review'] = 'Ulasan wajib diisi.';
        }

        // Jika tidak ada error
        if (empty($errors)) {
            // Query untuk update review
            $query = "UPDATE testimonials SET rating = '$rating', review = '$review', review_date = '$review_date' WHERE id = '$testimonial_id' AND user_id = '$user_id'";

            $update = $connection->query($query);

            if ($update) {
                // Simpan untuk notifikasi terkait testimoni ke dashboard
                $update_testimonial_notification = "UPDATE notifications SET status = 'Belum Dilihat' WHERE testimonial_id = '$testimonial_id'";
                $connection->query($update_testimonial_notification);

                $_SESSION['success'] = "Testimoni berhasil diperbarui.";
            } else {
                $_SESSION['error'] = "Testimoni gagal diperbarui.";
            }

            // Redirect pengguna
            echo "<script>window.location.href = '" . base_url('user/testimonial.php') . "';</script>";
            exit();
        } else {
            // Simpan error dalam session
            $_SESSION['errors'] = $errors;

            // Redirect kembali ke halaman edit
            echo "<script>window.location.href = '" . base_url('user/testimonial.php') . "';</script>";
            exit();
        }
    }
}
