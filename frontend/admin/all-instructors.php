<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Instructors - Instructor Management System</title>
    
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
                            <h1 class="h2 mb-1"><i class="fas fa-chalkboard-teacher me-2"></i>All Instructors</h1>
                            <p class="text-muted mb-0">Manage and view all registered instructors</p>
                        </div>
                        <div>
                            <a href="add-instructor.php" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Add New Instructor
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Summary Statistics -->
                <div class="row mt-4" id="statsContainer" style="display: none;">
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h4 class="text-primary" id="totalInstructors">0</h4>
                            <small class="text-muted">Total Instructors</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h4 class="text-success" id="activeInstructors">0</h4>
                            <small class="text-muted">Active</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h4 class="text-warning" id="onLeaveInstructors">0</h4>
                            <small class="text-muted">On Leave</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 border rounded">
                            <h4 class="text-info" id="totalDepartments">0</h4>
                            <small class="text-muted">Departments</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructors List -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fas fa-user-tie me-2"></i>Instructors List</h5>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search instructors..." style="width: 250px;">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Loading Spinner -->
                        <div id="loadingSpinner" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading instructors...</p>
                        </div>

                        <!-- No Instructors Message -->
                        <div id="noInstructorsMessage" class="text-center py-5" style="display: none;">
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Instructors Found</h5>
                            <p class="text-muted">No instructors have been registered yet.</p>
                            <a href="add-instructor.php" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Add First Instructor
                            </a>
                        </div>

                        <!-- Instructors Table -->
                        <div id="instructorsTableContainer" class="table-responsive" style="display: none;">
                            <table class="table table-striped table-hover" id="instructorsTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Instructor Details</th>
                                        <th>Contact</th>
                                        <th>Department</th>
                                        <th>Designation</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="instructorsTableBody">
                                    <!-- Instructors will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let instructors = [];

        // Load instructors on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadInstructors();
        });

        // Load instructors via API
        async function loadInstructors() {
            try {
                const response = await fetch('../../api/staff/');
                const result = await response.json();
                
                if (result.status === 'success') {
                    instructors = result.data;
                    displayInstructors();
                    updateStatistics();
                    initSearch();
                } else {
                    showAlert('Failed to load instructors', 'error');
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            } finally {
                document.getElementById('loadingSpinner').style.display = 'none';
            }
        }

        // Display instructors in table
        function displayInstructors() {
            const container = document.getElementById('instructorsTableContainer');
            const noInstructors = document.getElementById('noInstructorsMessage');
            const tableBody = document.getElementById('instructorsTableBody');

            if (instructors.length === 0) {
                noInstructors.style.display = 'block';
                container.style.display = 'none';
                document.getElementById('statsContainer').style.display = 'none';
                return;
            }

            noInstructors.style.display = 'none';
            container.style.display = 'block';
            document.getElementById('statsContainer').style.display = 'flex';

            tableBody.innerHTML = instructors.map((instructor, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                ${(instructor.first_name?.charAt(0) || '') + (instructor.last_name?.charAt(0) || '')}
                            </div>
                            <div>
                                <div class="fw-bold">${instructor.first_name} ${instructor.last_name}</div>
                                <small class="text-muted">
                                    ${instructor.instructor_no}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <a href="mailto:${instructor.email}" class="text-decoration-none d-block">
                                <i class="fas fa-envelope me-1"></i>
                                ${instructor.email}
                            </a>
                            ${instructor.phone ? `
                                <a href="tel:${instructor.phone}" class="text-decoration-none d-block">
                                    <i class="fas fa-phone me-1"></i>
                                    <small>${instructor.phone}</small>
                                </a>
                            ` : ''}
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-info text-dark">
                            ${instructor.department}
                        </span>
                    </td>
                    <td>
                        <small>${instructor.designation}</small>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewInstructor(${instructor.id})" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editInstructor(${instructor.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="assignCourses(${instructor.id})" title="Assign Courses">
                                <i class="fas fa-book"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteInstructor(${instructor.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        // Update statistics
        function updateStatistics() {
            const total = instructors.length;
            const active = instructors.filter(i => i.status === 'active').length;
            const onLeave = instructors.filter(i => i.status === 'on_leave').length;
            const departments = new Set(instructors.map(i => i.department)).size;

            document.getElementById('totalInstructors').textContent = total;
            document.getElementById('activeInstructors').textContent = active;
            document.getElementById('onLeaveInstructors').textContent = onLeave;
            document.getElementById('totalDepartments').textContent = departments;
        }

        // Initialize search functionality
        function initSearch() {
            document.getElementById('searchInput').addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.getElementById('instructorsTableBody').getElementsByTagName('tr');

                for (let row of rows) {
                    const name = row.cells[1].textContent.toLowerCase();
                    const email = row.cells[2].textContent.toLowerCase();
                    const department = row.cells[3].textContent.toLowerCase();
                    const designation = row.cells[4].textContent.toLowerCase();

                    const matchesSearch = name.includes(searchTerm) || 
                                         email.includes(searchTerm) || 
                                         department.includes(searchTerm) || 
                                         designation.includes(searchTerm);

                    row.style.display = matchesSearch ? '' : 'none';
                }
            });
        }

        // Action functions
        function viewInstructor(instructorId) {
            window.location.href = `view-instructor.php?id=${instructorId}`;
        }

        function editInstructor(instructorId) {
            window.location.href = `edit-instructor.php?id=${instructorId}`;
        }

        function assignCourses(instructorId) {
            window.location.href = `assign-courses.php?instructor_id=${instructorId}`;
        }

        async function deleteInstructor(instructorId) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will permanently delete the instructor record!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('../../api/staff/delete.php', {
                        method: 'DELETE',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: instructorId })
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        showAlert('Instructor deleted successfully!', 'success');
                        await loadInstructors(); // Reload the list
                    } else {
                        showAlert(data.message || 'Failed to delete instructor', 'error');
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
        .avatar-sm {
            width: 35px;
            height: 35px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            font-size: 14px;
        }
        
        .table td {
            vertical-align: middle;
        }
        
        .btn-group .btn {
            border-radius: 4px;
            margin-right: 2px;
        }
    </style>
</body>
</html>