
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Instructor - Instructor Management System</title>
    
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
                            <h1 class="h2 mb-1"><i class="fas fa-user-edit me-2"></i>Edit Instructor</h1>
                            <p class="text-muted mb-0">Update instructor information</p>
                        </div>
                        <div>
                            <a href="all-instructors.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Instructors
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
            <p class="mt-2 text-muted">Loading instructor data...</p>
        </div>

        <!-- Edit Instructor Form -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card" id="instructorFormCard" style="display: none;">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-user-tie me-2"></i>Instructor Information</h5>
                    </div>
                    <div class="card-body">
                        <form id="editInstructorForm">
                            <input type="hidden" id="instructorId">
                            
                            <div class="row">
                                <!-- Instructor Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="instructorNo" class="form-label">Instructor Number *</label>
                                    <input type="text" class="form-control" id="instructorNo" name="instructor_no" required readonly>
                                    <div class="form-text">Unique identifier (cannot be changed)</div>
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

                                <!-- Department -->
                                <div class="col-md-6 mb-3">
                                    <label for="department" class="form-label">Department *</label>
                                    <select class="form-select" id="department" name="department" required>
                                        <option value="">Select Department</option>
                                        <option value="IT">Information Technology</option>
                                        <option value="SE">Software Engineering</option>
                                        <option value="CS">Computer Science</option>
                                    </select>
                                </div>

                                <!-- Designation -->
                                <div class="col-md-6 mb-3">
                                    <label for="designation" class="form-label">Designation *</label>
                                    <select class="form-select" id="designation" name="designation" required>
                                        <option value="">Select Designation</option>
                                        <option value="Professor">Professor</option>
                                        <option value="Associate Professor">Associate Professor</option>
                                        <option value="Assistant Professor">Assistant Professor</option>
                                        <option value="Senior Lecturer">Senior Lecturer</option>
                                        <option value="Lecturer">Lecturer</option>
                                        <option value="Assistant Lecturer">Assistant Lecturer</option>
                                    </select>
                                </div>

                                <!-- Qualification -->
                                <div class="col-md-6 mb-3">
                                    <label for="qualification" class="form-label">Highest Qualification *</label>
                                    <select class="form-select" id="qualification" name="qualification" required>
                                        <option value="">Select Qualification</option>
                                        <option value="PhD">PhD</option>
                                        <option value="Masters">Masters Degree</option>
                                        <option value="Bachelors">Bachelors Degree</option>
                                        <option value="Diploma">Diploma</option>
                                    </select>
                                </div>

                                <!-- Years of Experience -->
                                <div class="col-md-6 mb-3">
                                    <label for="experience" class="form-label">Years of Experience</label>
                                    <input type="number" class="form-control" id="experience" name="experience" min="0">
                                </div>

                                <!-- Specialization -->
                                <div class="col-md-12 mb-3">
                                    <label for="specialization" class="form-label">Specialization/Expertise</label>
                                    <textarea class="form-control" id="specialization" name="specialization" rows="3"></textarea>
                                </div>

                                <!-- Additional Information Section -->
                                <div class="col-md-12 mb-3">
                                    <hr>
                                    <h6><i class="fas fa-info-circle me-2"></i>Additional Information</h6>
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
                                        <option value="">Select Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <!-- Join Date -->
                                <div class="col-md-4 mb-3">
                                    <label for="joinDate" class="form-label">Join Date</label>
                                    <input type="date" class="form-control" id="joinDate" name="join_date">
                                </div>

                                <!-- Address -->
                                <div class="col-md-12 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                                </div>

                                <!-- Office Information Section -->
                                <div class="col-md-12 mb-3">
                                    <hr>
                                    <h6><i class="fas fa-building me-2"></i>Office Information</h6>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="officeLocation" class="form-label">Office Location</label>
                                    <input type="text" class="form-control" id="officeLocation" name="office_location">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="officeHours" class="form-label">Office Hours</label>
                                    <input type="text" class="form-control" id="officeHours" name="office_hours">
                                </div>

                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="on_leave">On Leave</option>
                                        <option value="retired">Retired</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="salary" class="form-label">Monthly Salary</label>
                                    <input type="number" class="form-control" id="salary" name="salary" step="0.01">
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Instructor
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
                    populateForm();
                } else {
                    showAlert('Instructor not found', 'error');
                    setTimeout(() => window.location.href = 'all-instructors.php', 2000);
                }
            } catch (error) {
                showAlert('Unable to connect to server', 'error');
            } finally {
                document.getElementById('loadingSpinner').style.display = 'none';
                document.getElementById('instructorFormCard').style.display = 'block';
            }
        }

        // Populate form with instructor data
        function populateForm() {
            if (!instructorData) return;

            document.getElementById('instructorId').value = instructorData.id;
            document.getElementById('instructorNo').value = instructorData.instructor_no || '';
            document.getElementById('nic').value = instructorData.nic || '';
            document.getElementById('firstName').value = instructorData.first_name || '';
            document.getElementById('lastName').value = instructorData.last_name || '';
            document.getElementById('email').value = instructorData.email || '';
            document.getElementById('phone').value = instructorData.phone || '';
            document.getElementById('department').value = instructorData.department || '';
            document.getElementById('designation').value = instructorData.designation || '';
            document.getElementById('qualification').value = instructorData.qualification || '';
            document.getElementById('experience').value = instructorData.experience || '';
            document.getElementById('specialization').value = instructorData.specialization || '';
            document.getElementById('dateOfBirth').value = instructorData.date_of_birth || '';
            document.getElementById('gender').value = instructorData.gender || '';
            document.getElementById('joinDate').value = instructorData.join_date || '';
            document.getElementById('address').value = instructorData.address || '';
            document.getElementById('officeLocation').value = instructorData.office_location || '';
            document.getElementById('officeHours').value = instructorData.office_hours || '';
            document.getElementById('status').value = instructorData.status || 'active';
            document.getElementById('salary').value = instructorData.salary || '';
        }

        // Handle form submission
        document.getElementById('editInstructorForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                id: document.getElementById('instructorId').value,
                instructor_no: document.getElementById('instructorNo').value,
                nic: document.getElementById('nic').value,
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value,
                department: document.getElementById('department').value,
                designation: document.getElementById('designation').value,
                qualification: document.getElementById('qualification').value,
                experience: document.getElementById('experience').value,
                specialization: document.getElementById('specialization').value,
                date_of_birth: document.getElementById('dateOfBirth').value,
                gender: document.getElementById('gender').value,
                join_date: document.getElementById('joinDate').value,
                address: document.getElementById('address').value,
                office_location: document.getElementById('officeLocation').value,
                office_hours: document.getElementById('officeHours').value,
                status: document.getElementById('status').value,
                salary: document.getElementById('salary').value
            };

            try {
                const response = await fetch('../../api/staff/update.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showAlert('Instructor updated successfully!', 'success');
                    setTimeout(() => window.location.href = 'view-instructor.php?id=' + instructorId, 2000);
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