<?php 
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .border-purple {
        border: 1px solid #a65acb;
        }
        .text-purple {
            color: #6e348b;
        }
        .ticket-card {
            background-color: #fff;
            transition: all 0.3s ease;
        }
        .ticket-card:hover {
            box-shadow: 0 6px 16px rgba(166, 90, 203, 0.15);
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

    <!-- Your Ticket -->
    <div class="container my-5">
        <div class="mx-auto p-4" style="max-width: 720px; border-radius: 1.5rem !important;">
            <h3 class="fw-bold mb-4">Your Tickets</h3>

            <?php
            $id_user = $_SESSION['id_user'];
            $query = "SELECT r.*, e.nama_event, e.poster 
                    FROM tiket_reservasi r 
                    JOIN event e ON r.id_event = e.id_event 
                    WHERE r.id_user = '$id_user'";
            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) > 0):
                while ($row = mysqli_fetch_assoc($result)):
                    // Mapping status ke warna dan teks
                    $status_labels = [
                        'pending'        => '<span class="text-warning fw-semibold">Pending</span>',
                        'approved'       => '<span class="text-success fw-semibold">Approved</span>',
                        'rejected'       => '<span class="text-danger fw-semibold">Rejected</span>',
                        'request_cancel' => '<span class="text-info fw-semibold">Request Cancel</span>',
                        'cancelled'      => '<span class="text-muted fw-semibold">Cancelled</span>',
                        'paid'           => '<span class="text-success fw-semibold">Paid</span>',
                    ];
                    $status_display = $status_labels[$row['status_tiket']] ?? '<span class="text-secondary fw-semibold">Unknown</span>';
            ?>
            
            <div class="ticket-card mb-4 p-3 rounded-4 border border-purple shadow-sm d-flex flex-column flex-md-row align-items-start justify-content-between">
                <div class="d-flex align-items-start">
                    <img src="<?= $row['poster'] ?>" alt="Event Image" class="rounded-3 me-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <div>
                        <small class="text-muted d-block mb-1">Konser Musik</small>
                        <h5 class="fw-semibold mb-2"><?= $row['nama_event'] ?></h5>
                        <div class="d-flex gap-5">
                            <div><small class="text-muted">Tiket ID</small><br><strong><?= $row['id_tiket'] ?></strong></div>
                            <div><small class="text-muted">Jumlah Tiket</small><br><strong><?= $row['tiket_kuantitas'] ?></strong></div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3 mt-md-0">
                    <?= $status_display ?><br>
                    <small class="text-muted"><?= date('d/m/Y', strtotime($row['tgl_pemesanan'])) ?></small><br>
                    <a href="14_ticket_detail.php?id=<?= $row['id_tiket'] ?>" 
                        class="text-decoration-underline text-purple fw-medium small mt-4 d-inline-block">
                        Ticket Details
                    </a>
                </div>
            </div>
            
            <?php endwhile;
            else: ?>
                <div class="alert alert-info">Belum ada tiket yang kamu pesan.</div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Akhir Your Ticket -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
