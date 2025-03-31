<?php
include 'includes/config.php';
$result = $conn->query("SELECT * FROM achievements ORDER BY year DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hall of Fame</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

<div id="hallOfFameCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="15000">
    <div class="carousel-inner">
        <?php $active = true; while ($row = $result->fetch_assoc()): ?>
            <div class="carousel-item <?= $active ? 'active' : '' ?>">
                <!-- Rozmazané pozadí -->
                <img src="uploads/<?= htmlspecialchars($row['image']) ?>" class="d-block w-100 img-fluid carousel-bg" alt="<?= htmlspecialchars($row['name']) ?>">

                <!-- Hlavní obsah (obrázek + text) -->
                <div class="container d-flex justify-content-center align-items-center vh-100">
                    <div class="row align-items-center text-white text-center content-wrapper">
                        <!-- Přední obrázek -->
                        <div class="col-md-5">
                            <?php
                                $imagePath = "uploads/" . htmlspecialchars($row['image']);
                                $fileExtension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));
                                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];

                                if (file_exists($imagePath) && in_array($fileExtension, $allowedExtensions)): ?>
                                    <img src="<?= $imagePath ?>?v=<?= time() ?>" class="d-block img-fluid content-img" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                    <p class="text-white text-center">Image not available.</p>
                            <?php endif; ?>
                        </div>
                        <!-- Textová informace -->
                        <div class="col-md-6 text-container">
                            <h1 class="display-3 fw-bold"><?= htmlspecialchars($row['name']) ?></h1>
                            <h3 class="fs-2"><?= htmlspecialchars($row['title']) ?> - <?= htmlspecialchars($row['year']) ?></h3>
                            <p class="lead fs-4"><?= htmlspecialchars($row['description']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        <?php $active = false; endwhile; ?>
    </div>

    <!-- Navigační šipky -->
    <button class="carousel-control-prev" type="button" data-bs-target="#hallOfFameCarousel" data-bs-slide="prev">
        <div class="custom-arrow"><span class="carousel-control-prev-icon" aria-hidden="true"></span></div>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#hallOfFameCarousel" data-bs-slide="next">
        <div class="custom-arrow"><span class="carousel-control-next-icon" aria-hidden="true"></span></div>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
</html>