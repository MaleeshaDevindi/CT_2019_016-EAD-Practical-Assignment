<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Courses - Student Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .course-card {
            transition: transform 0.3s, box-shadow 0.3s;
            height: 100%;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .enrolled-badge {
            position: absolute;
            top: 15px;
            right: 15px;
        }
        .filter-btn.active {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Include Student Sidebar -->
    <?php include 'sidemenu.php'; ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-book-open me-2"></i>All Courses</h1>
            <p>Browse and enroll in available courses</p>
        </div>

        <!-- Search and Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search courses...">
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select id="categoryFilter" class="form-select">
                            <option value="">All Categories</option>
                            <option value="IT">IT</option>
                            <option value="SE">SE</option>
                            <option value="CS">CS</option>
                        </select>
                    </div>
                </div>
                
                <div class="mt-3 d-flex flex-wrap gap-2">
                    <button class="filter-btn btn btn-outline-primary btn-sm active" data-filter="all">All</button>
                    <button class="filter-btn btn btn-outline-primary btn-sm" data-filter="3-year">Level III</button>
                    <button class="filter-btn btn btn-outline-primary btn-sm" data-filter="4-year">Level IV</button>
                    <button class="filter-btn btn btn-outline-primary btn-sm" data-filter="available">Available</button>
                    <button class="filter-btn btn btn-outline-primary btn-sm" data-filter="enrolled">My Enrollments</button>
                </div>
            </div>
        </div>

        <!-- Course Cards View -->
        <div id="courseCardsContainer">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="courseCards">
                <!-- Courses will be loaded via JavaScript -->
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="text-center my-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading courses...</p>
        </div>

        <!-- No Courses Message -->
        <div id="noCoursesMessage" class="alert alert-info text-center" style="display: none;">
            <i class="fas fa-info-circle me-2"></i>No courses found matching your criteria.
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-4" id="loadMoreContainer" style="display: none;">
            <button id="loadMoreBtn" class="btn btn-outline-primary">Load More Courses</button>
        </div>

<footer>
    @copy; CT/019/016 | All Rights Reserved
</footer>

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let allCourses = [];
        let enrolledCourses = [];
        let currentFilter = 'all';
        let currentSearch = '';
        let currentCategory = '';
        let displayedCount = 6;

        // Load courses on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadEnrolledCourses();
            loadAllCourses();
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('searchBtn').addEventListener('click', applyFilters);
            document.getElementById('searchInput').addEventListener('keyup', function(event) {
                if (event.key === 'Enter') applyFilters();
            });
            document.getElementById('categoryFilter').addEventListener('change', applyFilters);
            
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    currentFilter = this.dataset.filter;
                    applyFilters();
                });
            });
            
            document.getElementById('loadMoreBtn').addEventListener('click', function() {
                displayedCount += 6;
                displayCourses();
            });
        }

        // Load enrolled courses
        async function loadEnrolledCourses() {
            try {
                const response = await fetch('../../api/students/enrollments.php');
                const result = await response.json();
                
                if (result.status === 'success') {
                    enrolledCourses = result.data.map(course => course.course_id);
                }
            } catch (error) {
                console.error('Error loading enrolled courses:', error);
            }
        }

        // Load all courses
        async function loadAllCourses() {
            try {
                const response = await fetch('../../api/courses/index.php');
                const result = await response.json();
                
                if (result.status === 'success') {
                    allCourses = result.data;
                    displayCourses();
                } else {
                    showError('Failed to load courses');
                }
            } catch (error) {
                showError('Unable to connect to server');
            } finally {
                document.getElementById('loadingSpinner').style.display = 'none';
            }
        }

        // Apply filters and search
        function applyFilters() {
            currentSearch = document.getElementById('searchInput').value.toLowerCase();
            currentCategory = document.getElementById('categoryFilter').value;
            displayedCount = 6; // Reset to initial count
            displayCourses();
        }

        // Display filtered courses
        function displayCourses() {
            const container = document.getElementById('courseCards');
            
            // Filter courses based on criteria
            let filteredCourses = allCourses.filter(course => {
                // Search filter
                const matchesSearch = currentSearch === '' || 
                    course.title.toLowerCase().includes(currentSearch) ||
                    course.description.toLowerCase().includes(currentSearch) ||
                    course.code.toLowerCase().includes(currentSearch);
                
                // Category filter
                const matchesCategory = currentCategory === '' || course.category === currentCategory;
                
                // Additional filters
                let matchesFilter = true;
                if (currentFilter === '3-year') matchesFilter = course.level === '3-year';
                if (currentFilter === '4-year') matchesFilter = course.level === '4-year';
                if (currentFilter === 'enrolled') matchesFilter = enrolledCourses.includes(course.id.toString());
                if (currentFilter === 'available') matchesFilter = !enrolledCourses.includes(course.id.toString());
                
                return matchesSearch && matchesCategory && matchesFilter;
            });
            
            // Show/hide no courses message
            if (filteredCourses.length === 0) {
                document.getElementById('noCoursesMessage').style.display = 'block';
                container.innerHTML = '';
            } else {
                document.getElementById('noCoursesMessage').style.display = 'none';
                
                // Display limited number of courses
                const coursesToShow = filteredCourses.slice(0, displayedCount);
                
                container.innerHTML = coursesToShow.map(course => {
                    const isEnrolled = enrolledCourses.includes(course.id.toString());
                    
                    return `
                        <div class="col">
                            <div class="card h-100 course-card">
                                ${isEnrolled ? 
                                    `<span class="enrolled-badge badge bg-success"><i class="fas fa-check-circle me-1"></i>Enrolled</span>` : 
                                    ''}
                                
                                <div class="card-body">
                                    <h5 class="card-title">${course.title}</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-secondary">${course.code}</span>
                                        <span class="badge bg-info">${course.credits} Credits</span>
                                    </div>
                                    <p class="card-text text-muted">${course.description.substring(0, 100)}...</p>
                                    <div class="course-meta">
                                        <div class="row small text-center mb-3">
                                            <div class="col-4">
                                                <div><i class="fas fa-layer-group"></i></div>
                                                <div>${course.level}</div>
                                            </div>
                                            <div class="col-4">
                                                <div><i class="fas fa-tag"></i></div>
                                                <div>${course.category}</div>
                                            </div>
                                            <div class="col-4">
                                                <div><i class="fas fa-clock"></i></div>
                                                <div>${course.duration || 'N/A'}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <div class="d-grid gap-2">
                                        <a href="course-details.php?id=${course.id}" class="btn btn-outline-primary">
                                            <i class="fas fa-info-circle me-1"></i>View Details
                                        </a>
                                        ${!isEnrolled ? 
                                            `<a href="course-details.php?id=${course.id}&enroll=true" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i>Enroll
                                            </a>` : 
                                            `<a href="course-details.php?id=${course.id}" class="btn btn-success">
                                                <i class="fas fa-play-circle me-1"></i>Continue Learning
                                            </a>`
                                        }
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
                
                // Show/hide load more button
                if (filteredCourses.length > displayedCount) {
                    document.getElementById('loadMoreContainer').style.display = 'block';
                } else {
                    document.getElementById('loadMoreContainer').style.display = 'none';
                }
            }
        }

        // Show error message
        function showError(message) {
            alert('Error: ' + message);
        }
    </script>
</body>
</html>