<?php include '../../configration/db.config.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = htmlspecialchars($_POST['id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $image = $_POST['image'];
    $icon = $_POST['icon'];

    // Check for empty fields
    if (empty($id) || empty($description) || empty($name) || empty($image) || empty($icon)) {
        header("Location: ../products.php?message=fill in the values");
        exit(); // Stop script execution after redirection
    }

    try {
        $query = "INSERT INTO categories (id, name, description, image, icon) 
        VALUES (:id, :name, :description, :image, :icon)";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'image' => $image,
            'icon' => $icon
        ]);

        // Redirect after successful insertion
        header("Location: ../categories.php?message=success");
        exit();
    } catch (PDOException $e) {
        // Redirect with error message if insertion fails
        header("Location: ../categories.php?message=error");
        exit();
    }
}
