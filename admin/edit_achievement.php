<?php
session_start();
include '../includes/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit;
}

$result = $conn->query("SELECT * FROM achievements WHERE id = $id");
$achievement = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $year = $_POST['year'];
    $description = $_POST['description'];

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
    $new_image_name = $achievement['image']; // Keep existing image by default

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        if (in_array($image_extension, $allowed_extensions)) {
            $new_image_name = uniqid() . "." . $image_extension;
            $upload_path = "../uploads/" . $new_image_name;

            if (move_uploaded_file($image_tmp, $upload_path)) {
                // Optional: Delete old image file
                if (!empty($achievement['image']) && file_exists("../uploads/" . $achievement['image'])) {
                    unlink("../uploads/" . $achievement['image']);
                }
            } else {
                echo "<script>alert('Error moving uploaded file!');</script>";
            }
        } else {
            echo "<script>alert('Invalid file type!'); window.history.back();</script>";
        }
    }

    $sql = "UPDATE achievements SET name='$name', title='$title', year='$year', description='$description', image='$new_image_name' WHERE id=$id";
    if ($conn->query($sql)) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Database error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Achievement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-center flex-grow-1">Edit Achievement</h1>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
    <form method="post" enctype="multipart/form-data" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($achievement['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($achievement['title']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Year</label>
            <input type="number" name="year" value="<?= htmlspecialchars($achievement['year']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($achievement['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label>
            <br>
            <?php
                $imagePath = "../uploads/" . htmlspecialchars($achievement['image']);
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];

                $fileExtension = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

                if (file_exists($imagePath) && in_array($fileExtension, $allowedExtensions)): ?>
                    <img src="<?= $imagePath ?>?v=<?= time() ?>" class="img-fluid mb-3" style="max-height: 150px;">
            <?php else: ?>
                    <p class="text-danger">Image preview not available.</p>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">New Image (Optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary w-100">Update</button>
    </form>
</div>
</body>
</html>