<?php

include "../php/config.php";
session_start();
$_SESSION['message']="Successfully Added"; $_SESSION['message-type']="success";
// Check if message exists
if (isset($_SESSION['message']) && isset($_SESSION['message-type'])) {
    $msg = addslashes($_SESSION['message']);
    $type = $_SESSION['message-type']; // 'success' or 'error'

    
    echo "<script>
        Swal.fire({
            icon: '$type',
            title: '".($type === 'success' ? 'Success!' : 'Oops!')."',
            text: '$msg',
            ".($type === 'success' ? "timer: 2000, showConfirmButton: false" : "showConfirmButton: true")."
        });
    </script>";

    // Clear session messages
    unset($_SESSION['message']);
    unset($_SESSION['message-type']);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Courses - Course Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Include Sidebar -->
    <?php include 'sidemenu.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1><i class="fas fa-book-open"></i> All Courses</h1>
                    <p>Manage and view all available courses</p>
                </div>
                <div>
                    <a href="add-course.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Course
                    </a>
                </div>
            </div>
        </div>

        <!-- Course Statistics -->
        <!-- <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Total Courses</h5>
                                <h2 id="totalCourses">24</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-book fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Active Courses</h5>
                                <h2 id="activeCourses">18</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-play-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Draft Courses</h5>
                                <h2 id="draftCourses">4</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-edit fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Inactive Courses</h5>
                                <h2 id="inactiveCourses">2</h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-pause-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Total Courses</h5>
                        <h2><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM courses")); ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Level III Courses</h5>
                        <h2><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM courses WHERE `level` = '3-year'")); ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-play-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5>Level IV Courses</h5>
                        <h2><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT * FROM courses WHERE `level` = '4-year'")); ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-edit fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

        <!-- Course Cards View -->
        <div id="courseCardsContainer">
            <div class="row" id="courseCards">
                <?php
    $sql = "SELECT `id`, `code`, `title`, `description`, `credits`, `category`, `level`, `semester`, `prerequisites`, `created_at` FROM `courses`";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Format the semester text
            $semesterText = "";
            switch($row['semester']) {
                case 'first-semster':
                    $semesterText = "1<sup>st</sup> semester";
                    break;
                case 'second-semster':
                    $semesterText = "2<sup>nd</sup> semester";
                    break;
                case 'any-semster':
                    $semesterText = "Any semester";
                    break;
                case 'both-semsters':
                    $semesterText = "Both semesters";
                    break;
                default:
                    $semesterText = $row['semester'];
            }
?>
<!-- Course Card -->
<div class="col-lg-4 col-md-6 mb-4 course-item"  data-category="<?php echo $row['category']; ?>" data-status="active" data-level="<?php echo $row['level']; ?>">
    <div class="course-card"  style="height:400px;">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h5 class="course-title"><?php echo $row['title']; ?></h5>
                <div class="course-meta">
                    <span class="badge bg-primary me-2"><?php echo $row['code']; ?></span>
                    <span class="badge bg-info"><?php echo $row['credits']; ?> Credits</span>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-link text-muted" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="course-details.php?id=<?php echo $row['id']; ?>"><i class="fas fa-eye"></i> View Details</a></li>
                    <li><a class="dropdown-item" href="update-course.php?id=<?php echo $row['id']; ?>"><i class="fas fa-edit"></i> Edit</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteCourse(<?php echo $row['id']; ?>)"><i class="fas fa-trash"></i> Delete</a></li>
                </ul>
            </div>
        </div>
        
        <p class="course-description"><?php echo substr($row['description'], 0, 100); ?>...</p>
        
        <div class="course-meta mb-3">
            <div class="row text-center">
                <div class="col-4">
                    <small class="text-muted">Category</small>
                    <div class="fw-bold"><?php echo $row['category']; ?></div>
                </div>
                <div class="col-4">
                    <small class="text-muted">Level</small>
                    <div class="fw-bold"><?php echo $row['level']; ?></div>
                </div>
                <div class="col-4">
                    <small class="text-muted">Semester</small>
                    <div class="fw-bold"><?php echo $semesterText; ?></div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">Prerequisites:</small>
                <div class="fw-bold"><?php echo !empty($row['prerequisites']) ? $row['prerequisites'] : 'None'; ?></div>
            </div>
            <!-- <span class="badge bg-success">Active</span> -->
        </div>
        
        <div class="mt-3">
            <a href="course-details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm me-2">
                <i class="fas fa-eye"></i> View Details
            </a>
            <a href="update-course.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>
</div>
<?php
        }
    } else {
        echo '<div class="col-12"><div class="alert alert-info">No courses found.</div></div>';
    }
?>

            </div>
        </div>

        
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle text-danger"></i> Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this course? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete Course</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        let deleteId = null;

        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        // View toggle functionality
        document.getElementById('cardView').addEventListener('click', function() {
            showCardView();
        });

        document.getElementById('tableView').addEventListener('click', function() {
            showTableView();
        });

        function showCardView() {
            document.getElementById('courseCardsContainer').style.display = 'block';
            document.getElementById('courseTableContainer').style.display = 'none';
            document.getElementById('cardView').classList.add('active');
            document.getElementById('tableView').classList.remove('active');
        }

        function showTableView() {
            document.getElementById('courseCardsContainer').style.display = 'none';
            document.getElementById('courseTableContainer').style.display = 'block';
            document.getElementById('tableView').classList.add('active');
            document.getElementById('cardView').classList.remove('active');
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', filterCourses);
        document.getElementById('categoryFilter').addEventListener('change', filterCourses);
        document.getElementById('statusFilter').addEventListener('change', filterCourses);
        document.getElementById('levelFilter').addEventListener('change', filterCourses);
        document.getElementById('sortBy').addEventListener('change', sortCourses);

        document.getElementById('clearSearch').addEventListener('click', function() {
            document.getElementById('searchInput').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('levelFilter').value = '';
            filterCourses();
        });

        function filterCourses() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;
            const levelFilter = document.getElementById('levelFilter').value;

            // Filter cards
            const courseCards = document.querySelectorAll('.course-item');
            let visibleCount = 0;

            courseCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                const category = card.getAttribute('data-category');
                const status = card.getAttribute('data-status');
                const level = card.getAttribute('data-level');

                let showCard = true;

                if (searchTerm && !text.includes(searchTerm)) {
                    showCard = false;
                }
                if (categoryFilter && category !== categoryFilter) {
                    showCard = false;
                }
                if (statusFilter && status !== statusFilter) {
                    showCard = false;
                }
                if (levelFilter && level !== levelFilter) {
                    showCard = false;
                }

                card.style.display = showCard ? 'block' : 'none';
                if (showCard) visibleCount++;
            });

            // Filter table rows
            const tableRows = document.querySelectorAll('.course-row');
            let visibleRowCount = 0;

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const category = row.getAttribute('data-category');
                const status = row.getAttribute('data-status');
                const level = row.getAttribute('data-level');

                let showRow = true;

                if (searchTerm && !text.includes(searchTerm)) {
                    showRow = false;
                }
                if (categoryFilter && category !== categoryFilter) {
                    showRow = false;
                }
                if (statusFilter && status !== statusFilter) {
                    showRow = false;
                }
                if (levelFilter && level !== levelFilter) {
                    showRow = false;
                }

                row.style.display = showRow ? '' : 'none';
                if (showRow) visibleRowCount++;
            });

            // Update count
            document.getElementById('showingCount').textContent = Math.max(visibleCount, visibleRowCount);
        }

        function sortCourses() {
            const sortBy = document.getElementById('sortBy').value;
            // Sorting logic would be implemented here
            console.log('Sorting by:', sortBy);
        }

        // Delete course functionality
        function deleteCourse(courseId) {
            deleteId = courseId;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteId) {
                // In a real application, this would make an AJAX call to delete the course
                console.log('Deleting course with ID:', deleteId);
                
                // Remove from UI (for demonstration)
                const cardToRemove = document.querySelector(`[onclick="deleteCourse(${deleteId})"]`).closest('.course-item');
                const rowToRemove = document.querySelector(`button[onclick="deleteCourse(${deleteId})"]`).closest('tr');
                
                if (cardToRemove) cardToRemove.remove();
                if (rowToRemove) rowToRemove.remove();
                
                // Update statistics
                updateStatistics();
                
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                deleteModal.hide();
                
                // Show success message
                showAlert('Course deleted successfully!', 'success');
                
                deleteId = null;
            }
        });

        function updateStatistics() {
            // Update course count statistics
            const totalCards = document.querySelectorAll('.course-item').length;
            const activeCards = document.querySelectorAll('.course-item[data-status="active"]').length;
            const draftCards = document.querySelectorAll('.course-item[data-status="draft"]').length;
            const inactiveCards = document.querySelectorAll('.course-item[data-status="inactive"]').length;

            document.getElementById('totalCourses').textContent = totalCards;
            document.getElementById('activeCourses').textContent = activeCards;
            document.getElementById('draftCourses').textContent = draftCards;
            document.getElementById('inactiveCourses').textContent = inactiveCards;
            document.getElementById('totalCount').textContent = totalCards;
            document.getElementById('showingCount').textContent = totalCards;
        }

        function showAlert(message, type) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.querySelector('.main-content').insertBefore(alertDiv, document.querySelector('.page-header').nextSibling);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alertDiv) alertDiv.remove();
            }, 5000);
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            filterCourses(); // Initial filter to set counts
        });
    </script>
</body>
</html>