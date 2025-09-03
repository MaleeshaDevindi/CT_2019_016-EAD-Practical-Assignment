<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student - Student Management System</title>
    
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
                            <h1 class="h2 mb-1"><i class="fas fa-user-edit me-2"></i>Update Student</h1>
                            <p class="text-muted mb-0">Modify student information using the form below</p>
                        </div>
                        <div>
                            <a href="all-students.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Students
                            </a>
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
            <p class="mt-2 text-muted">Loading student data...</p>
        </div>

        <!-- Update Student Form -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card" id="studentFormCard" style="display: none;">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-user-graduate me-2"></i>Student Information</h5>
                    </div>
                    <div class="card-body">
                        <form id="updateStudentForm">
                            <input type="hidden" id="studentId">
                            
                            <div class="row">
                                <!-- Student Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="studentNo" class="form-label">Student Number *</label>
                                    <input type="text" class="form-control" id="studentNo" name="student_no" required>
                                    <div class="form-text">Unique identifier for the student</div>
                                </div>

                                <!-- NIC -->
                                <div class="col-md-6 mb-3">
                                    <label for="nic" class="form-label">NIC *</label>
                                    <input type="text" class="form-control" id="nic" name="nic" required>
                                </div>

                                <!-- First Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name *</label>
                                    <input type="text" class="form-control" id="firstName" name="first_name" required>
                                </div>

                                <!-- Last Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control" id="lastName" name="last_name" required>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone">
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active">Active</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-6 mb-3">
                                    <label for="dateOfBirth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dateOfBirth" name="date_of_birth">
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <!-- Address -->
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                                </div>

                                <!-- Emergency Contact -->
                                <div class="col-md-12 mb-3">
                                    <hr>
                                    <h6><i class="fas fa-phone-alt me-2"></i>Emergency Contact</h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergencyContactName" class="form-label">Emergency Contact Name</label>
                                    <input type="text" class="form-control" id="emergencyContactName" name="emergency_contact_name">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergencyContactPhone" class="form-label">Emergency Contact Phone</label>
                                    <input type="tel" class="form-control" id="emergencyContactPhone" name="emergency_contact_phone">
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Student
                                </button>
                            </div>
                        </form>
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

        // Load student data on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!studentId) {
                showAlert('Invalid student ID', 'error');
                setTimeout(() => window.location.href = 'all-students.php', 2000);
                return;
            }
            loadStudentData();
        });

        // Load student data via API
        async function loadStudentData() {
            try {
                const response = await fetch(`../../api/students/read.php?id=${studentId}`);
                const result = await response.json();
                
                if (result.status === 'success') {
                    studentData = result.data;
                    populateForm();
                } else {
                    showAlert('Student not found', 'error');
                    setTimeout(() => window.location.href = 'all-students.php', 2000);
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            } finally {
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('studentFormCard').style.display = 'block';
            }
        }

        // Populate form with student data
        function populateForm() {
            if (!studentData) return;

            document.getElementById('studentId').value = studentData.id;
            document.getElementById('studentNo').value = studentData.student_no || '';
            document.getElementById('nic').value = studentData.nic || '';
            document.getElementById('firstName').value = studentData.first_name || '';
            document.getElementById('lastName').value = studentData.last_name || '';
            document.getElementById('email').value = studentData.email || '';
            document.getElementById('phone').value = studentData.phone || '';
            document.getElementById('status').value = studentData.status || 'active';
            document.getElementById('dateOfBirth').value = studentData.date_of_birth || '';
            document.getElementById('gender').value = studentData.gender || '';
            document.getElementById('address').value = studentData.address || '';
            document.getElementById('emergencyContactName').value = studentData.emergency_contact_name || '';
            document.getElementById('emergencyContactPhone').value = studentData.emergency_contact_phone || '';
        }

        // Handle form submission
        document.getElementById('updateStudentForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                id: document.getElementById('studentId').value,
                student_no: document.getElementById('studentNo').value,
                nic: document.getElementById('nic').value,
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                status: document.getElementById('status').value,
                date_of_birth: document.getElementById('dateOfBirth').value,
                gender: document.getElementById('gender').value,
                address: document.getElementById('address').value,
                emergency_contact_name: document.getElementById('emergencyContactName').value,
                emergency_contact_phone: document.getElementById('emergencyContactPhone').value
            };

            try {
                const response = await fetch('../../api/students/update.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showAlert('Student updated successfully!', 'success');
                    setTimeout(() => window.location.href = 'view-student.php?id=' + studentId, 2000);
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            }
        });

        // Reset form function
        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All changes will be lost.')) {
                populateForm(); // Reset to original values
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