<?php
include 'db.php';

$message = "";
$student = null;

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            die("Student not found.");
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
} else {
    // Redirect if no ID
    header("Location: index.php");
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $student_id = $_POST['student_id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $grade = $_POST['grade'];
    $gender = $_POST['gender'];
    $status = $_POST['status'];

    if (!empty($name) && !empty($email)) {
        try {
            $sql = "UPDATE students SET student_id=?, name=?, email=?, grade=?, gender=?, status=? WHERE id=?";
            $stmt = $pdo->prepare($sql); // Prepare the query
            $stmt->execute([$student_id, $name, $email, $grade, $gender, $status, $id]); // Execute with values

            // Redirect back to index with success? Or just show message
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            $message = "Error updating record: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Student</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body {
            font-family: 'Lexend', sans-serif;
            background-color: #f6f6f8;
        }
    </style>
</head>

<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="index.php">
                <span class="bg-primary text-white rounded p-1 d-flex align-items-center justify-content-center">
                    <span class="material-icons" style="font-size: 20px;">school</span>
                </span>
                EduPulse
            </a>
            <a href="index.php" class="btn btn-outline-secondary d-flex align-items-center gap-1 border-0">
                <span class="material-icons transform-rotate-180">arrow_back</span> Back to List
            </a>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h2 class="h3 fw-bold mb-4 text-dark">Edit Student</h2>

                        <?php if ($message): ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <div><?php echo $message; ?></div>
                            </div>
                        <?php endif; ?>

                        <form action="edit.php?id=<?php echo htmlspecialchars($id); ?>" method="POST"
                            class="needs-validation" novalidate>
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">

                            <div class="mb-3">
                                <label for="student_id" class="form-label fw-medium text-secondary">Student ID</label>
                                <input type="text" class="form-control" id="student_id" name="student_id"
                                    value="<?php echo htmlspecialchars($student['student_id']); ?>" required>
                                <div class="invalid-feedback">
                                    Please provide a valid Student ID.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label fw-medium text-secondary">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?php echo htmlspecialchars($student['name']); ?>" required>
                                <div class="invalid-feedback">
                                    Please provide a name.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-medium text-secondary">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($student['email']); ?>" required>
                                <div class="invalid-feedback">
                                    Please provide a valid email.
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="grade" class="form-label fw-medium text-secondary">Grade</label>
                                    <select class="form-select" id="grade" name="grade" required>
                                        <option value="Grade 9" <?php echo ($student['grade'] == 'Grade 9') ? 'selected' : ''; ?>>Grade 9</option>
                                        <option value="Grade 10" <?php echo ($student['grade'] == 'Grade 10') ? 'selected' : ''; ?>>Grade 10</option>
                                        <option value="Grade 11" <?php echo ($student['grade'] == 'Grade 11') ? 'selected' : ''; ?>>Grade 11</option>
                                        <option value="Grade 12" <?php echo ($student['grade'] == 'Grade 12') ? 'selected' : ''; ?>>Grade 12</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a grade.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label fw-medium text-secondary">Gender</label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="Male" <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                        <option value="Female" <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                        <option value="Other" <?php echo ($student['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        Please select a gender.
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="status" class="form-label fw-medium text-secondary">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Active" <?php echo ($student['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo ($student['status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 fw-medium">Update Student</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Form Validation Script -->
    <script>
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>

</html>