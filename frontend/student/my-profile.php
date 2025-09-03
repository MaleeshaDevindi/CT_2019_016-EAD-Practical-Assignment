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

// Include database connection
include '../../api/config.php';

// Get current student ID from session
$studentNIC = $_SESSION['nic'] ?? 1; // In real app, get from session

// Fetch student details
try {
    $sql = "SELECT * FROM students WHERE nic = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $studentNIC);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    if (!$student) {
        $_SESSION['message'] = "Student profile not found.";
        $_SESSION['message-type'] = "error";
        header("Location: ../../index.php");
        exit();
    }
} 
catch (PDOException $e) {
    $_SESSION['message'] = "Error fetching profile: " . $e->getMessage();
    $_SESSION['message-type'] = "error";
}

// Sample enrolled courses data
$enrolledCourses = [
    ['code' => 'CS101', 'title' => 'Introduction to Programming', 'credits' => 3, 'grade' => 'A', 'instructor' => 'Dr. Smith', 'semester' => '1st Semester'],
    ['code' => 'IT201', 'title' => 'Database Systems', 'credits' => 4, 'grade' => 'B+', 'instructor' => 'Prof. Johnson', 'semester' => '1st Semester'],
    ['code' => 'SE301', 'title' => 'Software Engineering', 'credits' => 3, 'grade' => 'A-', 'instructor' => 'Dr. Williams', 'semester' => '2nd Semester'],
    ['code' => 'CS201', 'title' => 'Data Structures', 'credits' => 4, 'grade' => 'B', 'instructor' => 'Prof. Brown', 'semester' => '2nd Semester']
];

// Calculate GPA and academic stats
$totalCredits = array_sum(array_column($enrolledCourses, 'credits'));
$gradePoints = ['A' => 4.0, 'A-' => 3.7, 'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7, 'C+' => 2.3, 'C' => 2.0];
$totalGradePoints = 0;
foreach ($enrolledCourses as $course) {
    $totalGradePoints += ($gradePoints[$course['grade']] ?? 0) * $course['credits'];
}
$gpa = $totalCredits > 0 ? $totalGradePoints / $totalCredits : 0;

// Recent activities
$recentActivities = [
    ['date' => '2024-08-30', 'activity' => 'Submitted assignment for CS101', 'type' => 'assignment'],
    ['date' => '2024-08-29', 'activity' => 'Enrolled in Software Engineering course', 'type' => 'enrollment'],
    ['date' => '2024-08-28', 'activity' => 'Updated profile information', 'type' => 'profile'],
    ['date' => '2024-08-27', 'activity' => 'Received grade for Database Systems', 'type' => 'grade']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Student Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/student-style.css">
</head>
<body>
    <!-- Include Student Sidebar -->
    <?php include 'student-sidemenu.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
         <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="page-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1><i class="fas fa-user-circle"></i> My Profile</h1>
                            <p>View and manage your personal information</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" onclick="editProfile()">
                                <i class="fas fa-edit"></i> Edit Profile
                            </button>
                            <button class="btn btn-outline-success" onclick="downloadTranscript()">
                                <i class="fas fa-download"></i> Download Transcript
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Profile Content -->
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <!-- Profile Header Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="profile-avatar bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-3">
                                    <?php echo strtoupper(substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1)); ?>
                                </div>
                                <h4><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></h4>
                                <p class="text-muted mb-2"><?php echo htmlspecialchars($student['student_no']); ?></p>
                                <?php
                                $statusClass = $student['status'] === 'active' ? 'success' : 'danger';
                                $statusIcon = $student['status'] === 'active' ? 'check-circle' : 'times-circle';
                                ?>
                                <span class="badge bg-<?php echo $statusClass; ?> fs-6 mb-3">
                                    <i class="fas fa-<?php echo $statusIcon; ?>"></i>
                                    <?php echo ucfirst(htmlspecialchars($student['status'])); ?> Student
                                </span>
                                <div class="text-center">
                                    <button class="btn btn-outline-primary btn-sm me-2" onclick="changePassword()">
                                        <i class="fas fa-key"></i> Change Password
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="editContactInfo()">
                                        <i class="fas fa-phone"></i> Update Contact
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Email Address</label>
                                        <p class="form-control-plaintext">
                                            <i class="fas fa-envelope me-2"></i>
                                            <?php echo htmlspecialchars($student['email']); ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Phone Number</label>
                                        <p class="form-control-plaintext">
                                            <?php if ($student['phone']): ?>
                                                <i class="fas fa-phone me-2"></i>
                                                <?php echo htmlspecialchars($student['phone']); ?>
                                            <?php else: ?>
                                                <span class="text-muted">
                                                    <i class="fas fa-phone me-2"></i>
                                                    Not provided
                                                </span>
                                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="addPhone()">Add Phone</button>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Registration Date</label>
                                        <p class="form-control-plaintext">
                                            <i class="fas fa-calendar me-2"></i>
                                            <?php echo date('F d, Y', strtotime($student['created_at'])); ?>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">Student ID</label>
                                        <p class="form-control-plaintext">
                                            <i class="fas fa-id-badge me-2"></i>
                                            <?php echo $student['id']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Summary Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-graduation-cap"></i> Academic Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="summary-item p-3 border rounded bg-light">
                                    <h3 class="text-primary mb-1"><?php echo count($enrolledCourses); ?></h3>
                                    <small class="text-muted">Enrolled Courses</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="summary-item p-3 border rounded bg-light">
                                    <h3 class="text-success mb-1"><?php echo $totalCredits; ?></h3>
                                    <small class="text-muted">Total Credits</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="summary-item p-3 border rounded bg-light">
                                    <?php 
                                    $gpaClass = $gpa >= 3.5 ? 'success' : ($gpa >= 3.0 ? 'info' : ($gpa >= 2.0 ? 'warning' : 'danger'));
                                    ?>
                                    <h3 class="text-<?php echo $gpaClass; ?> mb-1"><?php echo number_format($gpa, 2); ?></h3>
                                    <small class="text-muted">Current GPA</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="summary-item p-3 border rounded bg-light">
                                    <h3 class="text-warning mb-1"><?php echo date('Y') - date('Y', strtotime($student['created_at'])) + 1; ?></h3>
                                    <small class="text-muted">Academic Year</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Courses -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-book-open"></i> My Courses</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-primary" onclick="enrollNewCourse()">
                                    <i class="fas fa-plus"></i> Enroll New Course
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="viewFullSchedule()">
                                    <i class="fas fa-calendar-alt"></i> View Schedule
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($enrolledCourses)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Courses Enrolled</h5>
                                <p class="text-muted">You haven't enrolled in any courses yet.</p>
                                <button class="btn btn-primary" onclick="enrollNewCourse()">
                                    <i class="fas fa-plus"></i> Enroll in Your First Course
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($enrolledCourses as $course): ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card border-start border-4 border-primary">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div>
                                                    <h6 class="card-title mb-1"><?php echo htmlspecialchars($course['code']); ?></h6>
                                                    <p class="card-text mb-1"><?php echo htmlspecialchars($course['title']); ?></p>
                                                    <small class="text-muted">Manage active sessions</small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-warning" onclick="manageSessions()">
                                            <i class="fas fa-list"></i> Manage
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6>Privacy Settings</h6>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="profileVisibility" checked>
                                        <label class="form-check-label" for="profileVisibility">
                                            Profile visible to instructors
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                        <label class="form-check-label" for="emailNotifications">
                                            Email notifications
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="smsNotifications">
                                        <label class="form-check-label" for="smsNotifications">
                                            SMS notifications
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="shareAcademicInfo">
                                        <label class="form-check-label" for="shareAcademicInfo">
                                            Share academic progress with parents
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-phone-alt"></i> Emergency Contact</h5>
                            <button class="btn btn-sm btn-outline-primary" onclick="editEmergencyContact()">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Contact Name</label>
                                <p class="form-control-plaintext">
                                    <?php echo isset($student['emergency_contact_name']) && $student['emergency_contact_name'] ? 
                                        htmlspecialchars($student['emergency_contact_name']) : 
                                        '<span class="text-muted">Not provided</span>'; ?>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Contact Phone</label>
                                <p class="form-control-plaintext">
                                    <?php echo isset($student['emergency_contact_phone']) && $student['emergency_contact_phone'] ? 
                                        htmlspecialchars($student['emergency_contact_phone']) : 
                                        '<span class="text-muted">Not provided</span>'; ?>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Relationship</label>
                                <p class="form-control-plaintext">
                                    <?php echo isset($student['emergency_contact_relationship']) && $student['emergency_contact_relationship'] ? 
                                        htmlspecialchars($student['emergency_contact_relationship']) : 
                                        '<span class="text-muted">Not specified</span>'; ?>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Alternative Contact</label>
                                <p class="form-control-plaintext">
                                    <?php echo isset($student['emergency_contact_email']) && $student['emergency_contact_email'] ? 
                                        htmlspecialchars($student['emergency_contact_email']) : 
                                        '<span class="text-muted">Not provided</span>'; ?>
                                </p>
                            </div>
                        </div>
                        <?php if (empty($student['emergency_contact_name'])): ?>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Important:</strong> Please add emergency contact information for safety purposes.
                            <button class="btn btn-sm btn-warning ms-2" onclick="addEmergencyContact()">
                                <i class="fas fa-plus"></i> Add Now
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="row justify-content-center">
                    <div class="col-lg-11">
                        <div class="d-flex gap-2 justify-content-end mb-4">
                            <button type="button" class="btn btn-outline-info" onclick="downloadData()">
                                <i class="fas fa-download"></i> Download My Data
                            </button>
                            <button type="button" class="btn btn-outline-warning" onclick="deactivateAccount()">
                                <i class="fas fa-user-slash"></i> Deactivate Account
                            </button>
                            <button type="button" class="btn btn-primary" onclick="saveAllChanges()">
                                <i class="fas fa-save"></i> Save All Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        // Edit profile function
        function editProfile() {
            window.location.href = 'edit-my-profile.php';
        }

        // Download transcript
        function downloadTranscript() {
            Swal.fire({
                title: 'Download Transcript',
                text: 'Choose transcript format:',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-file-pdf"></i> PDF',
                cancelButtonText: '<i class="fas fa-file-word"></i> Word',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('php/generate-transcript.php?format=pdf&student_id=<?php echo $student['id']; ?>', '_blank');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.open('php/generate-transcript.php?format=word&student_id=<?php echo $student['id']; ?>', '_blank');
                }
            });
        }

        // Change password
        function changePassword() {
            Swal.fire({
                title: 'Change Password',
                html: `
                    <div class="mb-3">
                        <label for="swal-input1" class="form-label">Current Password</label>
                        <input id="swal-input1" class="swal2-input" placeholder="Enter current password" type="password">
                    </div>
                    <div class="mb-3">
                        <label for="swal-input2" class="form-label">New Password</label>
                        <input id="swal-input2" class="swal2-input" placeholder="Enter new password" type="password">
                    </div>
                    <div class="mb-3">
                        <label for="swal-input3" class="form-label">Confirm New Password</label>
                        <input id="swal-input3" class="swal2-input" placeholder="Confirm new password" type="password">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Change Password',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const currentPassword = document.getElementById('swal-input1').value;
                    const newPassword = document.getElementById('swal-input2').value;
                    const confirmPassword = document.getElementById('swal-input3').value;
                    
                    if (!currentPassword || !newPassword || !confirmPassword) {
                        Swal.showValidationMessage('Please fill in all fields');
                        return false;
                    }
                    
                    if (newPassword !== confirmPassword) {
                        Swal.showValidationMessage('New passwords do not match');
                        return false;
                    }
                    
                    if (newPassword.length < 8) {
                        Swal.showValidationMessage('Password must be at least 8 characters long');
                        return false;
                    }
                    
                    return { currentPassword, newPassword };
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send password change request
                    fetch('php/change-student-password.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Success!', 'Your password has been changed successfully.', 'success');
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to change password.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error!', 'An error occurred while changing password.', 'error');
                    });
                }
            });
        }

        // Edit contact info
        function editContactInfo() {
            Swal.fire({
                title: 'Update Contact Information',
                html: `
                    <div class="mb-3">
                        <label for="swal-input1" class="form-label">Email Address</label>
                        <input id="swal-input1" class="swal2-input" value="<?php echo htmlspecialchars($student['email']); ?>" type="email">
                    </div>
                    <div class="mb-3">
                        <label for="swal-input2" class="form-label">Phone Number</label>
                        <input id="swal-input2" class="swal2-input" value="<?php echo htmlspecialchars($student['phone'] ?? ''); ?>" type="tel" placeholder="+1234567890">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Update Contact Info',
                preConfirm: () => {
                    const email = document.getElementById('swal-input1').value;
                    const phone = document.getElementById('swal-input2').value;
                    
                    if (!email) {
                        Swal.showValidationMessage('Email address is required');
                        return false;
                    }
                    
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        Swal.showValidationMessage('Please enter a valid email address');
                        return false;
                    }
                    
                    return { email, phone };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('php/update-student-contact.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Updated!', 'Your contact information has been updated.', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to update contact info.', 'error');
                        }
                    });
                }
            });
        }

        // Add phone number
        function addPhone() {
            Swal.fire({
                title: 'Add Phone Number',
                input: 'tel',
                inputPlaceholder: '+1234567890',
                showCancelButton: true,
                confirmButtonText: 'Add Phone',
                inputValidator: (value) => {
                    if (!value) {
                        return 'Please enter a phone number';
                    }
                    if (value.length < 10) {
                        return 'Please enter a valid phone number';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('php/add-student-phone.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({phone: result.value})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Added!', 'Phone number has been added to your profile.', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to add phone number.', 'error');
                        }
                    });
                }
            });
        }

        // View course details
        function viewCourseDetails(courseCode) {
            window.location.href = `student-course-details.php?code=${courseCode}`;
        }

        // View grades for specific course
        function viewGrades(courseCode) {
            window.location.href = `student-course-grades.php?code=${courseCode}`;
        }

        // View all grades
        function viewGrades() {
            window.location.href = 'student-grades.php';
        }

        // View schedule
        function viewSchedule() {
            window.location.href = 'student-schedule.php';
        }

        // View full schedule
        function viewFullSchedule() {
            window.location.href = 'student-schedule.php';
        }

        // Browse courses
        function browseCourses() {
            window.location.href = 'student-browse-courses.php';
        }

        // Enroll in new course
        function enrollNewCourse() {
            window.location.href = 'student-course-enrollment.php';
        }

        // Contact support
        function contactSupport() {
            Swal.fire({
                title: 'Contact Support',
                html: `
                    <div class="mb-3">
                        <label for="swal-input1" class="form-label">Subject</label>
                        <input id="swal-input1" class="swal2-input" placeholder="Enter subject" type="text">
                    </div>
                    <div class="mb-3">
                        <label for="swal-input2" class="form-label">Message</label>
                        <textarea id="swal-input2" class="swal2-textarea" placeholder="Describe your issue or question..."></textarea>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send Message',
                preConfirm: () => {
                    const subject = document.getElementById('swal-input1').value;
                    const message = document.getElementById('swal-input2').value;
                    
                    if (!subject || !message) {
                        Swal.showValidationMessage('Please fill in all fields');
                        return false;
                    }
                    
                    return { subject, message };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('php/send-support-message.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Message Sent!', 'Support team will respond within 24 hours.', 'success');
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to send message.', 'error');
                        }
                    });
                }
            });
        }

        // Edit personal info
        function editPersonalInfo() {
            window.location.href = 'edit-personal-info.php';
        }

        // Edit emergency contact
        function editEmergencyContact() {
            Swal.fire({
                title: 'Emergency Contact Information',
                html: `
                    <div class="mb-3">
                        <label for="swal-input1" class="form-label">Contact Name</label>
                        <input id="swal-input1" class="swal2-input" value="<?php echo htmlspecialchars($student['emergency_contact_name'] ?? ''); ?>" placeholder="Full name">
                    </div>
                    <div class="mb-3">
                        <label for="swal-input2" class="form-label">Phone Number</label>
                        <input id="swal-input2" class="swal2-input" value="<?php echo htmlspecialchars($student['emergency_contact_phone'] ?? ''); ?>" type="tel" placeholder="+1234567890">
                    </div>
                    <div class="mb-3">
                        <label for="swal-input3" class="form-label">Relationship</label>
                        <select id="swal-input3" class="swal2-input">
                            <option value="">Select Relationship</option>
                            <option value="parent">Parent</option>
                            <option value="guardian">Guardian</option>
                            <option value="sibling">Sibling</option>
                            <option value="spouse">Spouse</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="swal-input4" class="form-label">Email (Optional)</label>
                        <input id="swal-input4" class="swal2-input" value="<?php echo htmlspecialchars($student['emergency_contact_email'] ?? ''); ?>" type="email" placeholder="email@example.com">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Update Emergency Contact',
                preConfirm: () => {
                    const name = document.getElementById('swal-input1').value;
                    const phone = document.getElementById('swal-input2').value;
                    const relationship = document.getElementById('swal-input3').value;
                    const email = document.getElementById('swal-input4').value;
                    
                    if (!name || !phone) {
                        Swal.showValidationMessage('Name and phone number are required');
                        return false;
                    }
                    
                    return { name, phone, relationship, email };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('php/update-emergency-contact.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Updated!', 'Emergency contact information has been updated.', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to update emergency contact.', 'error');
                        }
                    });
                }
            });
        }

        // Add emergency contact
        function addEmergencyContact() {
            editEmergencyContact();
        }

        // Setup two-factor authentication
        function setupTwoFactor() {
            Swal.fire({
                title: 'Setup Two-Factor Authentication',
                text: 'Two-factor authentication adds an extra layer of security to your account.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Setup 2FA',
                cancelButtonText: 'Maybe Later'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'setup-2fa.php';
                }
            });
        }

        // Manage login sessions
        function manageSessions() {
            window.location.href = 'manage-sessions.php';
        }

        // Download personal data
        function downloadData() {
            Swal.fire({
                title: 'Download Personal Data',
                text: 'This will include all your profile information, courses, and grades.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Download Data'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open('php/download-student-data.php', '_blank');
                }
            });
        }

        // Deactivate account
        function deactivateAccount() {
            Swal.fire({
                title: 'Deactivate Account?',
                text: 'Your account will be temporarily deactivated. You can reactivate it by contacting support.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, deactivate'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('php/deactivate-student-account.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Deactivated!', 'Your account has been deactivated.', 'success')
                            .then(() => {
                                window.location.href = 'student-login.php';
                            });
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to deactivate account.', 'error');
                        }
                    });
                }
            });
        }

        // Save all changes
        function saveAllChanges() {
            const privacySettings = {
                profileVisibility: document.getElementById('profileVisibility').checked,
                emailNotifications: document.getElementById('emailNotifications').checked,
                smsNotifications: document.getElementById('smsNotifications').checked,
                shareAcademicInfo: document.getElementById('shareAcademicInfo').checked
            };

            fetch('php/update-privacy-settings.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(privacySettings)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Saved!', 'Your privacy settings have been updated.', 'success');
                } else {
                    Swal.fire('Error!', data.message || 'Failed to save settings.', 'error');
                }
            });
        }

        // View all activities
        function viewAllActivities() {
            window.location.href = 'student-activity-log.php';
        }

        // Profile completion functions
        function completeEmail() {
            editContactInfo();
        }

        function completePhone() {
            addPhone();
        }

        function completeProfilePhoto() {
            Swal.fire({
                title: 'Upload Profile Photo',
                html: `
                    <div class="mb-3">
                        <input type="file" class="form-control" id="profilePhoto" accept="image/*">
                        <div class="form-text">Supported formats: JPG, PNG, GIF (Max 2MB)</div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Upload Photo',
                preConfirm: () => {
                    const fileInput = document.getElementById('profilePhoto');
                    const file = fileInput.files[0];
                    
                    if (!file) {
                        Swal.showValidationMessage('Please select a photo');
                        return false;
                    }
                    
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.showValidationMessage('File size must be less than 2MB');
                        return false;
                    }
                    
                    return file;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('profile_photo', result.value);
                    
                    fetch('php/upload-profile-photo.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Uploaded!', 'Profile photo has been updated.', 'success')
                            .then(() => location.reload());
                        } else {
                            Swal.fire('Error!', data.message || 'Failed to upload photo.', 'error');
                        }
                    });
                }
            });
        }

        function completeEmergencyContact() {
            editEmergencyContact();
        }

        // Add animations on page load
        document.addEventListener('DOMContentLoaded', function() {
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
    </script>

    <style>
        .profile-avatar {
            width: 120px;
            height: 120px;
            font-size: 48px;
            font-weight: bold;
        }
        
        .gpa-circle {
            width: 80px;
            height: 80px;
        }
        
        .timeline-marker {
            width: 35px;
            height: 35px;
            font-size: 14px;
            flex-shrink: 0;
        }
        
        .timeline-content {
            flex: 1;
        }
        
        .summary-item {
            transition: transform 0.2s;
        }
        
        .summary-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #dee2e6;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
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

        .completion-checklist .fas.fa-check-circle {
            color: #198754;
        }

        .completion-checklist .fas.fa-circle {
            color: #6c757d;
        }

        .border-start {
            border-left-width: 4px !important;
        }

        .alert {
            border-left: 4px solid;
        }

        .alert-warning {
            border-left-color: #ffc107;
        }

        .btn-group-sm .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</body>
</html>
                                                        <i class="fas fa-user-tie me-1"></i><?php echo htmlspecialchars($course['instructor']); ?>
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <?php 
                                                    $gradeClass = in_array($course['grade'], ['A', 'A-']) ? 'success' : (in_array($course['grade'], ['B+', 'B']) ? 'info' : 'warning');
                                                    ?>
                                                    <span class="badge bg-<?php echo $gradeClass; ?> mb-1"><?php echo $course['grade']; ?></span>
                                                    <br>
                                                    <small class="text-muted"><?php echo $course['credits']; ?> credits</small>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i><?php echo $course['semester']; ?>
                                                </small>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-primary" onclick="viewCourseDetails('<?php echo $course['code']; ?>')" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-success" onclick="viewGrades('<?php echo $course['code']; ?>')" title="View Grades">
                                                        <i class="fas fa-chart-line"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Activities -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-history"></i> Recent Activities</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($recentActivities)): ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-clock fa-2x text-muted mb-3"></i>
                                        <h6 class="text-muted">No Recent Activities</h6>
                                        <p class="text-muted">No recent activities to display.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="timeline">
                                        <?php foreach ($recentActivities as $activity): ?>
                                        <div class="timeline-item d-flex mb-3">
                                            <div class="timeline-marker bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                <?php
                                                $activityIcons = [
                                                    'assignment' => 'fas fa-file-alt',
                                                    'enrollment' => 'fas fa-book-open',
                                                    'profile' => 'fas fa-user-edit',
                                                    'grade' => 'fas fa-star'
                                                ];
                                                $icon = $activityIcons[$activity['type']] ?? 'fas fa-dot-circle';
                                                ?>
                                                <i class="<?php echo $icon; ?>"></i>
                                            </div>
                                            <div class="timeline-content">
                                                <div class="fw-bold"><?php echo htmlspecialchars($activity['activity']); ?></div>
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?php echo date('F d, Y h:i A', strtotime($activity['date'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="text-center">
                                        <button class="btn btn-outline-primary btn-sm" onclick="viewAllActivities()">
                                            <i class="fas fa-list"></i> View All Activities
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Profile Completion -->
                    <div class="col-md-4">
                        <!-- Profile Completion -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6><i class="fas fa-tasks"></i> Profile Completion</h6>
                            </div>
                            <div class="card-body">
                                <?php
                                $completionItems = [
                                    'Email' => !empty($student['email']),
                                    'Phone' => !empty($student['phone']),
                                    'Profile Photo' => false, // Check if photo exists
                                    'Emergency Contact' => false // Check if emergency contact exists
                                ];
                                $completedCount = count(array_filter($completionItems));
                                $totalItems = count($completionItems);
                                $completionPercentage = ($completedCount / $totalItems) * 100;
                                ?>
                                <div class="text-center mb-3">
                                    <div class="progress mx-auto mb-2" style="width: 80px; height: 80px; border-radius: 50%; background: conic-gradient(#0d6efd <?php echo $completionPercentage; ?>%, #e9ecef 0%);">
                                        <div class="d-flex align-items-center justify-content-center h-100 w-100 bg-white rounded-circle" style="margin: 6px;">
                                            <span class="fw-bold text-primary"><?php echo round($completionPercentage); ?>%</span>
                                        </div>
                                    </div>
                                    <p class="mb-0 text-muted">Profile Complete</p>
                                </div>
                                <div class="completion-checklist">
                                    <?php foreach ($completionItems as $item => $completed): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="<?php echo $completed ? 'text-success' : 'text-muted'; ?>">
                                            <i class="fas fa-<?php echo $completed ? 'check-circle' : 'circle'; ?> me-2"></i>
                                            <?php echo $item; ?>
                                        </span>
                                        <?php if (!$completed): ?>
                                        <button class="btn btn-sm btn-outline-primary" onclick="complete<?php echo str_replace(' ', '', $item); ?>()">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6><i class="fas fa-bolt"></i> Quick Actions</h6>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button class="btn btn-outline-primary" onclick="viewGrades()">
                                        <i class="fas fa-chart-line me-2"></i> View My Grades
                                    </button>
                                    <button class="btn btn-outline-success" onclick="viewSchedule()">
                                        <i class="fas fa-calendar-alt me-2"></i> My Schedule
                                    </button>
                                    <button class="btn btn-outline-info" onclick="browseCourses()">
                                        <i class="fas fa-search me-2"></i> Browse Courses
                                    </button>
                                    <button class="btn btn-outline-warning" onclick="contactSupport()">
                                        <i class="fas fa-life-ring me-2"></i> Contact Support
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-user"></i> Personal Information</h5>
                            <button class="btn btn-sm btn-outline-primary" onclick="editPersonalInfo()">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Student Number</label>
                                <p class="form-control-plaintext"><?php echo htmlspecialchars($student['student_no']); ?></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Date of Birth</label>
                                <p class="form-control-plaintext">
                                    <?php echo isset($student['date_of_birth']) && $student['date_of_birth'] ? 
                                        date('F d, Y', strtotime($student['date_of_birth'])) : 
                                        '<span class="text-muted">Not provided</span>'; ?>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Gender</label>
                                <p class="form-control-plaintext">
                                    <?php echo isset($student['gender']) && $student['gender'] ? 
                                        ucfirst(htmlspecialchars($student['gender'])) : 
                                        '<span class="text-muted">Not specified</span>'; ?>
                                </p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Address</label>
                                <p class="form-control-plaintext">
                                    <?php echo isset($student['address']) && $student['address'] ? 
                                        htmlspecialchars($student['address']) : 
                                        '<span class="text-muted">Not provided</span>'; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Performance -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-chart-bar"></i> Academic Performance</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>Grade Distribution</h6>
                                <?php 
                                $gradeDistribution = array_count_values(array_column($enrolledCourses, 'grade'));
                                $gradeColors = ['A' => 'success', 'A-' => 'success', 'B+' => 'info', 'B' => 'info', 'B-' => 'warning', 'C+' => 'warning', 'C' => 'danger'];
                                ?>
                                <?php foreach ($gradeDistribution as $grade => $count): ?>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-<?php echo $gradeColors[$grade] ?? 'secondary'; ?> me-2"><?php echo $grade; ?></span>
                                        <span>Grade <?php echo $grade; ?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 100px; height: 6px;">
                                            <div class="progress-bar bg-<?php echo $gradeColors[$grade] ?? 'secondary'; ?>" 
                                                 style="width: <?php echo ($count / count($enrolledCourses)) * 100; ?>%"></div>
                                        </div>
                                        <span class="text-muted"><?php echo $count; ?> course<?php echo $count !== 1 ? 's' : ''; ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="gpa-circle bg-primary text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2">
                                        <div>
                                            <div class="fs-4 fw-bold"><?php echo number_format($gpa, 2); ?></div>
                                            <small>GPA</small>
                                        </div>
                                    </div>
                                    <p class="mb-0">
                                        <?php 
                                        if ($gpa >= 3.5) echo '<span class="text-success">Excellent Performance</span>';
                                        elseif ($gpa >= 3.0) echo '<span class="text-info">Good Performance</span>';
                                        elseif ($gpa >= 2.0) echo '<span class="text-warning">Satisfactory</span>';
                                        else echo '<span class="text-danger">Needs Improvement</span>';
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Settings -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-shield-alt"></i> Account Settings</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Security Settings</h6>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <div class="fw-bold">Password</div>
                                            <small class="text-muted">Last changed: 30 days ago</small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-primary" onclick="changePassword()">
                                            <i class="fas fa-key"></i> Change
                                        </button>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <div class="fw-bold">Two-Factor Authentication</div>
                                            <small class="text-muted">Add an extra layer of security</small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-success" onclick="setupTwoFactor()">
                                            <i class="fas fa-shield-alt"></i> Setup
                                        </button>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <div>
                                            <div class="fw-bold">Login Sessions</div>
                                            <small class="text-muted">