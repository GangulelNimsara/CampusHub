<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../includes/db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$studentsQuery = Database::search("SELECT * FROM `users` ORDER BY `id` DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container-fluid px-4 py-5 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Manage Students 🎓</h2>
                <p class="text-muted small mb-0">View, update, or remove student accounts.</p>
            </div>
            <a href="add.php" class="btn btn-dark rounded-pill px-4 fw-medium">
                <i class="bi bi-person-plus me-1"></i> Add New Student
            </a>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom border-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Mobile</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($studentsQuery && $studentsQuery->num_rows > 0): ?>
                            <?php while ($student = $studentsQuery->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo htmlspecialchars($student['id']); ?></td>
                                    <td><?php echo htmlspecialchars(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')); ?></td>
                                    <td><?php echo htmlspecialchars($student['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($student['mobile'] ?? 'N/A'); ?></td>
                                    <td class="text-end">
                                        <a href="edit.php?id=<?php echo $student['id']; ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3 me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button onclick="deleteStudent(<?php echo $student['id']; ?>);" class="btn btn-danger btn-sm rounded-pill px-3">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No students registered yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        function deleteStudent(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This student account will be deleted permanently!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#000',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData();
                    formData.append("id", id);

                    fetch("delete.php", {
                        method: "POST",
                        body: formData
                    })
                    .then(res => res.text())
                    .then(data => {
                        if (data.trim() === "success") {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Student record has been deleted.',
                                confirmButtonColor: '#000'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.trim(),
                                confirmButtonColor: '#000'
                            });
                        }
                    })
                    .catch(err => console.error(err));
                }
            });
        }
    </script>

</body>

</html>