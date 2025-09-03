<?php

include "../../api/config.php";

// Get course ID from URL
$course_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($course_id <= 0) {
    $_SESSION['message'] = 'Invalid course ID';
    $_SESSION['message-type'] = 'error';
    header('Location: all-courses.php');
    exit();
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
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                        <form id="updateCourseForm">
                            <input type="hidden" id="course_id" value="<?php echo $course_id; ?>">
                            
                            <div class="row">
                                <!-- Course Title -->
                                <div class="col-md-12 mb-3">
                                    <label for="courseTitle" class="form-label">Course Title *</label>
                                    <input type="text" class="form-control" id="courseTitle" name="course_title" required>
                                    <div class="form-text">Enter a descriptive title for the course</div>
                                </div>

                                <!-- Course Code -->
                                <div class="col-md-6 mb-3">
                                    <label for="courseCode" class="form-label">Course Code *</label>
                                    <input type="text" class="form-control" id="courseCode" name="course_code" required>
                                </div>

                                <!-- Credits -->
                                <div class="col-md-6 mb-3">
                                    <label for="credits" class="form-label">Credits *</label>
                                    <input type="number" class="form-control" id="credits" name="credits" required>                                    
                                </div>

                                <!-- Category -->
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="" disabled>Select Category</option>
                                        <option value="IT">Information Technology</option>
                                        <option value="SE">Software Engineering</option>
                                        <option value="CS">Computer Science</option>
                                    </select>
                                </div>

                                <!-- Level -->
                                <div class="col-md-6 mb-3">
                                    <label for="level" class="form-label">Level *</label>
                                    <select class="form-select" id="level" name="level" required>
                                        <option value="" disabled>Select Level</option>
                                        <option value="3-year">3<sup>rd</sup> year</option>
                                        <option value="4-year">4<sup>th</sup> year</option>                                        
                                    </select>
                                </div>

                                <!-- Prerequisites -->
                                <div class="col-md-6 mb-3">
                                    <label for="prerequisites" class="form-label">Prerequisites</label>
                                    <input type="text" class="form-control" id="prerequisites" name="prerequisites" 
                                           placeholder="List any course prerequisites or requirements...">
                                </div>

                                <!-- Semester -->
                                <div class="col-md-6 mb-3">
                                    <label for="semester" class="form-label">Semester *</label>
                                    <select class="form-select" id="semester" name="semester" required>
                                        <option value="" disabled>Select Semester</option>
                                        <option value="first-semster">1<sup>st</sup> semester</option>
                                        <option value="second-semster">2<sup>nd</sup> semester</option>
                                        <option value="any-semster">Any semester</option>
                                        <option value="both-semsters">Both semesters</option>                                        
                                    </select>
                                </div>

                                <!-- Course Description -->
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label">Course Description *</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="4" required placeholder="Provide a detailed description of the course..."></textarea>
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
        let courseId = <?php echo $course_id; ?>;

        // Load course data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCourseData();
        });

        // Load course data via API
        async function loadCourseData() {
            try {
                const response = await fetch(`../../api/courses/read.php?id=${courseId}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    const course = result.data;
                    populateForm(course);
                } else {
                    showAlert('Failed to load course data', 'error');
                    setTimeout(() => window.location.href = 'all-courses.php', 2000);
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            }
        }

        // Populate form with course data
        function populateForm(course) {
            document.getElementById('courseTitle').value = course.title || '';
            document.getElementById('courseCode').value = course.code || '';
            document.getElementById('credits').value = course.credits || '';
            document.getElementById('category').value = course.category || '';
            document.getElementById('level').value = course.level || '';
            document.getElementById('prerequisites').value = course.prerequisites || '';
            document.getElementById('semester').value = course.semester || '';
            document.getElementById('description').value = course.description || '';
        }

        // Handle form submission
        document.getElementById('updateCourseForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                course_id: courseId,
                course_code: document.getElementById('courseCode').value,
                course_title: document.getElementById('courseTitle').value,
                credits: document.getElementById('credits').value,
                category: document.getElementById('category').value,
                level: document.getElementById('level').value,
                semester: document.getElementById('semester').value,
                prerequisites: document.getElementById('prerequisites').value,
                description: document.getElementById('description').value
            };

            try {
                const response = await fetch('../../api/courses/update.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showAlert('Course updated successfully!', 'success');
                    setTimeout(() => window.location.href = 'all-courses.php', 2000);
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            }
        });

        // Show alert function
        function showAlert(message, type) {
            Swal.fire({
                icon: type,
                title: type === 'success' ? 'Success!' : 'Oops!',
                text: message,
                timer: type === 'success' ? 2000 : null,
                showConfirmButton: type !== 'success'
            });
        }
    </script>
</body>
</html>