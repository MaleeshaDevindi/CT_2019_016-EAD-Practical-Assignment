

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile - Student Management System</title>
    
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
            <div class="col-lg-12">
                <div class="page-header border-bottom pb-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h2 mb-1"><i class="fas fa-user-circle me-2"></i>Student Profile</h1>
                            <p class="text-muted mb-0">View and manage student information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="all-students.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Students
                            </a>
                            <button id="editStudentBtn" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading student profile...</p>
        </div>

        <!-- Student Profile Content (will be populated by JavaScript) -->
        <div id="studentContent" style="display: none;">
            <!-- Basic Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Profile Avatar Section -->
                        <div class="col-md-3 text-center mb-4">
                            <div class="profile-avatar bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" id="studentAvatar">
                                <!-- Avatar will be populated -->
                            </div>
                            <h4 id="studentName"></h4>
                            <p class="text-muted" id="studentNumber"></p>
                            <span class="badge fs-6" id="studentStatusBadge">
                                <!-- Status will be populated -->
                            </span>
                        </div>

                        <!-- Student Details -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Student Number</label>
                                    <p class="form-control-plaintext" id="studentNo"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Registration Date</label>
                                    <p class="form-control-plaintext" id="registrationDate"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <p class="form-control-plaintext" id="studentEmail"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Phone</label>
                                    <p class="form-control-plaintext" id="studentPhone"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">NIC</label>
                                    <p class="form-control-plaintext" id="studentNIC"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Date of Birth</label>
                                    <p class="form-control-plaintext" id="studentDOB"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-primary w-100" onclick="sendEmail()">
                                <i class="fas fa-envelope"></i><br>
                                <small>Send Email</small>
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-info w-100" onclick="viewGrades()">
                                <i class="fas fa-chart-line"></i><br>
                                <small>View Grades</small>
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-success w-100" onclick="enrollCourse()">
                                <i class="fas fa-book-open"></i><br>
                                <small>Enroll Course</small>
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-warning w-100" onclick="generateReport()">
                                <i class="fas fa-file-pdf"></i><br>
                                <small>Generate Report</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enrolled Courses Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"><i class="fas fa-book me-2"></i>Enrolled Courses</h5>
                        <button class="btn btn-sm btn-primary" onclick="enrollCourse()">
                            <i class="fas fa-plus me-2"></i>Enroll New Course
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="noCoursesMessage" class="text-center py-4">
                        <i class="fas fa-book-open fa-2x text-muted mb-3"></i>
                        <h6 class="text-muted">No Courses Enrolled</h6>
                        <p class="text-muted">This student is not enrolled in any courses yet.</p>
                    </div>
                    <div id="coursesTableWrapper" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Credits</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="coursesTableBody">
                                    <!-- Courses will be populated here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Academic Summary Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="summary-item p-3 border rounded">
                                <h3 class="text-primary mb-1" id="enrolledCoursesCount">0</h3>
                                <small class="text-muted">Enrolled Courses</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="summary-item p-3 border rounded">
                                <h3 class="text-success mb-1" id="totalCredits">0</h3>
                                <small class="text-muted">Total Credits</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="summary-item p-3 border rounded">
                                <h3 class="text-info mb-1" id="studentGPA">0.0</h3>
                                <small class="text-muted">GPA</small>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="summary-item p-3 border rounded">
                                <h3 class="text-warning mb-1" id="academicYear">1</h3>
                                <small class="text-muted">Academic Year</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="d-flex gap-2 justify-content-end mb-4">
                        <button type="button" class="btn btn-outline-danger" onclick="deleteStudent()">
                            <i class="fas fa-trash me-2"></i>Delete Student
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="changeStatus()">
                            <i class="fas fa-sync-alt me-2"></i>Change Status
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const studentId = new URLSearchParams(window.location.search).get('id');
        let studentData = null;
        let enrolledCourses = [];

        // Load student data on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!studentId) {
                showAlert('Invalid student ID', 'error');
                setTimeout(() => window.location.href = 'all-students.php', 2000);
                return;
            }
            loadStudentData();
            loadEnrolledCourses();
        });

        // Load student data via API
        async function loadStudentData() {
            try {
                const response = await fetch(`../../api/students/read.php?id=${studentId}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    studentData = result.data;
                    displayStudentData();
                } else {
                    showAlert('Student not found', 'error');
                    setTimeout(() => window.location.href = 'all-students.php', 2000);
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            } finally {
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('studentContent').style.display = 'block';
            }
        }

        // Load enrolled courses via API
        async function loadEnrolledCourses() {
            try {
                const response = await fetch(`../../api/students/enrollments.php?student_id=${studentId}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    enrolledCourses = result.data;
                    displayEnrolledCourses();
                    updateAcademicSummary();
                }
            } catch (error) {
                console.error('Error loading enrolled courses:', error);
            }
        }

        // Display student data
        function displayStudentData() {
            if (!studentData) return;

            // Set up edit button
            document.getElementById('editStudentBtn').onclick = () => {
                window.location.href = `edit-student.php?id=${studentId}`;
            };

            // Update basic information
            document.getElementById('studentAvatar').textContent = 
                (studentData.first_name?.charAt(0) || '') + (studentData.last_name?.charAt(0) || '');
            document.getElementById('studentName').textContent = `${studentData.first_name} ${studentData.last_name}`;
            document.getElementById('studentNumber').textContent = studentData.student_no;
            document.getElementById('studentNo').textContent = studentData.student_no;
            document.getElementById('studentEmail').innerHTML = `
                <a href="mailto:${studentData.email}" class="text-decoration-none">
                    <i class="fas fa-envelope me-1"></i>${studentData.email}
                </a>
            `;
            document.getElementById('studentPhone').innerHTML = studentData.phone ? `
                <a href="tel:${studentData.phone}" class="text-decoration-none">
                    <i class="fas fa-phone me-1"></i>${studentData.phone}
                </a>
            ` : '<span class="text-muted">Not provided</span>';
            document.getElementById('studentNIC').textContent = studentData.nic || 'Not provided';
            document.getElementById('studentDOB').textContent = studentData.date_of_birth ? 
                new Date(studentData.date_of_birth).toLocaleDateString() : 'Not provided';
            document.getElementById('registrationDate').textContent = 
                new Date(studentData.created_at).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });

            // Update status badge
            const statusBadge = document.getElementById('studentStatusBadge');
            statusBadge.className = `badge bg-${studentData.status === 'active' ? 'success' : 'danger'} fs-6`;
            statusBadge.innerHTML = `
                <i class="fas fa-${studentData.status === 'active' ? 'check-circle' : 'times-circle'} me-1"></i>
                ${studentData.status.charAt(0).toUpperCase() + studentData.status.slice(1)}
            `;
        }

        // Display enrolled courses
        function displayEnrolledCourses() {
            const noCourses = document.getElementById('noCoursesMessage');
            const coursesWrapper = document.getElementById('coursesTableWrapper');
            const coursesBody = document.getElementById('coursesTableBody');

            if (enrolledCourses.length === 0) {
                noCourses.style.display = 'block';
                coursesWrapper.style.display = 'none';
                return;
            }

            noCourses.style.display = 'none';
            coursesWrapper.style.display = 'block';

            coursesBody.innerHTML = enrolledCourses.map(course => `
                <tr>
                    <td><strong>${course.course_code}</strong></td>
                    <td>${course.course_title}</td>
                    <td>${course.credits}</td>
                    <td>
                        <span class="badge bg-${course.status === 'enrolled' ? 'success' : 'secondary'}">
                            ${course.status.charAt(0).toUpperCase() + course.status.slice(1)}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="viewCourse('${course.course_id}')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        // Update academic summary
        function updateAcademicSummary() {
            document.getElementById('enrolledCoursesCount').textContent = enrolledCourses.length;
            document.getElementById('totalCredits').textContent = enrolledCourses.reduce((sum, course) => sum + (course.credits || 0), 0);
            
            // Calculate academic year based on registration date
            if (studentData && studentData.created_at) {
                const regDate = new Date(studentData.created_at);
                const currentYear = new Date().getFullYear();
                const regYear = regDate.getFullYear();
                const academicYear = currentYear - regYear + 1;
                document.getElementById('academicYear').textContent = academicYear;
            }
        }

        // Quick action functions
        function sendEmail() {
            if (studentData) {
                const subject = 'Message from Student Management System';
                window.location.href = `mailto:${studentData.email}?subject=${encodeURIComponent(subject)}`;
            }
        }

        function viewGrades() {
            window.location.href = `student-grades.php?id=${studentId}`;
        }

        function enrollCourse() {
            window.location.href = `enroll-course.php?student_id=${studentId}`;
        }

        function generateReport() {
            window.open(`generate-student-report.php?id=${studentId}`, '_blank');
        }

        function viewCourse(courseId) {
            window.location.href = `course-details.php?id=${courseId}`;
        }

        async function changeStatus() {
            if (!studentData) return;

            const newStatus = studentData.status === 'active' ? 'suspended' : 'active';
            const result = await Swal.fire({
                title: 'Change Status',
                text: `Change student status from ${studentData.status} to ${newStatus}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('../../api/students/update-status.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: studentId, status: newStatus })
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        showAlert('Status updated successfully!', 'success');
                        await loadStudentData(); // Reload student data
                    } else {
                        showAlert(data.message || 'Failed to update status', 'error');
                    }
                } catch (error) {
                    showAlert('Unable to connect to server', 'error');
                }
            }
        }

        async function deleteStudent() {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the student record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('../../api/students/delete.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: studentId })
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        showAlert('Student deleted successfully!', 'success');
                        setTimeout(() => window.location.href = 'all-students.php', 2000);
                    } else {
                        showAlert(data.message || 'Failed to delete student', 'error');
                    }
                } catch (error) {
                    showAlert('Unable to connect to server', 'error');
                }
            }
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

    <style>
        .profile-avatar {
            width: 120px;
            height: 120px;
            font-size: 48px;
            font-weight: bold;
        }
        
        .summary-item {
            transition: transform 0.2s;
        }
        
        .summary-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .btn-outline-primary, .btn-outline-info, .btn-outline-success, .btn-outline-warning {
            height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .btn-outline-primary i, .btn-outline-info i, .btn-outline-success i, .btn-outline-warning i {
            font-size: 20px;
            margin-bottom: 5px;
        }
    </style>
</body>
</html>