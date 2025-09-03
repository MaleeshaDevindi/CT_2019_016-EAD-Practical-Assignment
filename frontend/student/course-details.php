<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details - Student Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .enroll-btn-container {
            position: sticky;
            top: 20px;
            z-index: 100;
        }
    </style>
</head>
<body>
    <!-- Include Student Sidebar -->
    <?php include 'sidemenu.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header border-bottom pb-3 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1"><i class="fas fa-info-circle me-2"></i>Course Details</h1>
                    <p class="text-muted mb-0">Course information and enrollment</p>
                </div>
                <div>
                    <a href="all-courses.php" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left me-2"></i>Back to Courses
                    </a>
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
            <div class="row">
                <!-- Left Column - Course Details -->
                <div class="col-lg-8">
                    <!-- Course Overview -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0"><i class="fas fa-book me-2"></i>Course Overview</h5>
                                <span id="enrollmentStatusBadge" class="badge bg-success fs-6">Not Enrolled</span>
                            </div>
                        </div>
                        <div class="card-body">
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
                                    <h6><i class="fas fa-clock me-2"></i>Duration</h6>
                                    <p id="courseDuration"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Enrollment & Info -->
                <div class="col-lg-4">
                    <!-- Enrollment Card -->
                    <div class="card mb-4 enroll-btn-container">
                        <div class="card-body text-center">
                            <div id="enrollmentSection">
                                <h5>Enroll in this Course</h5>
                                <p class="text-muted">Gain access to all course materials and start learning</p>
                                <button id="enrollBtn" class="btn btn-primary btn-lg w-100 mb-3">
                                    <i class="fas fa-plus me-2"></i>Enroll Now
                                </button>
                            </div>
                            
                            <div id="progressSection" style="display: none;">
                                <h5>Your Progress</h5>
                                <div class="progress mb-2">
                                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <p class="mb-3"><span id="progressPercent">0</span>% Complete</p>
                                <a href="#" id="continueLearningBtn" class="btn btn-success w-100">
                                    <i class="fas fa-play-circle me-2"></i>Continue Learning
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Course Info Card - Only Level and Credits -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Course Information</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-trophy me-2"></i>Level</span>
                                    <span id="courseLevel">N/A</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-graduation-cap me-2"></i>Credits</span>
                                    <span id="courseCredits">0</span>
                                </li>
                            </ul>
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
        let isEnrolled = false;
        let courseData = null;

        // Load course data on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!courseId) {
                showAlert('Invalid course ID', 'error');
                setTimeout(() => window.location.href = 'all-courses.php', 2000);
                return;
            }
            
            checkEnrollmentStatus();
            loadCourseData();
        });

        // Check if student is enrolled in this course
        async function checkEnrollmentStatus() {
            try {
                const response = await fetch('../../api/students/enrollments.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include' // Include cookies for session
                });
                const result = await response.json();
                
                if (result.status === 'success') {
                    const enrolledCourses = result.data.map(course => course.course_id);
                    isEnrolled = enrolledCourses.includes(courseId.toString());
                    updateEnrollmentUI();
                } else {
                    showAlert(result.message || 'Error checking enrollment status', 'error');
                }
            } catch (error) {
                console.error('Error checking enrollment status:', error);
                showAlert('Error connecting to server', 'error');
            }
        }

        // Load course data via API
        async function loadCourseData() {
            try {
                const response = await fetch(`../../api/courses/read.php?id=${courseId}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    courseData = result.data;
                    displayCourseData(courseData);
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

        // Display course data
        function displayCourseData(course) {
            document.getElementById('courseTitle').textContent = course.title;
            document.getElementById('courseDescription').textContent = course.description;
            document.getElementById('courseCategory').textContent = course.category;
            document.getElementById('coursePrerequisites').textContent = course.prerequisites || 'None';
            document.getElementById('courseDuration').textContent = course.duration || 'Not specified';
            document.getElementById('courseLevel').textContent = course.level === '3-year' ? 'Level III' : 'Level IV';
            document.getElementById('courseCredits').textContent = course.credits;

            // Format semester
            const semesterFormats = {
                'first-semster': "1st Semester",
                'second-semster': "2nd Semester",
                'any-semster': "Any Semester",
                'both-semsters': "Both Semesters"
            };
            document.getElementById('courseSemester').textContent = semesterFormats[course.semester] || course.semester;

            // Update course meta
            document.getElementById('courseMeta').innerHTML = `
                <span class="badge bg-primary me-2 fs-6">${course.code}</span>
                <span class="badge bg-info me-2 fs-6">${course.credits} Credits</span>
                <span class="badge bg-warning text-dark fs-6">
                    ${course.level === '3-year' ? 'Level III' : 'Level IV'}
                </span>
            `;
        }

        // Update UI based on enrollment status
        function updateEnrollmentUI() {
            const enrollmentSection = document.getElementById('enrollmentSection');
            const progressSection = document.getElementById('progressSection');
            const statusBadge = document.getElementById('enrollmentStatusBadge');
            const enrollBtn = document.getElementById('enrollBtn');
            
            if (isEnrolled) {
                enrollmentSection.style.display = 'none';
                progressSection.style.display = 'block';
                statusBadge.textContent = 'Enrolled';
                statusBadge.className = 'badge bg-success fs-6';
                enrollBtn.disabled = true;
                
                // Set up continue learning button
                document.getElementById('continueLearningBtn').href = `learning.php?course_id=${courseId}`;
                
                // Simulate progress (in a real app, this would come from the API)
                const progress = 25; // 25% progress
                document.getElementById('progressBar').style.width = `${progress}%`;
                document.getElementById('progressPercent').textContent = progress;
            } else {
                enrollmentSection.style.display = 'block';
                progressSection.style.display = 'none';
                statusBadge.textContent = 'Not Enrolled';
                statusBadge.className = 'badge bg-secondary fs-6';
                enrollBtn.disabled = false;
            }
        }

        // Enroll in course
        async function enrollInCourse() {
            try {
                const response = await fetch('../../api/students/enrollments.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    credentials: 'include', // Include cookies for session
                    body: JSON.stringify({
                        course_id: courseId
                    })
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    isEnrolled = true;
                    updateEnrollmentUI();
                    showAlert('Successfully enrolled in the course!', 'success');
                } else {
                    showAlert(result.message || 'Failed to enroll in course', 'error');
                }
            } catch (error) {
                showAlert('Error enrolling in course', 'error');
            }
        }

        // Set up event listeners
        document.getElementById('enrollBtn').addEventListener('click', enrollInCourse);

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