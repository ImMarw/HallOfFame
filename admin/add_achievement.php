<?php
include '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $title = $_POST['title'];
    $year = $_POST['year'];
    $description = $_POST['description'];

    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'];
    
    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_extension = strtolower(pathinfo($image, PATHINFO_EXTENSION));

        // Debug: Check if extension is valid
        if (!in_array($image_extension, $allowed_extensions)) {
            echo "<script>alert('Invalid file type: $image_extension');</script>";
            exit;
        }

        // Generate unique filename
        $new_image_name = uniqid() . "." . $image_extension;
        $upload_path = "../uploads/" . $new_image_name;

        // Debug: Check if tmp_name exists
        if (!file_exists($image_tmp)) {
            echo "<script>alert('Error: File not found in temp directory!');</script>";
            exit;
        }

        // Move file to uploads directory
        if (!move_uploaded_file($image_tmp, $upload_path)) {
            echo "<script>alert('Error moving uploaded file! Check file permissions and PHP settings.');</script>";
            error_log("Upload error: Failed to move file from $image_tmp to $upload_path", 0);
            exit;
        }

        // Insert into database
        $sql = "INSERT INTO achievements (name, title, year, description, image) VALUES ('$name', '$title', '$year', '$description', '$new_image_name')";
        if ($conn->query($sql)) {
            header("Location: dashboard.php");
            exit;
        } else {
            echo "<script>alert('Database error: " . $conn->error . "');</script>";
            exit;
        }
    } else {
        echo "<script>alert('No image uploaded! Please select an image.');</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Achievement</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-center flex-grow-1">Add Achievement</h1>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
    <form method="post" enctype="multipart/form-data" class="shadow p-4 bg-white rounded">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Year</label>
            <input type="number" name="year" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary w-100">Add</button>
    </form>
</div>

</body>
</html>