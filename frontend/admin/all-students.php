
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Students - Student Management System</title>
    
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
                            <h1 class="h2 mb-1"><i class="fas fa-users me-2"></i>All Students</h1>
                            <p class="text-muted mb-0">Manage and view all registered students</p>
                        </div>
                        <div>
                            <a href="add-student.php" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Add New Student
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Students List -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><i class="fas fa-user-graduate me-2"></i>Students List</h5>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search students..." style="width: 250px;">
                                <select class="form-select form-select-sm" id="statusFilter" style="width: 150px;">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="loadingSpinner" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-muted">Loading students...</p>
                        </div>

                        <div id="noStudentsMessage" class="text-center py-5" style="display: none;">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Students Found</h5>
                            <p class="text-muted">No students have been registered yet.</p>
                            <a href="add-student.php" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Add First Student
                            </a>
                        </div>

                        <div id="studentsTableContainer" class="table-responsive" style="display: none;">
                            <table class="table table-striped table-hover" id="studentsTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Student No</th>
                                        <th>Name</th>
                                        <th>Contact Details</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="studentsTableBody">
                                    <!-- Students will be loaded via JavaScript -->
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
        let students = [];

        // Load students on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadStudents();
        });

        // Load students via API
        async function loadStudents() {
            try {
                const response = await fetch('../../api/students/');
                const result = await response.json();
                
                if (result.status === 'success') {
                    students = result.data;
                    displayStudents();
                } else {
                    showAlert('Failed to load students', 'error');
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            } finally {
                document.getElementById('loadingSpinner').style.display = 'none';
            }
        }

        // Display students in table
        function displayStudents() {
            const container = document.getElementById('studentsTableContainer');
            const noStudents = document.getElementById('noStudentsMessage');
            const tableBody = document.getElementById('studentsTableBody');

            if (students.length === 0) {
                noStudents.style.display = 'block';
                container.style.display = 'none';
                return;
            }

            noStudents.style.display = 'none';
            container.style.display = 'block';

            tableBody.innerHTML = students.map((student, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td><strong>${student.student_no}</strong></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                ${(student.first_name?.charAt(0) || '') + (student.last_name?.charAt(0) || '')}
                            </div>
                            <div>
                                <div class="fw-bold">${student.first_name} ${student.last_name}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <a href="mailto:${student.email}" class="text-decoration-none">
                            ${student.email}
                        </a><br>
                        ${student.phone ? `
                            <a href="tel:${student.phone}" class="text-decoration-none">
                                ${student.phone}
                            </a>
                        ` : '<span class="text-muted">Not provided</span>'}
                    </td>
                   <td>
                        ${student.gender.charAt(0).toUpperCase() + student.gender.slice(1)}
                   </td>
                    <td>
                        <span class="badge bg-${student.status === 'active' ? 'success' : 'danger'}">
                            <i class="fas fa-${student.status === 'active' ? 'check-circle' : 'times-circle'} me-1"></i>
                            ${student.status.charAt(0).toUpperCase() + student.status.slice(1)}
                        </span>
                    </td>
                    <td>
                        <div class="small">
                            ${new Date(student.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                            <br>
                            <span class="text-muted">${new Date(student.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</span>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewStudent(${student.id})" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editStudent(${student.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteStudent(${student.id})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');

            // Initialize search and filter functionality
            initSearchAndFilter();
        }

        // Initialize search and filter functionality
        function initSearchAndFilter() {
            document.getElementById('searchInput').addEventListener('keyup', filterTable);
            document.getElementById('statusFilter').addEventListener('change', filterTable);
        }

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            const rows = document.getElementById('studentsTableBody').getElementsByTagName('tr');

            for (let row of rows) {
                const studentNo = row.cells[1].textContent.toLowerCase();
                const name = row.cells[2].textContent.toLowerCase();
                const email = row.cells[3].textContent.toLowerCase();
                const phone = row.cells[4].textContent.toLowerCase();
                const status = row.cells[5].textContent.toLowerCase();
                

                const matchesSearch = studentNo.includes(searchTerm) || 
                                    name.includes(searchTerm) || 
                                    email.includes(searchTerm) || 
                                    phone.includes(searchTerm);
                
                const matchesStatus = statusFilter === '' || status.includes(statusFilter);

                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            }
        }

        // View student details
        function viewStudent(studentId) {
            window.location.href = `view-student.php?id=${studentId}`;
        }

        // Edit student
        function editStudent(studentId) {
            window.location.href = `edit-student.php?id=${studentId}`;
        }

        // Delete student
        async function deleteStudent(studentId) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
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
                        await loadStudents(); // Reload the students list
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
</body>
</html>