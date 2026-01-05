<?php
// SAYA ASUMSIKAN KAMU SUDAH PUNYA KONEKSI DATABASE
// include 'koneksi.php'; 

// SIMULASI DATA (Hapus bagian ini jika sudah connect database)
// Ini hanya agar kamu bisa melihat tampilan UI-nya langsung tanpa error database dulu.
$total_pendapatan = "Rp 1.250.000.000"; // Dari query SUM(totalHarga)
$total_reservasi = 200; // Dari query COUNT(idReservasi)
$total_user = 10; // Dari query COUNT(idUser)
$reservasi_terbaru = [
    ['id' => 'INV-10-20-957', 'tamu' => 'Joko Susilo', 'hotel' => 'Favehotel Rungkut', 'checkin' => '2025-06-13', 'status' => 'Completed', 'total' => 'Rp 1.800.000'],
    ['id' => 'INV-10-19-7655', 'tamu' => 'Joko Susilo', 'hotel' => 'Favehotel Rungkut', 'checkin' => '2025-11-23', 'status' => 'Confirmed', 'total' => 'Rp 3.800.000'],
    ['id' => 'INV-4-20-7956', 'tamu' => 'Dimas Anggara', 'hotel' => 'Fairmont Jakarta', 'checkin' => '2025-04-25', 'status' => 'Cancelled', 'total' => 'Rp 8.500.000'],
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - NginapYuk!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #0d6efd; /* Biru khas NginapYuk! */
            --secondary-bg: #f8f9fa;
            --text-dark: #333;
        }
        body {
            background-color: var(--secondary-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .sidebar {
            min-height: 100vh;
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            z-index: 100;
        }
        .nav-link {
            color: #6c757d;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #e7f1ff;
            color: var(--primary-color);
        }
        .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        .card-stat {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }
        .card-stat:hover {
            transform: translateY(-5px);
        }
        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .table-custom th {
            font-weight: 600;
            background-color: #f1f4f9;
            border-bottom: none;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        .status-Completed { background-color: #d1e7dd; color: #0f5132; }
        .status-Confirmed { background-color: #cff4fc; color: #055160; }
        .status-Cancelled { background-color: #f8d7da; color: #842029; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 d-md-block sidebar collapse px-3 py-3" id="sidebarMenu">
            <a href="#" class="d-flex align-items-center mb-4 mb-md-0 me-md-auto text-decoration-none px-2">
                <span class="fs-4 fw-bold text-primary"><i class="bi bi-building-check me-2"></i>NginapYuk!</span>
            </a>
            <hr>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="#">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-calendar-check"></i> Reservasi
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-buildings"></i> Data Hotel
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-door-open"></i> Tipe Kamar
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-people"></i> Pengguna
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="bi bi-star"></i> Ulasan
                    </a>
                </li>
            </ul>
            <hr class="mt-5">
            <div class="px-2">
                <a href="#" class="btn btn-outline-danger w-100"><i class="bi bi-box-arrow-right me-2"></i>Logout</a>
            </div>
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <nav class="navbar navbar-light bg-light d-md-none mb-3">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <span class="navbar-brand mb-0 h1 text-primary">NginapYuk! Admin</span>
                </div>
            </nav>

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">Dashboard Overview</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card card-stat h-100 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Pendapatan</h6>
                                <h3 class="fw-bold mb-0"><?= $total_pendapatan; ?></h3>
                                <small class="text-success"><i class="bi bi-arrow-up-short"></i> Dari reservasi 'Completed'</small>
                            </div>
                            <div class="icon-box bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat h-100 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Total Reservasi</h6>
                                <h3 class="fw-bold mb-0"><?= $total_reservasi; ?></h3>
                                <small class="text-muted">Semua status</small>
                            </div>
                            <div class="icon-box bg-success bg-opacity-10 text-success">
                                <i class="bi bi-journal-bookmark"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-stat h-100 p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted mb-1">Pengguna Terdaftar</h6>
                                <h3 class="fw-bold mb-0"><?= $total_user; ?></h3>
                                <small class="text-primary">Pelanggan aktif</small>
                            </div>
                            <div class="icon-box bg-warning bg-opacity-10 text-warning">
                                <i class="bi bi-people-fill"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Reservasi Terbaru</h5>
                    <a href="#" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>ID Booking</th>
                                    <th>Nama Tamu</th>
                                    <th>Hotel</th>
                                    <th>Check-In</th>
                                    <th>Status</th>
                                    <th>Total Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($reservasi_terbaru as $res): ?>
                                <tr>
                                    <td><span class="fw-bold text-primary"><?= $res['id']; ?></span></td>
                                    <td><?= $res['tamu']; ?></td>
                                    <td><?= $res['hotel']; ?></td>
                                    <td><?= $res['checkin']; ?></td>
                                    <td><span class="status-badge status-<?= $res['status']; ?>"><?= $res['status']; ?></span></td>
                                    <td class="fw-bold"><?= $res['total']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-light text-primary"><i class="bi bi-eye"></i></button>
                                        <button class="btn btn-sm btn-light text-danger"><i class="bi bi-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>