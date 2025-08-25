<?php
session_start();

// Include database connection
include '../php/config.php';

// Get course ID from URL
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch course data if ID is valid
$course = null;
if ($course_id > 0) {
    $sql = "SELECT * FROM courses WHERE id = $course_id";
    $result = mysqli_query($conn, $sql);
    $course = mysqli_fetch_assoc($result);
    
    if (!$course) {
        $_SESSION['message'] = 'Course not found';
        $_SESSION['message-type'] = 'error';
        header('Location: all-courses.php');
        exit();
    }
} else {
    $_SESSION['message'] = 'Invalid course ID';
    $_SESSION['message-type'] = 'error';
    header('Location: all-courses.php');
    exit();
}

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course - Course Management System</title>
    
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
         <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="page-header">
                    <h1><i class="fas fa-edit"></i> Update Course</h1>
                    <p>Update the course information using the form below</p>
                </div>
            </div>
        </div>

        <!-- Update Course Form -->
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-book"></i> Course Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Fixed the action attribute to point to your update processor -->
                        <form id="updateCourseForm" method="POST" action="php/update-course-function.php" enctype="multipart/form-data">
                            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
                            
                            <div class="row">
                                <!-- Course Title -->
                                <div class="col-md-12 mb-3">
                                    <label for="courseTitle" class="form-label">Course Title *</label>
                                    <input type="text" class="form-control" id="courseTitle" name="course_title" 
                                           value="<?php echo htmlspecialchars($course['title']); ?>" required>
                                    <div class="form-text">Enter a descriptive title for the course</div>
                                </div>

                                <!-- Course Code -->
                                <div class="col-md-6 mb-3">
                                    <label for="courseCode" class="form-label">Course Code *</label>
                                    <input type="text" class="form-control" id="courseCode" name="course_code" 
                                           value="<?php echo htmlspecialchars($course['code']); ?>" required>
                                </div>

                                <!-- Credits -->
                                <div class="col-md-6 mb-3">
                                    <label for="credits" class="form-label">Credits *</label>
                                    <input type="number" class="form-control" id="credits" name="credits" 
                                           value="<?php echo htmlspecialchars($course['credits']); ?>" required>                                    
                                </div>

                                <!-- Category -->
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="" disabled>Select Category</option>
                                        <option value="IT" <?php echo ($course['category'] == 'IT') ? 'selected' : ''; ?>>Information Technology</option>
                                        <option value="SE" <?php echo ($course['category'] == 'SE') ? 'selected' : ''; ?>>Software Engineering</option>
                                        <option value="CS" <?php echo ($course['category'] == 'CS') ? 'selected' : ''; ?>>Computer Science</option>
                                    </select>
                                </div>

                                <!-- Level -->
                                <div class="col-md-6 mb-3">
                                    <label for="level" class="form-label">Level *</label>
                                    <select class="form-select" id="level" name="level" required>
                                        <option value="" disabled>Select Level</option>
                                        <option value="3-year" <?php echo ($course['level'] == '3-year') ? 'selected' : ''; ?>>3<sup>rd</sup> year</option>
                                        <option value="4-year" <?php echo ($course['level'] == '4-year') ? 'selected' : ''; ?>>4<sup>th</sup> year</option>                                        
                                    </select>
                                </div>

                                <!-- Prerequisites -->
                                <div class="col-md-6 mb-3">
                                    <label for="prerequisites" class="form-label">Prerequisites</label>
                                    <input type="text" class="form-control" id="prerequisites" name="prerequisites" 
                                           value="<?php echo htmlspecialchars($course['prerequisites']); ?>"
                                           placeholder="List any course prerequisites or requirements...">
                                </div>

                                <!-- Semester -->
                                <div class="col-md-6 mb-3">
                                    <label for="semester" class="form-label">Semester *</label>
                                    <select class="form-select" id="semester" name="semester" required>
                                        <option value="" disabled>Select Semester</option>
                                        <option value="first-semster" <?php echo ($course['semester'] == 'first-semster') ? 'selected' : ''; ?>>1<sup>st</sup> semester</option>
                                        <option value="second-semster" <?php echo ($course['semester'] == 'second-semster') ? 'selected' : ''; ?>>2<sup>nd</sup> semester</option>
                                        <option value="any-semster" <?php echo ($course['semester'] == 'any-semster') ? 'selected' : ''; ?>>Any semester</option>
                                        <option value="both-semsters" <?php echo ($course['semester'] == 'both-semsters') ? 'selected' : ''; ?>>Both semesters</option>                                        
                                    </select>
                                </div>

                                <!-- Course Description -->
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Course Description *</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="4" required placeholder="Provide a detailed description of the course..."><?php echo htmlspecialchars($course['description']); ?></textarea>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="all-courses.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Courses
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Course
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <div id="messageContainer" style="display: none;">
                    <div id="successMessage" class="alert alert-success" style="display: none;">
                        <i class="fas fa-check-circle"></i> Course has been updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    <div id="errorMessage" class="alert alert-danger" style="display: none;">
                        <i class="fas fa-exclamation-triangle"></i> <span id="errorText"></span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

        // Form validation and submission
        document.getElementById('updateCourseForm').addEventListener('submit', function(e) {
            // Let the form submit normally since we've fixed the action attribute
            // The validation will be handled by the browser's built-in validation
            // due to the required attributes on the form fields
        });
    </script>
</body>
</html>