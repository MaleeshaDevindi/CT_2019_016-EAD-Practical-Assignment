<?php


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
    <title>Dashboard - Student Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 -->
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
                            <h1 class="h2 mb-1"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
                            <p class="text-muted mb-0">Welcome to the Student Management System - Overview of your institution</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" onclick="refreshDashboard()">
                                <i class="fas fa-sync-alt me-2"></i>Refresh
                            </button>
                            <button class="btn btn-primary" onclick="exportReport()">
                                <i class="fas fa-download me-2"></i>Export Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics -->
        <div class="card">
            <div class="card-body">
                <div class="row mt-4" id="statsContainer">
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h4 class="text-primary" id="total-students">0</h4>
                            <small class="text-muted">Total Students</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h4 class="text-success" id="total-instructors">0</h4>
                            <small class="text-muted">Total Instructors</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h4 class="text-info" id="total-courses">0</h4>
                            <small class="text-muted">Total Courses</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h4 class="text-warning" id="total-departments">0</h4>
                            <small class="text-muted">Departments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <a href="add-student.php" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                                    <span>Add Student</span>
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="add-instructor.php" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-chalkboard-teacher fa-2x mb-2"></i>
                                    <span>Add Instructor</span>
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="add-course.php" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-book-open fa-2x mb-2"></i>
                                    <span>Add Course</span>
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="#" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                    <span>Enrollment</span>
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="#" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-chart-line fa-2x mb-2"></i>
                                    <span>Reports</span>
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="#" class="btn btn-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-cogs fa-2x mb-2"></i>
                                    <span>Settings</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Row -->
        <div class="row justify-content-center mt-4">
            <!-- Recent Students -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fas fa-user-graduate me-2"></i>Recent Students</h5>
                            <a href="#" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body" id="recent-students-container">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Loading students...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Instructors -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Recent Instructors</h5>
                            <a href="#" class="btn btn-sm btn-primary">View All</a>
                        </div>
                    </div>
                    <div class="card-body" id="recent-instructors-container">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Loading instructors...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Statistics -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>Department Statistics</h5>
                    </div>
                    <div class="card-body" id="department-stats-container">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="text-muted mt-2 mb-0">Loading department statistics...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // API base URL
        const API_BASE = '../../api/';

        // Load dashboard data on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            
            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    card.style.transition = 'all 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

        // Load all dashboard data
        async function loadDashboardData() {
            try {
                await Promise.all([
                    loadStatistics(),
                    loadRecentStudents(),
                    loadRecentInstructors(),
                    loadDepartmentStats()
                ]);
            } catch (error) {
                console.error('Error loading dashboard data:', error);
                showAlert('Failed to load dashboard data. Please try again.', 'error');
            }
        }

        // Load statistics
        async function loadStatistics() {
            try {
                const [studentsResponse, instructorsResponse, coursesResponse] = await Promise.all([
                    fetch(`${API_BASE}students/`),
                    fetch(`${API_BASE}staff/`),
                    fetch(`${API_BASE}courses/`)
                ]);

                const studentsData = await studentsResponse.json();
                const instructorsData = await instructorsResponse.json();
                const coursesData = await coursesResponse.json();
                
                if (studentsData.status === 'success') {
                    const students = studentsData.data || [];
                    document.getElementById('total-students').textContent = students.length;
                }
                
                if (instructorsData.status === 'success') {
                    const instructors = instructorsData.data || [];
                    document.getElementById('total-instructors').textContent = instructors.length;
                    const departments = new Set(instructors.map(i => i.department).filter(Boolean));
                    document.getElementById('total-departments').textContent = departments.size;
                }
                
                if (coursesData.status === 'success') {
                    const courses = coursesData.data || [];
                    document.getElementById('total-courses').textContent = courses.length;
                }
                
            } catch (error) {
                console.error('Error loading statistics:', error);
                throw error;
            }
        }

        // Load recent students
        async function loadRecentStudents() {
            try {
                const response = await fetch(`${API_BASE}students/?limit=5&sort=created_at&order=desc`);
                const data = await response.json();
                const container = document.getElementById('recent-students-container');
                
                if (data.status === 'success' && data.data && data.data.length > 0) {
                    const students = data.data;
                    let html = '<div class="list-group list-group-flush">';
                    
                    students.forEach(student => {
                        const statusClass = student.status === 'active' ? 'success' : 'danger';
                        const initials = (student.first_name ? student.first_name[0] : '') + (student.last_name ? student.last_name[0] : '');
                        const createdDate = student.created_at ? new Date(student.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : 'N/A';
                        
                        html += `
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    ${initials.toUpperCase()}
                                </div>
                                <div>
                                    <h6 class="mb-0">${escapeHtml(student.first_name || '')} ${escapeHtml(student.last_name || '')}</h6>
                                    <small class="text-muted">${escapeHtml(student.student_no || '')}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-${statusClass}">${capitalizeFirst(student.status || '')}</span>
                                <br>
                                <small class="text-muted">${createdDate}</small>
                            </div>
                        </div>
                        `;
                    });
                    
                    html += '</div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Students Found</h5>
                            <p class="text-muted">No students have been registered yet.</p>
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Add First Student
                            </a>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading recent students:', error);
                throw error;
            }
        }

        // Load recent instructors
        async function loadRecentInstructors() {
            try {
                const response = await fetch(`${API_BASE}staff/?limit=5&sort=created_at&order=desc`);
                const data = await response.json();
                const container = document.getElementById('recent-instructors-container');
                
                if (data.status === 'success' && data.data && data.data.length > 0) {
                    const instructors = data.data;
                    let html = '<div class="list-group list-group-flush">';
                    
                    instructors.forEach(instructor => {
                        const statusClasses = {
                            'active': 'success',
                            'inactive': 'danger',
                            'on_leave': 'warning',
                            'retired': 'dark'
                        };
                        const statusClass = statusClasses[instructor.status] || 'secondary';
                        const statusText = instructor.status ? instructor.status.replace('_', ' ') : '';
                        const initials = (instructor.first_name ? instructor.first_name[0] : '') + (instructor.last_name ? instructor.last_name[0] : '');
                        const createdDate = instructor.created_at ? new Date(instructor.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : 'N/A';
                        
                        html += `
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                    ${initials.toUpperCase()}
                                </div>
                                <div>
                                    <h6 class="mb-0">${escapeHtml(instructor.first_name || '')} ${escapeHtml(instructor.last_name || '')}</h6>
                                    <small class="text-muted">${escapeHtml(instructor.instructor_no || '')} | ${escapeHtml(instructor.department || '')}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-${statusClass}">${capitalizeFirst(statusText)}</span>
                                <br>
                                <small class="text-muted">${createdDate}</small>
                            </div>
                        </div>
                        `;
                    });
                    
                    html += '</div>';
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Instructors Found</h5>
                            <p class="text-muted">No instructors have been registered yet.</p>
                            <a href="#" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Add First Instructor
                            </a>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading recent instructors:', error);
                throw error;
            }
        }

        // Load department statistics
        async function loadDepartmentStats() {
            try {
                const response = await fetch(`${API_BASE}staff/`);
                const data = await response.json();
                const container = document.getElementById('department-stats-container');
                
                if (data.status === 'success' && data.data && data.data.length > 0) {
                    const instructors = data.data;
                    
                    // Group by department
                    const departmentCounts = {};
                    instructors.forEach(instructor => {
                        if (instructor.department) {
                            departmentCounts[instructor.department] = (departmentCounts[instructor.department] || 0) + 1;
                        }
                    });
                    
                    // Sort departments by count
                    const sortedDepartments = Object.entries(departmentCounts)
                        .sort((a, b) => b[1] - a[1]);
                    
                    if (sortedDepartments.length > 0) {
                        let html = '<div class="row">';
                        
                        sortedDepartments.forEach(([department, count]) => {
                            const icons = {
                                'Computer Science': 'fas fa-laptop-code',
                                'Mathematics': 'fas fa-calculator',
                                'Physics': 'fas fa-atom',
                                'English': 'fas fa-book'
                            };
                            const icon = icons[department] || 'fas fa-building';
                            
                            html += `
                            <div class="col-md-4 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <div class="mb-2">
                                            <i class="${icon} fa-2x text-primary"></i>
                                        </div>
                                        <h5 class="card-title">${escapeHtml(department)}</h5>
                                        <h3 class="text-primary">${count}</h3>
                                        <small class="text-muted">Instructors</small>
                                    </div>
                                </div>
                            </div>
                            `;
                        });
                        
                        html += '</div>';
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                            <div class="text-center py-5">
                                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Departments Found</h5>
                                <p class="text-muted">No department data available.</p>
                            </div>
                        `;
                    }
                } else {
                    container.innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Departments Found</h5>
                            <p class="text-muted">No department data available.</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading department stats:', error);
                throw error;
            }
        }

        // Refresh dashboard
        function refreshDashboard() {
            Swal.fire({
                title: 'Refreshing Dashboard...',
                html: 'Please wait while we update the data.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            loadDashboardData().then(() => {
                Swal.close();
                showAlert('Dashboard data has been updated.', 'success');
            }).catch(error => {
                Swal.close();
                showAlert('Failed to refresh dashboard data.', 'error');
            });
        }

        // Export report
        function exportReport() {
            showAlert('This feature would export a report in a real application', 'info');
        }

        // Utility functions
        function escapeHtml(text) {
            if (!text) return '';
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
        
        function capitalizeFirst(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1);
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

        // Auto-refresh dashboard every 5 minutes
        setInterval(function() {
            // Silently refresh data in background
            loadDashboardData().catch(error => {
                console.log('Auto-refresh failed:', error);
            });
        }, 300000); // 5 minutes
    </script>

    <style>
        .avatar-sm {
            width: 35px;
            height: 35px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .list-group-item {
            border-left: none;
            border-right: none;
        }

        .list-group-item:first-child {
            border-top: none;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
</body>
</html>