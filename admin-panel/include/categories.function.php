<?php
include '../../configration/db.config.php';

/**
 * Add a new category.
 */
function addCategory($name, $description, $icon, $image) {
   global $pdo;

    try {
        $query = "INSERT INTO categories (name, description, image, icon) VALUES (:name, :description, :image, :icon)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'image' => $image,
            'icon' => $icon
        ]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Update an existing category.
 */
function updateCategory($id, $name, $description, $icon, $image) {
    global $pdo;

    try {
        $query = "UPDATE categories SET name=:name, description=:description, image=:image, icon=:icon WHERE id=:id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'image' => $image,
            'icon' => $icon
        ]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Delete a category.
 */
function deleteCategory($id) {
    global $pdo;

    try {
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id=:id");
        $stmt->execute(['id' => $id]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}
?>
