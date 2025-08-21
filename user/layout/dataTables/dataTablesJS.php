<!-- dataTables j-Query JS -->
<script src="https://zuramai.github.io/mazer/demo/assets/extensions/jquery/jquery.min.js"></script>
<script src="https://zuramai.github.io/mazer/demo/assets/extensions/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="https://zuramai.github.io/mazer/demo/assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
<script>
    let customized_datatable = $("#table").DataTable({
        responsive: true,
        dom: "<'row'<'col-3'l><'col-9'f>>" +
            "<'row dt-row'<'col-sm-12'tr>>" +
            "<'row'<'col-4'i><'col-8'p>>",
        "language": {
            "info": "Halaman _PAGE_ dari _PAGES_",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "search": "",
            "searchPlaceholder": "Cari...",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(disaring dari total _MAX_ data)",
            "zeroRecords": "Tidak ditemukan data yang sesuai",
            "paginate": {
                "first": "Awal",
                "last": "Akhir",
                "next": "Berikutnya",
                "previous": "Sebelumnya"
            },
            "loadingRecords": "Sedang memuat...",
            "processing": "Sedang memproses...",
            "emptyTable": "Tidak ada data dalam tabel"
        }
    });

    const setTableColor = () => {
        document.querySelectorAll('.dataTables_paginate .pagination').forEach(dt => {
            dt.classList.add('pagination-primary')
        })
    }

    setTableColor()
</script>