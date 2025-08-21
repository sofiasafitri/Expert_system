<?php
// Menyertakan konfigurasi
include('../config/config.php');

// Memulai sesi
session_start();

// Nama halaman
$page_name = 'Tentang';
?>

<?php startSection('css'); ?>
<style>
    .formula {
        background-color: #f8f9fa;
        padding: 10px;
        border-left: 5px solid #435ebe;
        font-family: 'Courier New', Courier, monospace;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    html[data-bs-theme=dark] .formula {
        background-color: rgb(85, 85, 85);
        color: #fff;
    }
</style>
<?php endSection('css'); ?>

<?php startSection('content'); ?>
<section class="page-section" id="pageSection">
    <div class="container px-4">
        <div class="row justify-content-center mb-4" data-aos="zoom-in">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <h3 class="mb-0"><?= $page_name; ?></h3>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center" data-aos="fade-up">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <article class="content">
                            <div class="content-section mb-5">
                                <h5>🔍 Apa itu Sistem Pakar ? </h5>
                                <p>
                                    Sistem pakar adalah sebuah sistem berbasis komputer yang dirancang untuk meniru proses pengambilan keputusan seorang pakar dalam suatu bidang tertentu.
                                    Dengan menggunakan metode <i>Certainty Factor</i>, sistem ini mampu mengukur tingkat kepastian dari sebuah diagnosis berdasarkan bukti yang tersedia.
                                    Hal ini memungkinkan sistem untuk memberikan rekomendasi yang lebih akurat sesuai dengan kondisi pengguna.
                                </p>
                                <p> 
                                    Pada sistem ini, pengetahuan tentang anoreksia nervosa diimplementasikan ke dalam basis pengetahuan sehingga dapat membantu masyarakat mendapatkan informasi awal terkait anoreksia nervosa.
                                    Dengan adanya sistem pakar, diharapkan pengguna dapat mengetahui gejala awal anoreksia nervosa, cara pencegahan, serta langkah penanganan sederhana sebelum berkonsultasi lebih lanjut dengan dokter.
                                </p>
                            </div>

                            <div class="content-section mb-5">
                                <h5>🔍 Tentang Anoreksia nervosa</h5>
                                <p>Anoreksia nervosa adalah gangguan makan serius yang ditandai dengan pembatasan asupan makanan secara ekstrem, rasa takut berlebihan terhadap kenaikan berat badan, dan citra tubuh yang terdistorsi. 
                                Kondisi ini dapat menyebabkan penurunan berat badan drastis, kelemahan fisik, gangguan hormonal, hingga komplikasi kesehatan serius. 
                                Penanganan anoreksia nervosa memerlukan kombinasi terapi psikologis, perawatan medis, dan dukungan gizi.
                                Informasi mengenai gejala, penyebab, dan pencegahan anoreksia nervosa dapat membantu masyarakat lebih waspada dan segera mengambil tindakan yang tepat.
                            </p> 
                            </div>

                            <div class="content-section mb-5">
                                <h5>📚Proses Diagnosa Menggunakan Certainty Factor</h5>
                                <p>Sistem pakar ini menggunakan metode <strong class="text-primary">Certainty Factor</strong> 
                        untuk membantu pengguna menentukan kemungkinan mengalami anoreksia nervosa berdasarkan gejala yang dipilih. 
                        Metode ini akan mencocokkan gejala yang dimasukkan dengan basis pengetahuan yang ada, 
                        lalu menarik kesimpulan berupa kemungkinan tingkat keparahan atau adanya gangguan anoreksia nervosa yang dialami. </p> 
                                
                            </div>

                            <div class="content-section mb-5">
                                <h5>📚 Contoh Data Pilihan Kondisi oleh Pengguna</h5>
                                <p>Adapun terdapat 6 pilihan kondisi yang tersedia, yaitu <b>TIDAK TAHU</b>, <b>TIDAK YAKIN</b>, <b>MUNGKIN YA</b>, <b>KEMUNGKINAN BESAR YA</b>, <b>HAMPIR PASTI YA</b>, dan <b>PASTI YA</b>.</p>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Kondisi</th>
                                                <th>Keterangan</th>
                                                <th>Nilai</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Tidak Tahu</td>
                                                <td>Gejala tidak dialami sama sekali.</td>
                                                <td>0</td>
                                            </tr>
                                            <tr>
                                                <td>Tidak Yakin</td>
                                                <td>Tidak yakin apakah gejala tersebut ada atau tidak.</td>
                                                <td>0.2</td>
                                            </tr>
                                            <tr>
                                                <td>Mungkin Ya</td>
                                                <td>Gejala bisa saja ada, tetapi tidak pasti.</td>
                                                <td>0.4</td>
                                            </tr>
                                            <tr>
                                                <td>Kemungkinan Besar Ya</td>
                                                <td>Gejala sangat mungkin ada, namun belum dapat dipastikan.</td>
                                                <td>0.6</td>
                                            </tr>
                                            <tr>
                                                <td>Hampir Pasti</td>
                                                <td>Gejala pasti ada dan sangat jelas.</td>
                                                <td>0.8</td>
                                            </tr>
                                            <tr>
                                                <td>Pasti Ya</td>
                                                <td>Gejala sangat jelas dan dipastikan ada dengan tingkat kepastian yang tinggi.</td>
                                                <td>1</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endSection('content'); ?>

<?php startSection('script'); ?>
<?php endSection('script'); ?>

<?php include('template.php') ?>