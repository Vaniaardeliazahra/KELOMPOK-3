<?php 
session_start();
include 'db.php';

$sql = "
SELECT e.id_event, e.nama_event, e.poster, e.deskripsi, e.tgl_mulai, e.tgl_selesai, e.lokasi, e.contact_person,
       COALESCE(SUM(tk.sisa_tiket), 0) AS total_sisa
FROM event e
LEFT JOIN tiket_kategori tk ON e.id_event = tk.id_event
GROUP BY e.id_event
";

$result = $conn->query($sql);

if (!$result) {
    die("Query error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-purple {
            background-color:rgb(86, 48, 97); 
            color: white;
            border: none;
        }
        .btn-purple:hover {
            background-color:rgb(255, 255, 255); 
            color: rgb(86, 48, 97);
            box-shadow: 0 4px 8px rgba(117, 72, 132, 0.4);
        }
        .poster-img {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 2rem;
        }
    </style>
</head>
<body>
    
    <!-- Navbar -->
    <?php
    $current_page = basename($_SERVER['PHP_SELF']);
    ?>

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
                                <a class="nav-link <?= ($current_page == '3_home.php') ? 'active' : '' ?>" href="3_home.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($current_page == '8_your_ticket.php') ? 'active' : '' ?>" href="8_your_ticket.php">Ticket</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= ($current_page == '9_manage_event.php') ? 'active' : '' ?>" href="9_manage_event.php">Event</a>
                            </li>
                        </ul>
                    </div>
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle <?= in_array($current_page, ['7_profile.php', '4_make_event.php', '5_find_event.php']) ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Profile
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item <?= ($current_page == '7_profile.php') ? 'active' : '' ?>" href="7_profile.php">Profile</a></li>
                            <li><a class="dropdown-item <?= ($current_page == '4_make_event.php') ? 'active' : '' ?>" href="4_make_event.php">Make Event</a></li>
                            <li><a class="dropdown-item <?= ($current_page == '5_find_event.php') ? 'active' : '' ?>" href="5_find_event.php">Find Event</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<!-- Akhir Navbar -->

    <!-- Find Event -->
      <div class="container">
        <div class="text-center mb-5 mt-5"><h3>Find Event</h3></div>
        <div class="row mb-3">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-3 mb-3 d-flex justify-content-center">
                    <div class="card shadow-lg" style="height: 28rem; width: 18rem; border-radius: 2rem; position: relative;">
                        <?php
                        if ($row['poster'] && file_exists($row['poster'])) {
                            $imgSrc = $row['poster'];
                        } else {
                            $imgSrc = 'img/default.jpg'; 
                        }
                        ?>
                        <img src="<?= $imgSrc ?>" class="card-img-top p-2 poster-img" alt="<?= htmlspecialchars($row['nama_event']) ?>" />
                        <div class="card-body" style="position: relative;">
                            <h5 class="card-title mb-2"><?= htmlspecialchars($row['nama_event']) ?></h5>
                            <?php if (!empty($row['kategori'])): ?>
                                <p class="text-muted small">Kategori: <?= htmlspecialchars($row['kategori']) ?></p>
                            <?php endif; ?>
                            <?php if ($row['total_sisa'] > 0): ?>
                                <p class="card-text text-success mb-1"><strong>Available</strong></p>
                                <div class="d-flex justify-content-start align-items-center" style="gap: 1rem; position: absolute; bottom: 1rem; left: 1rem;">
                                    <a href="6_get_ticket.php?id=<?= $row['id_event'] ?>" class="btn btn-purple" style="width: 8rem; border-radius: 2rem;"><strong>Get Ticket</strong></a>
                                    <a href="#" 
                                        class="btn btn-link view-details" 
                                        style="color: #754884; font-weight: 600; text-decoration: underline;"
                                        data-nama="<?= htmlspecialchars($row['nama_event']) ?>"
                                        data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>"
                                        data-tgl-mulai="<?= htmlspecialchars($row['tgl_mulai']) ?>"
                                        data-tgl-selesai="<?= htmlspecialchars($row['tgl_selesai']) ?>"
                                        data-lokasi="<?= htmlspecialchars($row['lokasi']) ?>"
                                        data-contact="<?= htmlspecialchars($row['contact_person']) ?>"
                                        >View Details
                                    </a>
                                </div>
                            <?php else: ?>
                                <p class="card-text text-danger mb-1"><strong>Sold Out</strong></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <!-- Akhir Find Event -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const nama = this.dataset.nama;
            const deskripsi = this.dataset.deskripsi;
            const tglMulai = this.dataset.tglMulai;
            const tglSelesai = this.dataset.tglSelesai;
            const lokasi = this.dataset.lokasi;
            const contact = this.dataset.contact;  

            let tanggalDisplay;
            if (tglMulai === tglSelesai) {
                tanggalDisplay = tglMulai;
            } else {
                tanggalDisplay = `${tglMulai} s/d ${tglSelesai}`;
            }

            const htmlContent = `
                <table class="table table-borderless" style="text-align: left;">
                    <tbody>
                        <tr>
                            <th style="width: 40%;">Nama Event</th>
                            <td>${nama}</td>
                        </tr>
                        <tr>
                            <th>Deskripsi</th>
                            <td>${deskripsi}</td>
                        </tr>
                        <tr>
                            <th>Tanggal</th>
                            <td>${tanggalDisplay}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>${lokasi}</td>
                        </tr>
                        <tr>
                            <th>Contact Person</th>
                            <td>${contact}</td>
                        </tr>
                    </tbody>
                </table>
                <p style="font-weight: 600; margin-top: 1rem; text-align: center;">Apakah kamu ingin membeli tiket?</p>
            `;

            Swal.fire({
                title: 'Detail Event',
                html: htmlContent,
                showCancelButton: true,
                confirmButtonText: 'Ya, beli tiket',
                cancelButtonText: 'Batal',
                confirmButtonColor: 'rgb(117, 72, 132)',
                customClass: {
                    popup: 'shadow-lg rounded-4',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const idEvent = this.closest('.card').querySelector('a.btn-purple').href.split('=')[1];
                    window.location.href = `6_get_ticket.php?id=${idEvent}`;
                }
            });
        });
    });
    </script>
</body>
</html>