<?php 
session_start();
include 'db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (!isset($_SESSION['id_user'])) {
    header("Location: 2_login.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$query = mysqli_query($conn, "SELECT username, email, tgl_lahir FROM user WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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

    <!-- Profile -->
      <div class="container mt-5">
        <div class="mx-auto bg-white p-4 rounded shadow" style="max-width: 720px; border-radius: 1.5rem !important;">
            <h2 class="mb-4">Your Profile</h2>
            <form action="15_update_profile.php" method="POST" class="p-4 border rounded shadow-sm bg-light">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" value="<?= htmlspecialchars($user['tgl_lahir']) ?>" required>
                </div>
                <button type="submit" class="btn btn-purple">Update Profile</button>
            </form>
        </div>
    </div>
    <!-- Akhir Profile -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>