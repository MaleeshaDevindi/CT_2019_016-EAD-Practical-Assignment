
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
        <div class="row mb-4" id="statsContainer">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5>Total Courses</h5>
                                <h2 id="totalCourses">0</h2>
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
                                <h2 id="level3Courses">0</h2>
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
                                <h2 id="level4Courses">0</h2>
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
                <!-- Courses will be loaded via JavaScript -->
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center my-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading courses...</p>
        </div>

        <!-- No Courses Message -->
        <div id="noCoursesMessage" class="alert alert-info" style="display: none;">
            <i class="fas fa-info-circle"></i> No courses found.
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
    
    <script>
        let courses = [];
        let deleteId = null;

        // Load courses on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCourses();
        });

        // Load courses via API
        async function loadCourses() {
            try {
                const response = await fetch('../../api/courses/');
                const result = await response.json();
                
                if (result.status === 'success') {
                    courses = result.data;
                    displayCourses();
                    updateStatistics();
                } else {
                    showAlert('Failed to load courses', 'error');
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            } finally {
                document.getElementById('loadingSpinner').style.display = 'none';
            }
        }

        // Display courses in cards
        function displayCourses() {
            const container = document.getElementById('courseCards');
            
            if (courses.length === 0) {
                document.getElementById('noCoursesMessage').style.display = 'block';
                return;
            }

            container.innerHTML = courses.map(course => `
                <div class="col-lg-4 col-md-6 mb-4 course-item" data-category="${course.category}" data-level="${course.level}">
                    <div class="course-card" style="height:400px;">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="course-title">${course.title}</h5>
                                <div class="course-meta">
                                    <span class="badge bg-primary me-2">${course.code}</span>
                                    <span class="badge bg-info">${course.credits} Credits</span>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-link text-muted" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="course-details.php?id=${course.id}"><i class="fas fa-eye"></i> View Details</a></li>
                                    <li><a class="dropdown-item" href="update-course.php?id=${course.id}"><i class="fas fa-edit"></i> Edit</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteCourse(${course.id})"><i class="fas fa-trash"></i> Delete</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <p class="course-description">${course.description.substring(0, 100)}...</p>
                        
                        <div class="course-meta mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">Category</small>
                                    <div class="fw-bold">${course.category}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Level</small>
                                    <div class="fw-bold">${course.level}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Semester</small>
                                    <div class="fw-bold">${formatSemester(course.semester)}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Prerequisites:</small>
                                <div class="fw-bold">${course.prerequisites || 'None'}</div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="course-details.php?id=${course.id}" class="btn btn-primary btn-sm me-2">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            <a href="update-course.php?id=${course.id}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Format semester text
        function formatSemester(semester) {
            const formats = {
                'first-semster': "1<sup>st</sup> semester",
                'second-semster': "2<sup>nd</sup> semester",
                'any-semster': "Any",
                'both-semsters': "Both"
            };
            return formats[semester] || semester;
        }

        // Update statistics
        function updateStatistics() {
            const total = courses.length;
            const level3 = courses.filter(course => course.level === '3-year').length;
            const level4 = courses.filter(course => course.level === '4-year').length;

            document.getElementById('totalCourses').textContent = total;
            document.getElementById('level3Courses').textContent = level3;
            document.getElementById('level4Courses').textContent = level4;
        }

        // Delete course function
        function deleteCourse(courseId) {
            deleteId = courseId;
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        // Confirm delete
        document.getElementById('confirmDelete').addEventListener('click', async function() {
            if (deleteId) {
                try {
                    const response = await fetch(`../../api/courses/delete.php`, {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: deleteId })
                    });
                    
                    const result = await response.json();
                    
                    if (result.status === 'success') {
                        showAlert('Course deleted successfully!', 'success');
                        await loadCourses(); // Reload courses
                    } else {
                        showAlert(result.message, 'error');
                    }
                } catch (error) {
                    showAlert('Unable to connect to server', 'error');
                }
                
                const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
                deleteModal.hide();
                deleteId = null;
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