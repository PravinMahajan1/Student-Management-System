<?php
include 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Simple deletion
    try {
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$id]);

        // Redirect back to index
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        die("Error deleting record: " . $e->getMessage());
    }
} else {
    // Redirect if no ID specified
    header("Location: index.php");
    exit();
}
?>