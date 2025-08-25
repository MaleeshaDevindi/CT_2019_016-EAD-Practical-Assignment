<?php
session_start();

// Check if message exists
if (isset($_SESSION['message']) && isset($_SESSION['message-type'])) {
    $msg = addslashes($_SESSION['message']);
    $type = $_SESSION['message-type']; // 'success' or 'error'

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
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

// Include database connection
include '../php/config.php';

// Get course ID from URL parameter
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch course details
$sql = "SELECT * FROM courses WHERE id = $course_id";
$result = mysqli_query($conn, $sql);
$course = mysqli_fetch_assoc($result);

// If course not found, redirect
if (!$course) {
    header("Location: all-courses.php");
    exit();
}

// Format semester text
$semesterText = "";
switch($course['semester']) {
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
        $semesterText = $course['semester'];
}

// Fetch assigned staff for this course
$staff_sql = "SELECT s.*, cs.role 
              FROM staff s 
              JOIN course_staff cs ON s.id = cs.staff_id 
              WHERE cs.course_id = $course_id 
              ORDER BY cs.role DESC 
              LIMIT 1";
$staff_result = mysqli_query($conn, $staff_sql);
$instructor = mysqli_fetch_assoc($staff_result);

// Fetch enrolled students count
$student_count_sql = "SELECT COUNT(*) as count FROM enrollments WHERE course_id = $course_id AND status = 'enrolled'";
$student_count_result = mysqli_query($conn, $student_count_sql);
$student_count = mysqli_fetch_assoc($student_count_result)['count'];

// Fetch enrolled students
$students_sql = "SELECT s.*, e.enrolled_at 
                 FROM students s 
                 JOIN enrollments e ON s.id = e.student_id 
                 WHERE e.course_id = $course_id AND e.status = 'enrolled'
                 ORDER BY e.enrolled_at DESC 
                 LIMIT 10";
$students_result = mysqli_query($conn, $students_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $course['title']; ?> - Course Management System</title>
    
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
                    <h1><i class="fas fa-info-circle"></i> Course Details</h1>
                    <p>Comprehensive information about the selected course</p>
                </div>
                <div>
                    <a href="all-courses.php" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Back to Courses
                    </a>
                    <a href="update-course.php?id=<?php echo $course['id']; ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Course
                    </a>
                </div>
            </div>
        </div>

        <!-- Course Overview -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-book"></i> Course Overview</h5>
                            <!-- <span class="badge bg-success fs-6">Active</span> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h2 class="mb-3"><?php echo $course['title']; ?></h2>
                                <div class="course-meta mb-4">
                                    <span class="badge bg-primary me-2 fs-6"><?php echo $course['code']; ?></span>
                                    <span class="badge bg-info me-2 fs-6"><?php echo $course['credits']; ?> Credits</span>
                                    <span class="badge bg-warning text-dark fs-6">
                                        <?php echo ($course['level'] == '3-year') ? '3<sup>rd</sup> Year' : '4<sup>th</sup> Year'; ?>
                                    </span>

                                </div>
                                
                                <h5>Description</h5>
                                <p class="lead" style="text-align:justify;"><?php echo $course['description']; ?></p>
                                
                                <div class="row mt-4">
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-layer-group"></i> Category</h6>
                                        <p><?php echo $course['category']; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6><i class="fas fa-calendar-alt"></i> Semester</h6>
                                        <p><?php echo $semesterText; ?></p>
                                    </div>
                                    <?php if (!empty($course['prerequisites'])): ?>
                                    <div class="col-md-6 mt-3">
                                        <h6><i class="fas fa-tasks"></i> Prerequisites</h6>
                                        <p><?php echo $course['prerequisites']; ?></p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="col-md-6 mt-3">
                                        <h6><i class="fas fa-clock"></i> Created</h6>
                                        <p><?php echo date('F j, Y', strtotime($course['created_at'])); ?></p>
                                    </div>
                                </div>
                                
                                
                            </div>
                            <div class="col-md-4">
                                <div class="course-image-placeholder bg-light rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fa-solid fa-graduation-cap fa-5x text-muted"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Instructor Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-chalkboard-teacher"></i> Instructor</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($instructor): ?>
                        <div class="d-flex align-items-center mb-3">
                            <div class="instructor-avatar bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            <div>
                                <h6 class="mb-0"><?php echo $instructor['first_name'] . ' ' . $instructor['last_name']; ?></h6>
                                <small class="text-muted"><?php echo ucfirst($instructor['role']); ?>, <?php echo $instructor['designation']; ?></small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="mailto:<?php echo $instructor['email']; ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope"></i> Contact
                            </a>
                        </div>
                        <?php else: ?>
                        <div class="text-center text-muted">
                            <i class="fas fa-user-slash fa-2x mb-2"></i>
                            <p>No instructor assigned</p>
                            <a href="assign-staff.php?course_id=<?php echo $course['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-plus"></i> Assign Instructor
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Course Stats -->
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> Course Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="text-center">
                                <div class="fw-bold fs-4"><?php echo $student_count; ?></div>
                                <small class="text-muted">Enrolled Students</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold fs-4"><?php echo $course['credits']; ?></div>
                                <small class="text-muted">Credits</small>
                            </div>
                            <div class="text-center">
                                <div class="fw-bold fs-4"><?php echo $course['category']; ?></div>
                                <small class="text-muted">Category</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Content Tabs -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="courseContentTabs" role="tablist">                                                        
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="students-tab" data-bs-toggle="tab" data-bs-target="#students" type="button" role="tab">
                                    <i class="fas fa-users"></i> Enrolled Students (<?php echo $student_count; ?>)
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="courseContentTabsContent">
                            <!-- Students Tab -->
                            <div class="tab-pane fade show active" id="students" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5>Enrolled Students (<?php echo $student_count; ?>)</h5>
                                    <div>
                                        <button class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i> Export List
                                        </button>
                                    </div>
                                </div>
                                
                                <?php if (mysqli_num_rows($students_result) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Student ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Enrollment Date</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($student = mysqli_fetch_assoc($students_result)): ?>
                                            <tr>
                                                <td><?php echo $student['student_no']; ?></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="student-avatar bg-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                        <?php echo $student['first_name'] . ' ' . $student['last_name']; ?>
                                                    </div>
                                                </td>
                                                <td><?php echo $student['email']; ?></td>
                                                <td><?php echo $student['phone'] ?: 'N/A'; ?></td>
                                                <td><?php echo date('M j, Y', strtotime($student['enrolled_at'])); ?></td>
                                                <td>
                                                    <span class="badge bg-success"><?php echo ucfirst($student['status']); ?></span>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> No students enrolled in this course yet.
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
</body>
</html>