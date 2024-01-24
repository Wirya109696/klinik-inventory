$(document).ready(function () {
    var table = $("#tablelaporans").DataTable({
        processing: true,
        serverSide: true,
        ajax: window.url + "/transaksibarang/laporan/json",
        columns: [
            { data: "DT_RowIndex", name: "DT_RowIndex" },
            // { data: "barang_id", name: "barang_id" },
            { data: "brg_kode", name: "brg_kode" },
            { data: "brg_nama", name: "brg_nama" },
            { data: "kat_nama", name: "kat_nama" },
            { data: "stock_awal", name: "stock_awal" },
            { data: "pemasukan", name: "pemasukan" },
            { data: "pengeluaran", name: "pengeluaran" },
            { data: "dtl_jumlah", name: "dtl_jumlah" },
            // { data: "brg_min", name: "brg_min" },
            // { data: "gudang_id", name: "gudang_id" },
            { data: "gud_nama", name: "gud_nama" },
            {
                data: "action",
                name: "action",
                orderable: true,
                searchable: true,
            },
        ],
        columnDefs: [
            { targets: [0, 1, 2, 3, 4, 5, 6, 7, 8], className: "text-center" },
        ],
    });
});
