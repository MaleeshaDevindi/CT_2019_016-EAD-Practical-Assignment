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

// Fetch course report data via API
try {
    // Initialize cURL session for multiple requests
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer YOUR_API_TOKEN' // Add your auth token if needed
    ]);

    // Total courses
    curl_setopt($ch, CURLOPT_URL, $apiBaseUrl . '/courses/count');
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    $totalCourses = $response['data']['count'] ?? 0;

    // Courses by category
    curl_setopt($ch, CURLOPT_URL, $apiBaseUrl . '/courses/category-distribution');
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    $coursesByCategory = $response['data'] ?? [];

    // Courses by level
    curl_setopt($ch, CURLOPT_URL, $apiBaseUrl . '/courses/level-distribution');
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    $coursesByLevel = $response['data'] ?? [];

    // Course enrollment statistics
    curl_setopt($ch, CURLOPT_URL, $apiBaseUrl . '/courses/stats?limit=5');
    $result = curl_exec($ch);
    $response = json_decode($result, true);
    $courseStats = $response['data'] ?? [];

    // Calculate totals from API data
    $totalEnrollments = 0;
    $totalCapacity = 0;
    $totalCredits = 0;
    
    foreach ($courseStats as $course) {
        $totalEnrollments += $course['enrolled'] ?? 0;
        $totalCapacity += $course['capacity'] ?? 0;
        $totalCredits += $course['credits'] ?? 0;
    }

    curl_close($ch);

} catch (Exception $e) {
    $totalCourses = 0;
    $coursesByCategory = $coursesByLevel = $courseStats = [];
    $totalEnrollments = $totalCapacity = $totalCredits = 0;
    
    $_SESSION['message'] = "Error fetching course report data: " . $e->getMessage();
    $_SESSION['message-type'] = "error";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Reports - Student Management System</title>
    
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
                            <h1><i class="fas fa-chart-line"></i> Course Reports</h1>
                            <p>Comprehensive analysis and statistics of all courses</p>
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

        <!-- Course Statistics Overview -->
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h3 class="card-title mb-1"><?php echo $totalCourses; ?></h3>
                                        <p class="card-text mb-0">Total Courses</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-book fa-2x opacity-75"></i>
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
                                        <h3 class="card-title mb-1"><?php echo $totalEnrollments; ?></h3>
                                        <p class="card-text mb-0">Total Enrollments</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-users fa-2x opacity-75"></i>
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
                                        <h3 class="card-title mb-1"><?php echo $totalCredits; ?></h3>
                                        <p class="card-text mb-0">Total Credits</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-graduation-cap fa-2x opacity-75"></i>
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
                                        <h3 class="card-title mb-1"><?php echo round(($totalEnrollments / max($totalCapacity, 1)) * 100, 1); ?>%</h3>
                                        <p class="card-text mb-0">Capacity Utilization</p>
                                    </div>
                                    <div class="stats-icon">
                                        <i class="fas fa-chart-pie fa-2x opacity-75"></i>
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
                                <label for="categoryFilter" class="form-label">Category</label>
                                <select class="form-select" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    <option value="IT">Information Technology</option>
                                    <option value="SE">Software Engineering</option>
                                    <option value="CS">Computer Science</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="levelFilter" class="form-label">Level</label>
                                <select class="form-select" id="levelFilter">
                                    <option value="">All Levels</option>
                                    <option value="3-year">3rd Year</option>
                                    <option value="4-year">4th Year</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="semesterFilter" class="form-label">Semester</label>
                                <select class="form-select" id="semesterFilter">
                                    <option value="">All Semesters</option>
                                    <option value="first-semester">1st Semester</option>
                                    <option value="second-semester">2nd Semester</option>
                                    <option value="any-semester">Any Semester</option>
                                    <option value="both-semesters">Both Semesters</option>
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

                <!-- Course Statistics Charts -->
                <div class="row">
                    <!-- Courses by Category -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-donut"></i> Courses by Category</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($coursesByCategory)): ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-chart-donut fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No category data available</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($coursesByCategory as $category): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <?php
                                            $categoryIcons = [
                                                'IT' => 'fas fa-laptop-code text-primary',
                                                'SE' => 'fas fa-code-branch text-success',
                                                'CS' => 'fas fa-desktop text-info'
                                            ];
                                            $icon = $categoryIcons[$category['category']] ?? 'fas fa-book text-secondary';
                                            ?>
                                            <i class="<?php echo $icon; ?> me-2"></i>
                                            <span><?php echo htmlspecialchars($category['category']); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 100px; height: 8px;">
                                                <div class="progress-bar" style="width: <?php echo ($category['count'] / $totalCourses) * 100; ?>%"></div>
                                            </div>
                                            <span class="badge bg-secondary"><?php echo $category['count']; ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Courses by Level -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-chart-bar"></i> Courses by Level</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($coursesByLevel)): ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No level data available</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($coursesByLevel as $level): ?>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-layer-group text-warning me-2"></i>
                                            <span><?php echo htmlspecialchars($level['level']); ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div class="progress me-2" style="width: 100px; height: 8px;">
                                                <div class="progress-bar bg-warning" style="width: <?php echo ($level['count'] / $totalCourses) * 100; ?>%"></div>
                                            </div>
                                            <span class="badge bg-warning"><?php echo $level['count']; ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Course Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5><i class="fas fa-table"></i> Detailed Course Report</h5>
                            <div class="d-flex gap-2">
                                <input type="text" class="form-control" id="searchInput" placeholder="Search courses..." style="width: 250px;">
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
                            <table class="table table-striped table-hover" id="courseReportTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Course Code</th>
                                        <th>Course Title</th>
                                        <th>Category</th>
                                        <th>Credits</th>
                                        <th>Enrolled</th>
                                        <th>Capacity</th>
                                        <th>Utilization</th>
                                        <th>Instructor</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($courseStats as $index => $course): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td>
                                            <strong class="text-primary"><?php echo htmlspecialchars($course['code']); ?></strong>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="fw-bold"><?php echo htmlspecialchars($course['title']); ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $categoryColors = [
                                                'IT' => 'primary',
                                                'SE' => 'success', 
                                                'CS' => 'info'
                                            ];
                                            $color = $categoryColors[$course['category']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?php echo $color; ?>"><?php echo htmlspecialchars($course['category']); ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark"><?php echo $course['credits']; ?></span>
                                        </td>
                                        <td>
                                            <strong class="text-success"><?php echo $course['enrolled']; ?></strong>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo $course['capacity']; ?></span>
                                        </td>
                                        <td>
                                            <?php 
                                            $utilization = ($course['enrolled'] / max($course['capacity'], 1)) * 100;
                                            $utilizationClass = $utilization >= 90 ? 'danger' : ($utilization >= 75 ? 'warning' : 'success');
                                            ?>
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-2" style="width: 60px; height: 6px;">
                                                    <div class="progress-bar bg-<?php echo $utilizationClass; ?>" 
                                                         style="width: <?php echo $utilization; ?>%"></div>
                                                </div>
                                                <small class="text-<?php echo $utilizationClass; ?>"><?php echo round($utilization, 1); ?>%</small>
                                            </div>
                                        </td>
                                        <td>
                                            <small><?php echo htmlspecialchars($course['instructor']); ?></small>
                                        </td>
                                        <td>
                                            <?php if ($utilization >= 100): ?>
                                                <span class="badge bg-danger">Full</span>
                                            <?php elseif ($utilization >= 90): ?>
                                                <span class="badge bg-warning">Almost Full</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Available</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Course Performance Analytics -->
                <div class="row">
                    <!-- Most Popular Courses -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-star"></i> Most Popular Courses</h5>
                            </div>
                            <div class="card-body">
                                <?php 
                                // Sort courses by enrollment
                                $popularCourses = $courseStats;
                                usort($popularCourses, function($a, $b) {
                                    return $b['enrolled'] - $a['enrolled'];
                                });
                                $popularCourses = array_slice($popularCourses, 0, 5);
                                ?>
                                <?php foreach ($popularCourses as $index => $course): ?>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rank-badge bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <?php echo $index + 1; ?>
                                        </div>
                                        <div>
                                            <div class="fw-bold"><?php echo htmlspecialchars($course['code']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($course['title']); ?></small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-success"><?php echo $course['enrolled']; ?> students</div>
                                        <small class="text-muted"><?php echo $course['credits']; ?> credits</small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Course Capacity Analysis -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5><i class="fas fa-exclamation-triangle"></i> Capacity Alerts</h5>
                            </div>
                            <div class="card-body">
                                <?php 
                                // Filter courses with high utilization
                                $highUtilizationCourses = array_filter($courseStats, function($course) {
                                    return ($course['enrolled'] / max($course['capacity'], 1)) >= 0.8;
                                });
                                ?>
                                <?php if (empty($highUtilizationCourses)): ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                        <h6 class="text-success">All Good!</h6>
                                        <p class="text-muted">No capacity issues detected</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($highUtilizationCourses as $course): ?>
                                    <?php 
                                    $utilization = ($course['enrolled'] / max($course['capacity'], 1)) * 100;
                                    $alertClass = $utilization >= 100 ? 'danger' : ($utilization >= 90 ? 'warning' : 'info');
                                    $alertIcon = $utilization >= 100 ? 'times-circle' : 'exclamation-triangle';
                                    ?>
                                    <div class="alert alert-<?php echo $alertClass; ?> d-flex align-items-center mb-2" role="alert">
                                        <i class="fas fa-<?php echo $alertIcon; ?> me-2"></i>
                                        <div class="flex-grow-1">
                                            <strong><?php echo htmlspecialchars($course['code']); ?></strong>
                                            <br>
                                            <small><?php echo $course['enrolled']; ?>/<?php echo $course['capacity']; ?> students (<?php echo round($utilization, 1); ?>%)</small>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary Report -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-clipboard-list"></i> Course Summary Report</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Course Distribution</h6>
                                        <ul class="list-unstyled">
                                            <?php foreach ($coursesByCategory as $category): ?>
                                            <li class="mb-1">
                                                <i class="fas fa-circle text-primary me-2"></i>
                                                <?php echo htmlspecialchars($category['category']); ?>: 
                                                <strong><?php echo $category['count']; ?> courses</strong>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Level Distribution</h6>
                                        <ul class="list-unstyled">
                                            <?php foreach ($coursesByLevel as $level): ?>
                                            <li class="mb-1">
                                                <i class="fas fa-layer-group text-warning me-2"></i>
                                                <?php echo htmlspecialchars($level['level']); ?>: 
                                                <strong><?php echo $level['count']; ?> courses</strong>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light p-3 rounded">
                                    <h6>Report Generated</h6>
                                    <p class="mb-1"><i class="fas fa-calendar me-2"></i><?php echo date('F d, Y'); ?></p>
                                    <p class="mb-1"><i class="fas fa-clock me-2"></i><?php echo date('h:i A'); ?></p>
                                    <p class="mb-0"><i class="fas fa-user me-2"></i>Admin User</p>
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
            const table = document.getElementById('courseReportTable');
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const courseCode = row.cells[1].textContent.toLowerCase();
                const courseTitle = row.cells[2].textContent.toLowerCase();
                const category = row.cells[3].textContent.toLowerCase();
                const instructor = row.cells[8].textContent.toLowerCase();

                const matchesSearch = courseCode.includes(searchTerm) || 
                                    courseTitle.includes(searchTerm) || 
                                    category.includes(searchTerm) ||
                                    instructor.includes(searchTerm);

                if (matchesSearch) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        // Apply filters
        function applyFilters() {
            const category = document.getElementById('categoryFilter').value;
            const level = document.getElementById('levelFilter').value;
            const semester = document.getElementById('semesterFilter').value;
            
            Swal.fire({
                title: 'Applying Filters...',
                text: 'Filtering course data based on selected criteria.',
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            });
            
            // Make API call to filter data
            fetch(`${API_BASE_URL}/courses/filter`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ category, level, semester })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the table with filtered data
                    updateCourseTable(data.data);
                } else {
                    Swal.fire('Error', data.message || 'Failed to filter data', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'An error occurred while filtering data', 'error');
            });
        }

        function updateCourseTable(courses) {
            const tableBody = document.querySelector('#courseReportTable tbody');
            tableBody.innerHTML = '';
            
            courses.forEach((course, index) => {
                const row = document.createElement('tr');
                
                // Format utilization
                const utilization = (course.enrolled / Math.max(course.capacity, 1)) * 100;
                const utilizationClass = utilization >= 90 ? 'danger' : (utilization >= 75 ? 'warning' : 'success');
                
                // Format category badge
                const categoryColors = {
                    'IT': 'primary',
                    'SE': 'success', 
                    'CS': 'info'
                };
                const categoryColor = categoryColors[course.category] || 'secondary';
                
                // Format status
                let statusBadge = '';
                if (utilization >= 100) {
                    statusBadge = '<span class="badge bg-danger">Full</span>';
                } else if (utilization >= 90) {
                    statusBadge = '<span class="badge bg-warning">Almost Full</span>';
                } else {
                    statusBadge = '<span class="badge bg-success">Available</span>';
                }
                
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>
                        <strong class="text-primary">${course.code}</strong>
                    </td>
                    <td>
                        <div>
                            <div class="fw-bold">${course.title}</div>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-${categoryColor}">${course.category}</span>
                    </td>
                    <td>
                        <span class="badge bg-dark">${course.credits}</span>
                    </td>
                    <td>
                        <strong class="text-success">${course.enrolled}</strong>
                    </td>
                    <td>
                        <span class="text-muted">${course.capacity}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="progress me-2" style="width: 60px; height: 6px;">
                                <div class="progress-bar bg-${utilizationClass}" 
                                     style="width: ${utilization}%"></div>
                            </div>
                            <small class="text-${utilizationClass}">${utilization.toFixed(1)}%</small>
                        </div>
                    </td>
                    <td>
                        <small>${course.instructor}</small>
                    </td>
                    <td>
                        ${statusBadge}
                    </td>
                `;
                
                tableBody.appendChild(row);
            });
        }

        // Refresh reports
        function refreshReports() {
            Swal.fire({
                title: 'Refreshing Reports...',
                html: 'Please wait while we update the course data.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Make API call to refresh data
            fetch(`${API_BASE_URL}/courses/refresh`)
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
            
            window.open(`${API_BASE_URL}/reports/courses/export?format=${format}`, '_blank');
        }

        // Export table
        function exportTable(format) {
            const filters = {
                category: document.getElementById('categoryFilter').value,
                level: document.getElementById('levelFilter').value,
                semester: document.getElementById('semesterFilter').value,
                search: document.getElementById('searchInput').value
            };
            
            const queryString = new URLSearchParams(filters).toString();
            window.open(`${API_BASE_URL}/reports/courses/export-table?format=${format}&${queryString}`, '_blank');
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
                `,
                showCancelButton: true,
                confirmButtonText: 'Send Email',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const email = document.getElementById('swal-input1').value;
                    const format = document.getElementById('swal-input2').value;
                    
                    if (!email) {
                        Swal.showValidationMessage('Please enter an email address');
                        return false;
                    }
                    
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                        Swal.showValidationMessage('Please enter a valid email address');
                        return false;
                    }
                    
                    return { email: email, format: format };
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send email request
                    fetch(`${API_BASE_URL}/reports/courses/email`, {
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
                                `Course report has been sent to ${result.value.email}`,
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

        // Auto-refresh every 10 minutes
        setInterval(function() {
            fetch(`${API_BASE_URL}/courses/stats`)
                .then(response => response.json())
                .then(data => {
                    if (data.updated) {
                        // Show subtle notification of data update
                        const toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        
                        toast.fire({
                            icon: 'info',
                            title: 'Data updated'
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