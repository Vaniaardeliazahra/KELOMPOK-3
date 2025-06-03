<?php
include 'db.php';
session_start();

$id_event = $_GET['id'] ?? null;
if (!$id_event) {
    echo "ID Event tidak ditemukan.";
    exit;
}

// Ambil data tiket berdasarkan id_event
$tiket_result = mysqli_query($conn, "SELECT * FROM tiket_kategori WHERE id_event = $id_event AND sisa_tiket > 0");

// Ambil data metode pembayaran
$metode_result = mysqli_query($conn, "SELECT * FROM metode_pembayaran");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Get Tiket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    .btn-purple {
      background-color: rgb(117, 72, 132);
      color: white;
    }
    .btn-purple:hover {
      background-color: rgb(86, 48, 97);
      color: white;
    }
    .form-control, .form-select {
        background-color: #f3e9f8; /* soft purple */
        border: 1px solid rgb(181, 181, 181);
        color: #4b355c;
    }
    .form-control:focus, .form-select:focus {
        background-color: #f3e9f8;
        border-color: rgb(181, 181, 181);
        box-shadow: 0 0 0 0.2rem rgba(161, 116, 200, 0.25);
        color: #4b355c;
    }
  </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="3_home.php">
        <img src="img/Logo.jpg" alt="Logo" style="height: 40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="3_home.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="8_your_ticket.php">Ticket</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="9_manage_event.php">Event</a>
                    </li>
                </ul>
            </div>
            <li class="nav-item dropdown me-2">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                Profile
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="7_profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="4_make_event.php">Make Event</a></li>
                <li><a class="dropdown-item" href="5_find_event.php">Find Event</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
            </ul>
            </li>
        </ul>
        </div>
    </div>
    </nav>
    <!-- Akhir Navbar -->

    <!-- Get Ticket -->
    <section id="GetTicket" class="py-5 bg-light">
    <div class="container mt-5">
        <div class="mx-auto bg-white p-4 rounded shadow" style="max-width: 650px; border-radius: 1.5rem !important;">
                <h2 class="text-center mb-4">Pembelian Tiket</h2>
                <form id="ticketForm" method="POST" action="13_proses_reservasi.php">
                <input type="hidden" name="id_event" value="<?= $id_event ?>">

                <div class="mb-3">
                    <label for="id_kategori" class="form-label">Pilih Kategori Tiket</label>
                    <select name="id_kategori" id="id_kategori" class="form-select" required onchange="updateHarga()">
                    <option value="" disabled selected>-- Pilih Tiket --</option>
                    <?php while ($row = mysqli_fetch_assoc($tiket_result)) : ?>
                        <option 
                        value="<?= $row['id_kategori'] ?>" 
                        data-harga="<?= $row['harga_tiket'] ?>" 
                        data-sisa="<?= $row['sisa_tiket'] ?>">
                        <?= htmlspecialchars($row['nama_kategori']) ?> - Rp<?= number_format($row['harga_tiket'], 0, ',', '.') ?>
                        </option>
                    <?php endwhile; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="kuantitas" class="form-label">Jumlah Tiket</label>
                    <input type="number" name="kuantitas" id="kuantitas" class="form-control" min="1" onchange="hitungTotal()" required>
                    <div class="form-text" id="sisaText"></div>
                </div>

                <div class="mb-3">
                    <label for="total_harga" class="form-label">Total Harga</label>
                    <input type="text" class="form-control" id="total_harga" name="total_harga" readonly>
                </div>

                <div class="mb-3">
                    <label for="id_metode" class="form-label">Metode Pembayaran</label>
                    <select name="id_metode" id="id_metode" class="form-select" required>
                    <option value="" selected disabled>-- Pilih Metode Pembayaran --</option>
                    <?php while ($m = mysqli_fetch_assoc($metode_result)) : ?>
                        <option value="<?= $m['id_metode'] ?>"><?= htmlspecialchars($m['nama_metode']) ?></option>
                    <?php endwhile; ?>
                    </select>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-purple">Bayar Sekarang</button>
                </div>
            </form>
        </div>
    </div>
    </section>
    <!-- Akhir Get Ticket -->

  <script>
    function updateHarga() {
      const selected = document.querySelector('#id_kategori option:checked');
      const harga = selected.dataset.harga;
      const sisa = selected.dataset.sisa;
      document.getElementById('kuantitas').value = '';
      document.getElementById('total_harga').value = '';
      document.getElementById('kuantitas').max = sisa;
      document.getElementById('sisaText').textContent = `Sisa Tiket: ${sisa}`;
    }

    function hitungTotal() {
      const kuantitas = parseInt(document.getElementById('kuantitas').value) || 0;
      const harga = parseInt(document.querySelector('#id_kategori option:checked').dataset.harga) || 0;
      const sisa = parseInt(document.querySelector('#id_kategori option:checked').dataset.sisa);
      if (kuantitas > sisa) {
        alert('Jumlah melebihi sisa tiket!');
        document.getElementById('kuantitas').value = '';
        document.getElementById('total_harga').value = '';
      } else {
        document.getElementById('total_harga').value = `Rp${(kuantitas * harga).toLocaleString('id-ID')}`;
      }
    }

    // Submit konfirmasi dan generate kode bayar
    document.getElementById('ticketForm').addEventListener('submit', function(e) {
    e.preventDefault();

    Swal.fire({
        title: 'Konfirmasi Pembelian',
        text: 'Apakah Anda yakin ingin membeli tiket ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#754884'
    }).then((result) => {
        if (result.isConfirmed) {
        // Kirim data form via AJAX
        const formData = new FormData(document.getElementById('ticketForm'));
        fetch('13_proses_reservasi.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json()) // bisa dipakai lagi karena JSON sudah bersih
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    title: 'Pembayaran Berhasil!',
                    html: `Kode Pembayaran Anda: <strong>${data.kode_bayar}</strong>`,
                    icon: 'success',
                    confirmButtonColor: '#754884'
                }).then(() => {
                    window.location.href = '8_your_ticket.php';
                });
            } else {
                Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat menyimpan data.', 'error');
            }
        })
        .catch(err => {
            Swal.fire('Gagal', 'Terjadi kesalahan koneksi.', 'error');
        });
        }
    });
    });
  </script>
</body>
</html>
