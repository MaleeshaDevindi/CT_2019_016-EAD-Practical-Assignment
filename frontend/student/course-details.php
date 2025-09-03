<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details - Course Management System</title>
    
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
        <div class="page-header border-bottom pb-3 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1"><i class="fas fa-info-circle me-2"></i>Course Details</h1>
                    <p class="text-muted mb-0">Comprehensive information about the selected course</p>
                </div>
                <div>
                    <a href="all-courses.php" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Courses
                    </a>
                    <button id="editCourseBtn" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Edit Course
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading course details...</p>
        </div>

        <!-- Course Content (will be populated by JavaScript) -->
        <div id="courseContent" style="display: none;">
            <!-- Course Overview -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0"><i class="fas fa-book me-2"></i>Course Overview</h5>
                                <span id="courseStatusBadge" class="badge bg-success fs-6"></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2 class="mb-3" id="courseTitle"></h2>
                                    <div class="course-meta mb-4" id="courseMeta"></div>
                                    
                                    <h5>Description</h5>
                                    <p class="lead" id="courseDescription" style="text-align:justify;"></p>
                                    
                                    <div class="row mt-4">
                                        <div class="col-md-6">
                                            <h6><i class="fas fa-layer-group me-2"></i>Category</h6>
                                            <p id="courseCategory"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <h6><i class="fas fa-calendar-alt me-2"></i>Semester</h6>
                                            <p id="courseSemester"></p>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <h6><i class="fas fa-tasks me-2"></i>Prerequisites</h6>
                                            <p id="coursePrerequisites"></p>
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <h6><i class="fas fa-clock me-2"></i>Created</h6>
                                            <p id="courseCreatedAt"></p>
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
                
                <div class="row">
                    <!-- Instructor Info -->
                    <div class="col-lg-6 card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Instructor</h5>
                        </div>
                        <div class="card-body" id="instructorInfo">
                            <div class="text-center text-muted">
                                <i class="fas fa-user-slash fa-2x mb-2"></i>
                                <p>No instructor assigned</p>
                                <button class="btn btn-sm btn-outline-primary" id="assignInstructorBtn">
                                    <i class="fas fa-plus me-2"></i>Assign Instructor
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Course Stats -->
                    <div class="col-lg-6 card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Course Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3" id="courseStats">
                                <div class="text-center">
                                    <div class="fw-bold fs-4" id="studentCount">0</div>
                                    <small class="text-muted">Enrolled Students</small>
                                </div>
                                <div class="text-center">
                                    <div class="fw-bold fs-4" id="courseCredits">0</div>
                                    <small class="text-muted">Credits</small>
                                </div>
                                <div class="text-center">
                                    <div class="fw-bold fs-4" id="courseCategoryStat"></div>
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
                                        <i class="fas fa-users me-2"></i>Enrolled Students (<span id="studentCountBadge">0</span>)
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="courseContentTabsContent">
                                <!-- Students Tab -->
                                <div class="tab-pane fade show active" id="students" role="tabpanel">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5>Enrolled Students (<span id="studentCountText">0</span>)</h5>
                                        <div>
                                            <button class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-download me-2"></i>Export List
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div id="studentsTableContainer">
                                        <div class="alert alert-info" id="noStudentsAlert">
                                            <i class="fas fa-info-circle me-2"></i>No students enrolled in this course yet.
                                        </div>
                                        <div class="table-responsive" id="studentsTableWrapper" style="display: none;">
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
                                                <tbody id="studentsTableBody">
                                                    <!-- Students will be loaded here -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
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
        const courseId = new URLSearchParams(window.location.search).get('id');
        
        // Load course data on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!courseId) {
                showAlert('Invalid course ID', 'error');
                setTimeout(() => window.location.href = 'all-courses.php', 2000);
                return;
            }
            loadCourseData();
            loadEnrolledStudents();
        });

        // Load course data via API
        async function loadCourseData() {
            try {
                const response = await fetch(`../../api/courses/read.php?id=${courseId}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    displayCourseData(result.data);
                } else {
                    showAlert('Course not found', 'error');
                    setTimeout(() => window.location.href = 'all-courses.php', 2000);
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            } finally {
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('courseContent').style.display = 'block';
            }
        }

        // Load enrolled students via API
        async function loadEnrolledStudents() {
            try {
                const response = await fetch(`../../api/courses/enrollments.php?course_id=${courseId}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    displayEnrolledStudents(result.data);
                    updateStudentCount(result.data.length);
                }
            } catch (error) {
                console.error('Error loading enrolled students:', error);
            }
        }

        // Display course data
        function displayCourseData(course) {
            document.getElementById('courseTitle').textContent = course.title;
            document.getElementById('courseDescription').textContent = course.description;
            document.getElementById('courseCategory').textContent = course.category;
            document.getElementById('courseCategoryStat').textContent = course.category;
            document.getElementById('courseCredits').textContent = course.credits;
            document.getElementById('coursePrerequisites').textContent = course.prerequisites || 'None';
            document.getElementById('courseCreatedAt').textContent = new Date(course.created_at).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric' 
            });

            // Format semester
            const semesterFormats = {
                'first-semster': "1<sup>st</sup> semester",
                'second-semster': "2<sup>nd</sup> semester",
                'any-semster': "Any semester",
                'both-semsters': "Both semesters"
            };
            document.getElementById('courseSemester').innerHTML = semesterFormats[course.semester] || course.semester;

            // Update course meta
            document.getElementById('courseMeta').innerHTML = `
                <span class="badge bg-primary me-2 fs-6">${course.code}</span>
                <span class="badge bg-info me-2 fs-6">${course.credits} Credits</span>
                <span class="badge bg-warning text-dark fs-6">
                    ${course.level === '3-year' ? '3<sup>rd</sup> Year' : '4<sup>th</sup> Year'}
                </span>
            `;

            // Set up edit button
            document.getElementById('editCourseBtn').onclick = () => {
                window.location.href = `update-course.php?id=${courseId}`;
            };
        }

        // Display enrolled students
        function displayEnrolledStudents(students) {
            const container = document.getElementById('studentsTableWrapper');
            const noStudentsAlert = document.getElementById('noStudentsAlert');
            const tableBody = document.getElementById('studentsTableBody');

            if (students.length === 0) {
                noStudentsAlert.style.display = 'block';
                container.style.display = 'none';
                return;
            }

            noStudentsAlert.style.display = 'none';
            container.style.display = 'block';

            tableBody.innerHTML = students.map(student => `
                <tr>
                    <td>${student.student_no}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="student-avatar bg-success rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                            ${student.first_name} ${student.last_name}
                        </div>
                    </td>
                    <td>${student.email}</td>
                    <td>${student.phone || 'N/A'}</td>
                    <td>${new Date(student.enrolled_at).toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'short', 
                        day: 'numeric' 
                    })}</td>
                    <td>
                        <span class="badge bg-success">${student.status.charAt(0).toUpperCase() + student.status.slice(1)}</span>
                    </td>
                </tr>
            `).join('');
        }

        // Update student count in multiple places
        function updateStudentCount(count) {
            document.getElementById('studentCount').textContent = count;
            document.getElementById('studentCountBadge').textContent = count;
            document.getElementById('studentCountText').textContent = count;
        }

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