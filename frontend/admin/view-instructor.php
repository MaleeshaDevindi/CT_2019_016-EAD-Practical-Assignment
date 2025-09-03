

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Instructor - Instructor Management System</title>
    
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
                            <h1 class="h2 mb-1"><i class="fas fa-chalkboard-teacher me-2"></i>Instructor Profile</h1>
                            <p class="text-muted mb-0">View instructor details and information</p>
                        </div>
                        <div>
                            <a href="all-instructors.php" class="btn btn-secondary me-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to Instructors
                            </a>
                            <button id="editInstructorBtn" class="btn btn-primary">
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
            <p class="mt-2 text-muted">Loading instructor profile...</p>
        </div>

        <!-- Instructor Profile Content -->
        <div id="instructorContent" style="display: none;">
            <!-- Basic Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Profile Avatar Section -->
                        <div class="col-md-3 text-center mb-4">
                            <div class="profile-avatar bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3" id="instructorAvatar">
                                <!-- Avatar will be populated -->
                            </div>
                            <h4 id="instructorName"></h4>
                            <p class="text-muted" id="instructorNumber"></p>
                            <span class="badge fs-6" id="instructorStatusBadge">
                                <!-- Status will be populated -->
                            </span>
                        </div>

                        <!-- Instructor Details -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Instructor Number</label>
                                    <p class="form-control-plaintext" id="instructorNo"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">NIC</label>
                                    <p class="form-control-plaintext" id="instructorNIC"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <p class="form-control-plaintext" id="instructorEmail"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Phone</label>
                                    <p class="form-control-plaintext" id="instructorPhone"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Department</label>
                                    <p class="form-control-plaintext" id="instructorDepartment"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Designation</label>
                                    <p class="form-control-plaintext" id="instructorDesignation"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Qualification</label>
                                    <p class="form-control-plaintext" id="instructorQualification"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Experience</label>
                                    <p class="form-control-plaintext" id="instructorExperience"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Date of Birth</label>
                            <p class="form-control-plaintext" id="instructorDOB"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Gender</label>
                            <p class="form-control-plaintext" id="instructorGender"></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Join Date</label>
                            <p class="form-control-plaintext" id="instructorJoinDate"></p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Address</label>
                            <p class="form-control-plaintext" id="instructorAddress"></p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Specialization</label>
                            <p class="form-control-plaintext" id="instructorSpecialization"></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Office Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-building me-2"></i>Office Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Monthly Salary</label>
                            <p class="form-control-plaintext" id="instructorSalary"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Registration Date</label>
                            <p class="form-control-plaintext" id="instructorCreatedAt"></p>
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
                            <button class="btn btn-outline-info w-100" onclick="assignCourses()">
                                <i class="fas fa-book"></i><br>
                                <small>Assign Courses</small>
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-warning w-100" onclick="generateReport()">
                                <i class="fas fa-file-pdf"></i><br>
                                <small>Generate Report</small>
                            </button>
                        </div>
                        <div class="col-md-3 mb-2">
                            <button class="btn btn-outline-secondary w-100" onclick="changeStatus()">
                                <i class="fas fa-sync-alt"></i><br>
                                <small>Change Status</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="d-flex gap-2 justify-content-end mb-4">
                        <button type="button" class="btn btn-outline-danger" onclick="deleteInstructor()">
                            <i class="fas fa-trash me-2"></i>Delete Instructor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const instructorId = new URLSearchParams(window.location.search).get('id');
        let instructorData = null;

        // Load instructor data on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!instructorId) {
                showAlert('Invalid instructor ID', 'error');
                setTimeout(() => window.location.href = 'all-instructors.php', 2000);
                return;
            }
            loadInstructorData();
        });

        // Load instructor data via API
        async function loadInstructorData() {
            try {
                const response = await fetch(`../../api/staff/read.php?id=${instructorId}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    instructorData = result.data;
                    displayInstructorData();
                } else {
                    showAlert('Instructor not found', 'error');
                    setTimeout(() => window.location.href = 'all-instructors.php', 2000);
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            } finally {
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('instructorContent').style.display = 'block';
            }
        }

        // Display instructor data
        function displayInstructorData() {
            if (!instructorData) return;

            // Set up edit button
            document.getElementById('editInstructorBtn').onclick = () => {
                window.location.href = `edit-instructor.php?id=${instructorId}`;
            };

            // Update basic information
            document.getElementById('instructorAvatar').textContent = 
                (instructorData.first_name?.charAt(0) || '') + (instructorData.last_name?.charAt(0) || '');
            document.getElementById('instructorName').textContent = `${instructorData.first_name} ${instructorData.last_name}`;
            document.getElementById('instructorNumber').textContent = instructorData.instructor_no;
            document.getElementById('instructorNo').textContent = instructorData.instructor_no;
            document.getElementById('instructorNIC').textContent = instructorData.nic || 'Not provided';
            document.getElementById('instructorEmail').innerHTML = `
                <a href="mailto:${instructorData.email}" class="text-decoration-none">
                    <i class="fas fa-envelope me-1"></i>${instructorData.email}
                </a>
            `;
            document.getElementById('instructorPhone').innerHTML = instructorData.phone ? `
                <a href="tel:${instructorData.phone}" class="text-decoration-none">
                    <i class="fas fa-phone me-1"></i>${instructorData.phone}
                </a>
            ` : 'Not provided';
            document.getElementById('instructorDepartment').textContent = instructorData.department || 'Not specified';
            document.getElementById('instructorDesignation').textContent = instructorData.designation || 'Not specified';
            document.getElementById('instructorQualification').textContent = instructorData.qualification || 'Not specified';
            document.getElementById('instructorExperience').textContent = instructorData.experience ? `${instructorData.experience} years` : 'Not specified';

            // Update additional information
            document.getElementById('instructorDOB').textContent = instructorData.date_of_birth ? 
                new Date(instructorData.date_of_birth).toLocaleDateString() : 'Not provided';
            document.getElementById('instructorGender').textContent = instructorData.gender ? 
                instructorData.gender.charAt(0).toUpperCase() + instructorData.gender.slice(1) : 'Not specified';
            document.getElementById('instructorJoinDate').textContent = instructorData.join_date ? 
                new Date(instructorData.join_date).toLocaleDateString() : 'Not specified';
            document.getElementById('instructorAddress').textContent = instructorData.address || 'Not provided';
            document.getElementById('instructorSpecialization').textContent = instructorData.specialization || 'Not specified';

            // Update office information
            
            document.getElementById('instructorSalary').textContent = instructorData.salary ? 
                `LKR ${parseFloat(instructorData.salary).toLocaleString()}` : 'Not specified';
            document.getElementById('instructorCreatedAt').textContent = instructorData.created_at ? 
                new Date(instructorData.created_at).toLocaleDateString() : 'Not specified';

            // Update status badge
            const statusBadge = document.getElementById('instructorStatusBadge');
            statusBadge.className = `badge bg-${instructorData.status === 'active' ? 'success' : 'danger'} fs-6`;
            statusBadge.innerHTML = `
                <i class="fas fa-${instructorData.status === 'active' ? 'check-circle' : 'times-circle'} me-1"></i>
                ${instructorData.status.charAt(0).toUpperCase() + instructorData.status.slice(1).replace('_', ' ')}
            `;
        }

        // Quick action functions
        function sendEmail() {
            if (instructorData) {
                const subject = 'Message from Instructor Management System';
                window.location.href = `mailto:${instructorData.email}?subject=${encodeURIComponent(subject)}`;
            }
        }

        function assignCourses() {
            window.location.href = `assign-courses.php?instructor_id=${instructorId}`;
        }

        function generateReport() {
            window.open(`generate-instructor-report.php?id=${instructorId}`, '_blank');
        }

        async function changeStatus() {
            if (!instructorData) return;

            const newStatus = instructorData.status === 'active' ? 'inactive' : 'active';
            const result = await Swal.fire({
                title: 'Change Status',
                text: `Change instructor status from ${instructorData.status} to ${newStatus}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch('../../api/staff/update-status.php', {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: instructorId, status: newStatus })
                    });

                    const data = await response.json();

                    if (data.status === 'success') {
                        showAlert('Status updated successfully!', 'success');
                        await loadInstructorData(); // Reload instructor data
                    } else {
                        showAlert(data.message || 'Failed to update status', 'error');
                    }
                } catch (error) {
                    showAlert('Unable to connect to server', 'error');
                }
            }
        }

        async function deleteInstructor() {
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
                        setTimeout(() => window.location.href = 'all-instructors.php', 2000);
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
        .profile-avatar {
            width: 120px;
            height: 120px;
            font-size: 48px;
            font-weight: bold;
        }
        
        .btn-outline-primary, .btn-outline-info, .btn-outline-warning, .btn-outline-secondary {
            height: 80px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .btn-outline-primary i, .btn-outline-info i, .btn-outline-warning i, .btn-outline-secondary i {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .form-control-plaintext {
            min-height: 2.5rem;
            padding: 0.375rem 0;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</body>
</html>