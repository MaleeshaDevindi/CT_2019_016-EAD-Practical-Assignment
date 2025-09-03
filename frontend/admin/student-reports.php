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

// API base URL
$apiBaseUrl = 'http://your-api-domain.com/api'; // Change to your actual API URL

// Fetch student report data via API
try {
    // Initialize cURL session for multiple requests
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer YOUR_API_TOKEN' // Add your auth token if needed
    ]);

    // Total students
    curl_setopt($ch, CURLOPT_URL, $apiBaseUrl . '/students/count');
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    $totalStudents = $response['data']['count'] ?? 0;

    // Students by status
    curl_setopt($ch, CURLOPT_URL, $apiBaseUrl . '/students/status-distribution');
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    $studentsByStatus = $response['data'] ?? [];

    // Students by registration month (current year)
    curl_setopt($ch, CURLOPT_URL, $apiBaseUrl . '/students/registration-trends?year=' . date('Y'));
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    $registrationTrends = $response['data'] ?? [];

    // Recent registrations (last 30 days)
    curl_setopt($ch, CURLOPT_URL, $apiBaseUrl . '/students/recent-registrations?days=30');
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    $recentRegistrations = $response['data']['count'] ?? 0;

    // Student details
    curl_setopt($ch, CURLOPT_URL, $apiBaseUrl . '/students?limit=5&sort=-created_at');
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    $studentDetails = $response['data'] ?? [];

    // Calculate averages from API data
    $gpaSum = 0;
    $enrollmentSum = 0;
    $studentCount = count($studentDetails);
    
    foreach ($studentDetails as $student) {
        $gpaSum += $student['gpa'] ?? 0;
        $enrollmentSum += $student['enrolled_courses'] ?? 0;
    }
    
    $averageGPA = $studentCount > 0 ? $gpaSum / $studentCount : 0;
    $averageEnrollments = $studentCount > 0 ? $enrollmentSum / $studentCount : 0;

    curl_close($ch);

} catch (Exception $e) {
    $totalStudents = $recentRegistrations = 0;
    $studentsByStatus = $registrationTrends = $studentDetails = [];
    $averageGPA = $averageEnrollments = 0;
    
    $_SESSION['message'] = "Error fetching student report data: " . $e->getMessage();
    $_SESSION['message-type'] = "error";
}

// Month names for trend chart
$monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Reports - Student Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
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
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1><i class="fas fa-chart-line"></i> Student Reports</h1>
                            <p>Comprehensive analysis and statistics of all students</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-primary" onclick="refreshReports()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-download"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#" onclick="exportReport('pdf')"><i class="fas fa-file-pdf me-2"></i>Export as PDF</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="exportReport('excel')"><i class="fas fa-file-excel me-2"></i>Export as Excel</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="exportReport('csv')"><i class="fas fa-file-csv me-2"></i>Export as CSV</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Statistics Overview -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="card-title mb-1"><?php echo $totalStudents; ?></h3>
                                        <p class="card-text mb-0">Total Students</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-user-graduate fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card bg-success text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="card-title mb-1"><?php echo $recentRegistrations; ?></h3>
                                        <p class="card-text mb-0">New This Month</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-user-plus fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card bg-info text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="card-title mb-1"><?php echo number_format($averageGPA, 2); ?></h3>
                                        <p class="card-text mb-0">Average GPA</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card bg-warning text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="card-title mb-1"><?php echo round($averageEnrollments, 1); ?></h3>
                                        <p class="card-text mb-0">Avg Enrollments</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-book-open fa-2x opacity-75"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Controls -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-filter"></i> Report Filters</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="statusFilter" class="form-label">Status</label>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="gpaFilter" class="form-label">GPA Range</label>
                                <select class="form-select" id="gpaFilter">
                                    <option value="">All GPA</option>
                                    <option value="4.0-3.5">4.0 - 3.5 (Excellent)</option>
                                    <option value="3.4-3.0">3.4 - 3.0 (Good)</option>
                                    <option value="2.9-2.0">2.9 - 2.0 (Satisfactory)</option>
                                    <option value="1.9-0.0">Below 2.0 (At Risk)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="dateFilter" class="form-label">Registration Period</label>
                                <select class="form-select" id="dateFilter">
                                    <option value="">All Time</option>
                                    <option value="today">Today</option>
                                    <option value="week">This Week</option>
                                    <option value="month">This Month</option>
                                    <option value="year">This Year</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100" onclick="applyFilters()">
                                    <i class="fas fa-search"></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Analytics Charts -->
                <div class="row">
                    <!-- Students by Status -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-donut"></i> Students by Status</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($studentsByStatus)): ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-chart-donut fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No status data available</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($studentsByStatus as $status): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <?php
                                            $statusIcons = [
                                                'active' => 'fas fa-check-circle text-success',
                                                'suspended' => 'fas fa-times-circle text-danger',
                                                'graduated' => 'fas fa-graduation-cap text-primary',
                                                'inactive' => 'fas fa-pause-circle text-warning'
                                            ];
                                            $icon = $statusIcons[$status['status']] ?? 'fas fa-question-circle text-secondary';
                                            ?>
                                            <i class="<?php echo $icon; ?> me-2"></i>
                                            <span><?php echo ucfirst(htmlspecialchars($status['status'])); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 100px; height: 8px;">
                                                <div class="progress-bar" style="width: <?php echo ($status['count'] / $totalStudents) * 100; ?>%"></div>
                                            </div>
                                            <span class="badge bg-secondary"><?php echo $status['count']; ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Trends -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-line"></i> Registration Trends (<?php echo date('Y'); ?>)</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($registrationTrends)): ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-chart-line fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No registration data available</p>
                                    </div>
                                <?php else: ?>
                                    <?php 
                                    $maxCount = max(array_column($registrationTrends, 'count'));
                                    foreach ($registrationTrends as $trend): 
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar text-info me-2"></i>
                                            <span><?php echo $monthNames[$trend['month'] - 1]; ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 100px; height: 8px;">
                                                <div class="progress-bar bg-info" style="width: <?php echo ($trend['count'] / $maxCount) * 100; ?>%"></div>
                                            </div>
                                            <span class="badge bg-info"><?php echo $trend['count']; ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Student Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-table"></i> Detailed Student Report</h5>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search students..." style="width: 250px;">
                                <button class="btn btn-outline-success btn-sm" onclick="exportTable('excel')">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <button class="btn btn-outline-danger btn-sm" onclick="exportTable('pdf')">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="studentReportTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Student Details</th>
                                        <th>Contact Info</th>
                                        <th>Academic Info</th>
                                        <th>GPA</th>
                                        <th>Status</th>
                                        <th>Registration Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($studentDetails as $index => $student): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <?php 
                                                    $nameParts = explode(' ', $student['name']);
                                                    $initials = strtoupper(
                                                        substr($nameParts[0], 0, 1) . 
                                                        (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
                                                    );
                                                    echo $initials;
                                                    ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold"><?php echo htmlspecialchars($student['name']); ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars($student['student_no']); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="mb-1">
                                                    <a href="mailto:<?php echo htmlspecialchars($student['email']); ?>" class="text-decoration-none">
                                                        <i class="fas fa-envelope me-1"></i>
                                                        <small><?php echo htmlspecialchars($student['email']); ?></small>
                                                    </a>
                                                </div>
                                                <div>
                                                    <a href="tel:<?php echo htmlspecialchars($student['phone']); ?>" class="text-decoration-none">
                                                        <i class="fas fa-phone me-1"></i>
                                                        <small><?php echo htmlspecialchars($student['phone']); ?></small>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-center">
                                                <div class="fw-bold text-primary"><?php echo $student['enrolled_courses']; ?></div>
                                                <small class="text-muted">Courses</small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php 
                                            $gpa = $student['gpa'];
                                            $gpaClass = $gpa >= 3.5 ? 'success' : ($gpa >= 3.0 ? 'info' : ($gpa >= 2.0 ? 'warning' : 'danger'));
                                            ?>
                                            <div class="text-center">
                                                <span class="badge bg-<?php echo $gpaClass; ?> fs-6"><?php echo number_format($gpa, 2); ?></span>
                                                <br>
                                                <small class="text-muted">
                                                    <?php 
                                                    if ($gpa >= 3.5) echo 'Excellent';
                                                    elseif ($gpa >= 3.0) echo 'Good';
                                                    elseif ($gpa >= 2.0) echo 'Satisfactory';
                                                    else echo 'At Risk';
                                                    ?>
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = $student['status'] === 'active' ? 'success' : 'danger';
                                            $statusIcon = $student['status'] === 'active' ? 'check-circle' : 'times-circle';
                                            ?>
                                            <span class="badge bg-<?php echo $statusClass; ?>">
                                                <i class="fas fa-<?php echo $statusIcon; ?>"></i>
                                                <?php echo ucfirst(htmlspecialchars($student['status'])); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small">
                                                <?php echo date('M d, Y', strtotime($student['created_at'])); ?>
                                                <br>
                                                <span class="text-muted"><?php echo date('h:i A', strtotime($student['created_at'])); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewStudent(<?php echo $student['id']; ?>)" title="View Profile">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-success" onclick="viewGrades(<?php echo $student['id']; ?>)" title="View Grades">
                                                    <i class="fas fa-chart-line"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-info" onclick="generateStudentReport(<?php echo $student['id']; ?>)" title="Generate Report">
                                                    <i class="fas fa-file-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Academic Performance Analysis -->
                <div class="row">
                    <!-- GPA Distribution -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-area"></i> GPA Distribution</h5>
                            </div>
                            <div class="card-body">
                                <?php 
                                $gpaRanges = [
                                    ['range' => '4.0 - 3.5', 'color' => 'success', 'count' => count(array_filter($studentDetails, fn($s) => $s['gpa'] >= 3.5))],
                                    ['range' => '3.4 - 3.0', 'color' => 'info', 'count' => count(array_filter($studentDetails, fn($s) => $s['gpa'] >= 3.0 && $s['gpa'] < 3.5))],
                                    ['range' => '2.9 - 2.0', 'color' => 'warning', 'count' => count(array_filter($studentDetails, fn($s) => $s['gpa'] >= 2.0 && $s['gpa'] < 3.0))],
                                    ['range' => 'Below 2.0', 'color' => 'danger', 'count' => count(array_filter($studentDetails, fn($s) => $s['gpa'] < 2.0))]
                                ];
                                ?>
                                <?php foreach ($gpaRanges as $range): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="gpa-indicator bg-<?php echo $range['color']; ?> rounded me-2"></div>
                                        <span><?php echo $range['range']; ?></span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="progress me-2" style="width: 100px; height: 8px;">
                                            <div class="progress-bar bg-<?php echo $range['color']; ?>" 
                                                 style="width: <?php echo $totalStudents > 0 ? ($range['count'] / $totalStudents) * 100 : 0; ?>%"></div>
                                        </div>
                                        <span class="badge bg-<?php echo $range['color']; ?>"><?php echo $range['count']; ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- At-Risk Students -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-exclamation-triangle"></i> Students Requiring Attention</h5>
                            </div>
                            <div class="card-body">
                                <?php 
                                $atRiskStudents = array_filter($studentDetails, function($student) {
                                    return $student['gpa'] < 2.5 || $student['status'] === 'suspended';
                                });
                                ?>
                                <?php if (empty($atRiskStudents)): ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                        <h6 class="text-success">All Students on Track!</h6>
                                        <p class="text-muted">No students require immediate attention</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($atRiskStudents as $student): ?>
                                    <?php 
                                    $alertClass = $student['status'] === 'suspended' ? 'danger' : ($student['gpa'] < 2.0 ? 'danger' : 'warning');
                                    $alertIcon = $student['status'] === 'suspended' ? 'ban' : 'exclamation-triangle';
                                    $reason = $student['status'] === 'suspended' ? 'Suspended Status' : 'Low GPA (' . $student['gpa'] . ')';
                                    ?>
                                    <div class="alert alert-<?php echo $alertClass; ?> d-flex align-items-center mb-2" role="alert">
                                        <i class="fas fa-<?php echo $alertIcon; ?> me-2"></i>
                                        <div class="flex-grow-1">
                                            <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                                            <br>
                                            <small><?php echo htmlspecialchars($student['student_no']); ?> - <?php echo $reason; ?></small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-<?php echo $alertClass; ?>" onclick="viewStudent(<?php echo $student['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Performers -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-trophy"></i> Top Performing Students</h5>
                    </div>
                    <div class="card-body">
                        <?php 
                        $topStudents = $studentDetails;
                        usort($topStudents, function($a, $b) {
                            return $b['gpa'] <=> $a['gpa'];
                        });
                        $topStudents = array_slice($topStudents, 0, 5);
                        ?>
                        <div class="row">
                            <?php foreach ($topStudents as $index => $student): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card border-success">
                                    <div class="card-body text-center">
                                        <div class="position-relative">
                                            <?php if ($index === 0): ?>
                                                <i class="fas fa-crown text-warning position-absolute" style="top: -10px; right: 10px;"></i>
                                            <?php endif; ?>
                                            <div class="avatar-lg bg-success text-white rounded-circle mx-auto d-flex align-items-center justify-content-center mb-2">
                                                <?php 
                                                $nameParts = explode(' ', $student['name']);
                                                $initials = strtoupper(
                                                    substr($nameParts[0], 0, 1) . 
                                                    (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : '')
                                                );
                                                echo $initials;
                                                ?>
                                            </div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($student['name']); ?></h6>
                                            <small class="text-muted"><?php echo htmlspecialchars($student['student_no']); ?></small>
                                            <div class="mt-2">
                                                <span class="badge bg-success fs-6"><?php echo number_format($student['gpa'], 2); ?> GPA</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Summary Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-clipboard-list"></i> Student Summary Report</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Status Distribution</h6>
                                        <ul class="list-unstyled">
                                            <?php foreach ($studentsByStatus as $status): ?>
                                            <li class="mb-1">
                                                <?php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'suspended' => 'danger',
                                                    'graduated' => 'primary',
                                                    'inactive' => 'warning'
                                                ];
                                                $color = $statusColors[$status['status']] ?? 'secondary';
                                                ?>
                                                <i class="fas fa-circle text-<?php echo $color; ?> me-2"></i>
                                                <?php echo ucfirst(htmlspecialchars($status['status'])); ?>: 
                                                <strong><?php echo $status['count']; ?> students</strong>
                                                <small class="text-muted">(<?php echo round(($status['count'] / $totalStudents) * 100, 1); ?>%)</small>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Academic Performance</h6>
                                        <ul class="list-unstyled">
                                            <li class="mb-1">
                                                <i class="fas fa-star text-success me-2"></i>
                                                Average GPA: <strong><?php echo number_format($averageGPA, 2); ?></strong>
                                            </li>
                                            <li class="mb-1">
                                                <i class="fas fa-book text-info me-2"></i>
                                                Avg Enrollments: <strong><?php echo round($averageEnrollments, 1); ?> courses</strong>
                                            </li>
                                            <li class="mb-1">
                                                <i class="fas fa-trophy text-warning me-2"></i>
                                                Top GPA: <strong><?php echo max(array_column($studentDetails, 'gpa')); ?></strong>
                                            </li>
                                            <li class="mb-1">
                                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                                At Risk: <strong><?php echo count($atRiskStudents); ?> students</strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded">
                                    <h6>Report Details</h6>
                                    <p class="mb-1"><i class="fas fa-calendar me-2"></i><?php echo date('F d, Y'); ?></p>
                                    <p class="mb-1"><i class="fas fa-clock me-2"></i><?php echo date('h:i A'); ?></p>
                                    <p class="mb-1"><i class="fas fa-user me-2"></i>Admin User</p>
                                    <p class="mb-0"><i class="fas fa-database me-2"></i><?php echo $totalStudents; ?> Records</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Print/Export Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-print"></i> Print & Export Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-primary w-100" onclick="printReport()">
                                    <i class="fas fa-print"></i><br>
                                    <small>Print Report</small>
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-danger w-100" onclick="exportReport('pdf')">
                                    <i class="fas fa-file-pdf"></i><br>
                                    <small>Export PDF</small>
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-success w-100" onclick="exportReport('excel')">
                                    <i class="fas fa-file-excel"></i><br>
                                    <small>Export Excel</small>
                                </button>
                            </div>
                            <div class="col-md-3 mb-2">
                                <button class="btn btn-outline-info w-100" onclick="emailReport()">
                                    <i class="fas fa-envelope"></i><br>
                                    <small>Email Report</small>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // API base URL for JavaScript
        const API_BASE_URL = '<?php echo $apiBaseUrl; ?>';
        
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
            filterTable();
        });

        function filterTable() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const table = document.getElementById('studentReportTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const studentName = row.cells[1].textContent.toLowerCase();
                const contact = row.cells[2].textContent.toLowerCase();
                const studentNo = row.cells[1].querySelector('small').textContent.toLowerCase();

                const matchesSearch = studentName.includes(searchTerm) || 
                                    contact.includes(searchTerm) || 
                                    studentNo.includes(searchTerm);

                if (matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Apply filters
        function applyFilters() {
            const status = document.getElementById('statusFilter').value;
            const gpa = document.getElementById('gpaFilter').value;
            const date = document.getElementById('dateFilter').value;
            
            Swal.fire({
                title: 'Applying Filters...',
                text: 'Filtering student data based on selected criteria.',
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            });
            
            // Make API call to filter data
            fetch(`${API_BASE_URL}/students/filter`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ status, gpa, date })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the table with filtered data
                    updateStudentTable(data.data);
                } else {
                    Swal.fire('Error', data.message || 'Failed to filter data', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while filtering data', 'error');
            });
        }

        function updateStudentTable(students) {
            const tableBody = document.querySelector('#studentReportTable tbody');
            tableBody.innerHTML = '';
            
            students.forEach((student, index) => {
                const row = document.createElement('tr');
                
                // Format GPA badge
                const gpa = student.gpa;
                const gpaClass = gpa >= 3.5 ? 'success' : (gpa >= 3.0 ? 'info' : (gpa >= 2.0 ? 'warning' : 'danger'));
                const gpaText = gpa >= 3.5 ? 'Excellent' : (gpa >= 3.0 ? 'Good' : (gpa >= 2.0 ? 'Satisfactory' : 'At Risk'));
                
                // Format status badge
                const statusClass = student.status === 'active' ? 'success' : 'danger';
                const statusIcon = student.status === 'active' ? 'check-circle' : 'times-circle';
                
                // Format name initials
                const nameParts = student.name.split(' ');
                const initials = nameParts[0].charAt(0).toUpperCase() + 
                               (nameParts[1] ? nameParts[1].charAt(0).toUpperCase() : '');
                
                // Format date
                const createdDate = new Date(student.created_at);
                const formattedDate = createdDate.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                const formattedTime = createdDate.toLocaleTimeString('en-US', {
                    hour: 'numeric',
                    minute: 'numeric',
                    hour12: true
                });
                
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                ${initials}
                            </div>
                            <div>
                                <div class="fw-bold">${student.name}</div>
                                <small class="text-muted">${student.student_no}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div class="mb-1">
                                <a href="mailto:${student.email}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i>
                                    <small>${student.email}</small>
                                </a>
                            </div>
                            <div>
                                <a href="tel:${student.phone}" class="text-decoration-none">
                                    <i class="fas fa-phone me-1"></i>
                                    <small>${student.phone}</small>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-center">
                            <div class="fw-bold text-primary">${student.enrolled_courses}</div>
                            <small class="text-muted">Courses</small>
                        </div>
                    </td>
                    <td>
                        <div class="text-center">
                            <span class="badge bg-${gpaClass} fs-6">${gpa.toFixed(2)}</span>
                            <br>
                            <small class="text-muted">${gpaText}</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-${statusClass}">
                            <i class="fas fa-${statusIcon}"></i>
                            ${student.status.charAt(0).toUpperCase() + student.status.slice(1)}
                        </span>
                    </td>
                    <td>
                        <div class="small">
                            ${formattedDate}
                            <br>
                            <span class="text-muted">${formattedTime}</span>
                        </div>
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewStudent(${student.id})" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="viewGrades(${student.id})" title="View Grades">
                                <i class="fas fa-chart-line"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-info" onclick="generateStudentReport(${student.id})" title="Generate Report">
                                <i class="fas fa-file-alt"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
        }

        // Refresh reports
        function refreshReports() {
            Swal.fire({
                title: 'Refreshing Reports...',
                html: 'Please wait while we update the student data.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make API call to refresh data
            fetch(`${API_BASE_URL}/students/refresh`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        Swal.fire('Error', data.message || 'Failed to refresh data', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'An error occurred while refreshing data', 'error');
                });
        }

        // Export report functions
        function exportReport(format) {
            Swal.fire({
                title: 'Exporting Report...',
                text: `Generating ${format.toUpperCase()} report`,
                icon: 'info',
                showConfirmButton: false,
                timer: 2000
            });
            
            window.open(`${API_BASE_URL}/reports/export?format=${format}`, '_blank');
        }

        // Export table
        function exportTable(format) {
            const filters = {
                status: document.getElementById('statusFilter').value,
                gpa: document.getElementById('gpaFilter').value,
                date: document.getElementById('dateFilter').value,
                search: document.getElementById('searchInput').value
            };
            
            const queryString = new URLSearchParams(filters).toString();
            window.open(`${API_BASE_URL}/reports/export-table?format=${format}&${queryString}`, '_blank');
        }

        // Print report
        function printReport() {
            window.print();
        }

        // Email report
        function emailReport() {
            Swal.fire({
                title: 'Email Report',
                html: `
                    <div class="mb-3">
                        <label for="swal-input1" class="form-label">Email Address</label>
                        <input id="swal-input1" class="swal2-input" placeholder="Enter email address" type="email">
                    </div>
                    <div class="mb-3">
                        <label for="swal-input2" class="form-label">Report Format</label>
                        <select id="swal-input2" class="swal2-input">
                            <option value="pdf">PDF Report</option>
                            <option value="excel">Excel Spreadsheet</option>
                            <option value="summary">Summary Only</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="swal-input3" class="form-label">Include</label>
                        <select id="swal-input3" class="swal2-input">
                            <option value="all">All Students</option>
                            <option value="active">Active Students Only</option>
                            <option value="at_risk">At-Risk Students Only</option>
                            <option value="top_performers">Top Performers Only</option>
                        </select>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Send Email',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const email = document.getElementById('swal-input1').value;
                    const format = document.getElementById('swal-input2').value;
                    const include = document.getElementById('swal-input3').value;
                    
                    if (!email) {
                        Swal.showValidationMessage('Please enter an email address');
                        return false;
                    }
                    
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        Swal.showValidationMessage('Please enter a valid email address');
                        return false;
                    }
                    
                    return { email: email, format: format, include: include };
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`${API_BASE_URL}/reports/email`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Email Sent!',
                                `Student report has been sent to ${result.value.email}`,
                                'success'
                            );
                        } else {
                            Swal.fire(
                                'Error!',
                                data.message || 'Failed to send email.',
                                'error'
                            );
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error!',
                            'An error occurred while sending the email.',
                            'error'
                        );
                    });
                }
            });
        }

        // View student profile
        function viewStudent(studentId) {
            window.location.href = `student-profile.php?id=${studentId}`;
        }

        // View student grades
        function viewGrades(studentId) {
            window.location.href = `student-grades.php?id=${studentId}`;
        }

        // Generate individual student report
        function generateStudentReport(studentId) {
            Swal.fire({
                title: 'Generate Student Report',
                text: 'Choose report format:',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-file-pdf"></i> PDF',
                cancelButtonText: '<i class="fas fa-file-excel"></i> Excel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.open(`${API_BASE_URL}/reports/student/${studentId}?format=pdf`, '_blank');
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    window.open(`${API_BASE_URL}/reports/student/${studentId}?format=excel`, '_blank');
                }
            });
        }

        // Auto-refresh every 10 minutes
        setInterval(function() {
            fetch(`${API_BASE_URL}/students/stats`)
                .then(response => response.json())
                .then(data => {
                    if (data.updated) {
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        
                        toast.fire({
                            icon: 'info',
                            title: 'Student data updated'
                        });
                    }
                })
                .catch(error => {
                    console.log('Auto-refresh failed:', error);
                });
        }, 600000); // 10 minutes
    </script>
</body>
</html>