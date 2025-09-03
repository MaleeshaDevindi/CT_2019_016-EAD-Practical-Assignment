
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Instructor - Instructor Management System</title>
    
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
                    <h1 class="h2 mb-1"><i class="fas fa-chalkboard-teacher me-2"></i>Add New Instructor</h1>
                    <p class="text-muted mb-0">Register a new instructor by filling out the form below</p>
                </div>
            </div>
        </div>

        <!-- Add Instructor Form -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0"><i class="fas fa-user-tie me-2"></i>Instructor Information</h5>
                    </div>
                    <div class="card-body">
                        <form id="addInstructorForm">
                            <div class="row">
                                <!-- Instructor Number -->
                                <div class="col-md-6 mb-3">
                                    <label for="instructorNo" class="form-label">Instructor Number *</label>
                                    <input type="text" class="form-control" id="instructorNo" name="instructor_no" 
                                           placeholder="e.g., INS2024001" required>
                                    <div class="form-text">Unique identifier for the instructor</div>
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
                                           placeholder="instructor@example.com" required>
                                </div>

                                <!-- Phone -->
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           placeholder="+1234567890">
                                </div>

                                <!-- Department -->
                                <div class="col-md-6 mb-3">
                                    <label for="department" class="form-label">Department *</label>
                                    <select class="form-select" id="department" name="department" required>
                                        <option value="" selected disabled>Select Department</option>
                                        <option value="IT">Information Technology</option>
                                        <option value="SE">Software Engineering</option>
                                        <option value="CS">Computer Science</option>
                                    </select>
                                </div>

                                <!-- Designation -->
                                <div class="col-md-6 mb-3">
                                    <label for="designation" class="form-label">Designation *</label>
                                    <select class="form-select" id="designation" name="designation" required>
                                        <option value="" selected disabled>Select Designation</option>
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
                                        <option value="" selected disabled>Select Qualification</option>
                                        <option value="PhD">PhD</option>
                                        <option value="Masters">Masters Degree</option>
                                        <option value="Bachelors">Bachelors Degree</option>
                                        <option value="Diploma">Diploma</option>
                                    </select>
                                </div>

                                <!-- Years of Experience -->
                                <div class="col-md-6 mb-3">
                                    <label for="experience" class="form-label">Years of Experience</label>
                                    <input type="number" class="form-control" id="experience" name="experience" 
                                           placeholder="e.g., 5" min="0">
                                </div>

                                <!-- Specialization -->
                                <div class="col-md-12 mb-3">
                                    <label for="specialization" class="form-label">Specialization/Expertise</label>
                                    <textarea class="form-control" id="specialization" name="specialization" 
                                              rows="3" placeholder="List areas of expertise and specialization..."></textarea>
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
                                        <option value="" selected>Select Gender</option>
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
                                    <textarea class="form-control" id="address" name="address" 
                                              rows="3" placeholder="Enter instructor's full address..."></textarea>
                                </div>

                                <!-- Office Information Section -->
                                <div class="col-md-12 mb-3">
                                    <hr>
                                    <h6><i class="fas fa-building me-2"></i>Office Information</h6>
                                </div>

                                
                                <!-- Status -->
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="active" selected>Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="on_leave">On Leave</option>
                                        <option value="retired">Retired</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="salary" class="form-label">Monthly Salary</label>
                                    <input type="number" class="form-control" id="salary" name="salary" 
                                           placeholder="e.g., 50000" step="0.01">
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="d-flex gap-2 justify-content-end">
                                <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Add Instructor
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
        // Handle form submission
        document.getElementById('addInstructorForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
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
                const response = await fetch('../../api/staff/create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.status === 'success') {
                    showAlert('Instructor added successfully!', 'success');
                    setTimeout(() => window.location.href = 'all-instructors.php', 2000);
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
                document.getElementById('addInstructorForm').reset();
                document.getElementById('joinDate').value = new Date().toISOString().split('T')[0];
            }
        }

        // Auto-generate instructor number
        function generateInstructorNumber() {
            const year = new Date().getFullYear();
            const randomNum = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
            return `INS${year}${randomNum}`;
        }

        // Generate instructor number when field is focused
        document.getElementById('instructorNo').addEventListener('focus', function() {
            if (!this.value) {
                this.value = generateInstructorNumber();
            }
        });

        // Set default join date to today
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('joinDate').value = today;
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