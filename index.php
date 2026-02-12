<?php
include 'db.php';

// Fetch students from the database
$stmt = $pdo->query("SELECT * FROM students ORDER BY id DESC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total students
$total_students = count($students);

// Count active students (simple loop)
$active_count = 0;
foreach ($students as $student) {
    if ($student['status'] == 'Active') {
        $active_count++;
    }
}
$inactive_count = $total_students - $active_count;

// Search logic
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM students WHERE name LIKE ? OR student_id LIKE ? ORDER BY id DESC");
    $stmt->execute(["%$search_query%", "%$search_query%"]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Directory List</title>
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

        .navbar-brand {
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand .icon-wrapper {
            background-color: #0d6efd;
            padding: 5px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar-brand .material-icons {
            color: white;
            font-size: 20px;
        }

        .main-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .status-inactive {
            background-color: #e2e3e5;
            color: #41464b;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stats-icon {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
            padding: 10px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <div class="icon-wrapper">
                    <span class="material-icons">school</span>
                </div>
                EduPulse
            </a>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light rounded-circle p-2">
                    <span class="material-icons text-secondary">notifications</span>
                </button>
                <img src="https://ui-avatars.com/api/?name=Admin&background=random" alt="Profile" class="avatar border">
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Student Directory</h1>
                <p class="text-secondary small mb-0">Manage and organize your school's student records.</p>
            </div>
            <a href="create.php" class="btn btn-primary d-flex align-items-center gap-2">
                <span class="material-icons" style="font-size: 18px;">add</span>
                Add New Student
            </a>
        </div>

        <!-- Filters/Search -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-11 position-relative">
                        <span class="material-icons position-absolute text-secondary"
                            style="top: 10px; left: 25px;">search</span>
                        <input name="search" value="<?php echo htmlspecialchars($search_query ?? ''); ?>"
                            class="form-control ps-5" type="text" placeholder="Search by name or ID...">
                    </div>
                    <div class="col-md-1">
                        <button type="submit"
                            class="btn btn-primary w-100 d-flex align-items-center justify-content-center">
                            <span class="material-icons">search</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="main-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-secondary text-uppercase small fw-bold">ID</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Name</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Grade</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Gender</th>
                            <th class="py-3 text-secondary text-uppercase small fw-bold">Status</th>
                            <th class="pe-4 py-3 text-end text-secondary text-uppercase small fw-bold">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($students) > 0): ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td class="ps-4 fw-medium text-secondary">
                                        <?php echo htmlspecialchars($student['student_id']); ?>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($student['name']); ?>&background=random"
                                                class="avatar border" alt="<?php echo htmlspecialchars($student['name']); ?>">
                                            <div>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($student['name']); ?>
                                                </div>
                                                <div class="small text-secondary">
                                                    <?php echo htmlspecialchars($student['email']); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-secondary"><?php echo htmlspecialchars($student['grade']); ?></td>
                                    <td class="text-secondary"><?php echo htmlspecialchars($student['gender']); ?></td>
                                    <td>
                                        <?php if ($student['status'] == 'Active'): ?>
                                            <span class="status-badge status-active">Active</span>
                                        <?php else: ?>
                                            <span class="status-badge status-inactive">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="edit.php?id=<?php echo $student['id']; ?>"
                                                class="btn btn-sm btn-outline-secondary border-0 text-secondary" title="Edit">
                                                <span class="material-icons">edit</span>
                                            </a>
                                            <a href="delete.php?id=<?php echo $student['id']; ?>"
                                                class="btn btn-sm btn-outline-danger border-0 text-secondary hover-danger"
                                                onclick="return confirm('Are you sure you want to delete this student?');"
                                                title="Delete">
                                                <span class="material-icons">delete</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-secondary">No students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-4 mt-2">
            <div class="col-md-6 col-lg-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <span class="material-icons">groups</span>
                    </div>
                    <div>
                        <div class="h3 fw-bold mb-0"><?php echo $total_students; ?></div>
                        <div class="small text-secondary text-uppercase fw-bold">Total Students</div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>