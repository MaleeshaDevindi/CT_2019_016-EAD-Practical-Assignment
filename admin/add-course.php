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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course - Course Management System</title>
    
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
                    <h1><i class="fas fa-plus-circle"></i> Add New Course</h1>
                    <p>Create a new course by filling out the form below</p>
                </div>
            </div>
        </div>

        <!-- Add Course Form -->
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-book"></i> Course Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Fixed the action attribute to point to your form processor -->
                        <form id="addCourseForm" method="POST" action="php/add-course-function.php" enctype="multipart/form-data">
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
                                    <input type="text" class="form-control" id="courseCode" name="course_code" 
                                           placeholder="e.g., CS101" required>
                                </div>

                                <!-- Credits -->
                                <div class="col-md-6 mb-3">
                                    <label for="credits" class="form-label">Credits *</label>
                                    <input type="number" class="form-control" id="credits" name="credits" 
                                           placeholder="e.g., 1 Credit" required>                                    
                                </div>

                                <!-- Category -->
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="" selected disabled>Select Category</option>
                                        <option value="IT">Information Technology</option>
                                        <option value="SE">Software Engineering</option>
                                        <option value="CS">Computer Science</option>
                                    </select>
                                </div>

                                <!-- Level -->
                                <div class="col-md-6 mb-3">
                                    <label for="level" class="form-label">Level *</label>
                                    <select class="form-select" id="level" name="level" required>
                                        <option value="" selected disabled>Select Level</option>
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
                                        <option value="" selected disabled>Select Semester</option>
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
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Add Course
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <div id="messageContainer" style="display: none;">
                    <div id="successMessage" class="alert alert-success" style="display: none;">
                        <i class="fas fa-check-circle"></i> Course has been added successfully!
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
        document.getElementById('addCourseForm').addEventListener('submit', function(e) {
            // Let the form submit normally since we've fixed the action attribute
            // The validation will be handled by the browser's built-in validation
            // due to the required attributes on the form fields
            
            // If you want to show a success message after form submission,
            // you would need to handle that on the server side (process_course.php)
        });

        // Reset form function
        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All data will be lost.')) {
                document.getElementById('addCourseForm').reset();
            }
        }

        // Auto-generate course code based on category and title
        document.getElementById('category').addEventListener('change', updateCourseCode);
        document.getElementById('courseTitle').addEventListener('input', updateCourseCode);

        function updateCourseCode() {
            const category = document.getElementById('category').value;
            const title = document.getElementById('courseTitle').value;
            
            if (category && title) {
                const categoryMap = {
                    'IT': 'IT',
                    'SE': 'SE',
                    'CS': 'CS'
                };
                
                const prefix = categoryMap[category] || 'GEN';
                const titleWords = title.split(' ');
                const number = Math.floor(Math.random() * 900) + 100; // Random 3-digit number
                
                document.getElementById('courseCode').value = `${prefix}${number}`;
            }
        }
    </script>
</body>
</html>