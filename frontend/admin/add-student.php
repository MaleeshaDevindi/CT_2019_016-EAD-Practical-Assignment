
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Student Management System</title>
    
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
                <div class="page-header">
                    <h1><i class="fas fa-user-plus"></i> Add New Student</h1>
                    <p>Register a new student by filling out the form below</p>
                </div>
            </div>
        </div>

        <!-- Add Student Form -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-user-graduate"></i> Student Information</h5>
                    </div>
                    <div class="card-body">
                        <form id="addStudentForm">
                            <div class="row">
                                <!-- Student Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="studentNo" class="form-label">Student Number *</label>
                                    <input type="text" class="form-control" id="studentNo" name="student_no" 
                                           placeholder="e.g., STU2024001" required>
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
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="student@example.com" required>
                                    <div class="form-text">This will be used for student communications</div>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="+1234567890">
                                </div>

                                <!-- Additional Information Section -->
                                <div class="col-md-12 mb-3">
                                    <hr>
                                    <h6><i class="fas fa-info-circle"></i> Additional Information</h6>
                                </div>

                                <!-- Date of Birth -->
                                <div class="col-md-4 mb-3">
                                    <label for="dateOfBirth" class="form-label">Date of Birth</label>
                                    <input type="date" class="form-control" id="dateOfBirth" name="date_of_birth">
                                </div>

                                <!-- Gender -->
                                <div class="col-md-4 mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="" selected>Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <!-- Enrollment Date -->
                                <div class="col-md-4 mb-3">
                                    <label for="enrollmentDate" class="form-label">Enrollment Date</label>
                                    <input type="date" class="form-control" id="enrollmentDate" name="enrollment_date" 
                                           value="<?php echo date('Y-m-d'); ?>">
                                </div>

                                <!-- Address -->
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" 
                                              rows="3" placeholder="Enter student's full address..."></textarea>
                                </div>

                                <!-- Emergency Contact -->
                                <div class="col-md-12 mb-3">
                                    <hr>
                                    <h6><i class="fas fa-phone-alt"></i> Emergency Contact</h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergencyContactName" class="form-label">Emergency Contact Name</label>
                                    <input type="text" class="form-control" id="emergencyContactName" name="emergency_contact_name" 
                                           placeholder="Full name of emergency contact">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="emergencyContactPhone" class="form-label">Emergency Contact Phone</label>
                                    <input type="tel" class="form-control" id="emergencyContactPhone" name="emergency_contact_phone" 
                                           placeholder="+1234567890">
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Add Student
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <div id="messageContainer" style="display: none;">
                    <div id="successMessage" class="alert alert-success" style="display: none;">
                        <i class="fas fa-check-circle"></i> Student has been added successfully!
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
        // Handle form submission
        document.getElementById('addStudentForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                student_no: document.getElementById('studentNo').value,
                nic: document.getElementById('nic').value,
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                date_of_birth: document.getElementById('dateOfBirth').value,
                gender: document.getElementById('gender').value,
                enrollment_date: document.getElementById('enrollmentDate').value,
                address: document.getElementById('address').value,
                emergency_contact_name: document.getElementById('emergencyContactName').value,
                emergency_contact_phone: document.getElementById('emergencyContactPhone').value
            };

            try {
                const response = await fetch('../../api/students/create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showAlert('Student added successfully!', 'success');
                    setTimeout(() => window.location.href = 'all-students.php', 2000);
                } else {
                    showAlert(result.message, 'error');
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            }
        });

        // Reset form function
        function resetForm() {
            if (confirm('Are you sure you want to reset the form? All data will be lost.')) {
                document.getElementById('addStudentForm').reset();
                document.getElementById('enrollmentDate').value = new Date().toISOString().split('T')[0];
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

        // Set default enrollment date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('enrollmentDate').value = today;
        });
    </script>
</body>
</html>