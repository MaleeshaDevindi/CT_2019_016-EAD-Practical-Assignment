<?php
// Get current page name to highlight active menu item
$current_page = basename($_SERVER['PHP_SELF']);

?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-graduation-cap"></i> Course Manager</h4>
    </div>
    
    <ul class="sidebar-nav">
        <!-- Dashboard -->
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Courses Menu -->
        <li class="nav-item has-submenu">
            <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#coursesSubmenu" 
               aria-expanded="<?php echo (in_array($current_page, ['add-course.php', 'all-courses.php', 'update-course.php', 'course-details.php'])) ? 'true' : 'false'; ?>">
                <i class="fas fa-book"></i>
                <span>Courses</span>
                <i class="fas fa-chevron-down arrow"></i>
            </a>
            <div class="collapse <?php echo (in_array($current_page, ['add-course.php', 'all-courses.php', 'update-course.php', 'course-details.php'])) ? 'show' : ''; ?>" 
                 id="coursesSubmenu">
                <ul class="submenu">
                    <li>
                        <a href="add-course.php" class="submenu-link <?php echo ($current_page == 'add-course.php') ? 'active' : ''; ?>">
                            <i class="fas fa-plus"></i> Add Course
                        </a>
                    </li>
                    <li>
                        <a href="all-courses.php" class="submenu-link <?php echo ($current_page == 'all-courses.php') ? 'active' : ''; ?>">
                            <i class="fas fa-list"></i> All Courses
                        </a>
                    </li>
                    
                </ul>
            </div>
        </li>

        <!-- Students Menu -->
        <li class="nav-item has-submenu">
            <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#studentsSubmenu" aria-expanded="false">
                <i class="fas fa-user-graduate"></i>
                <span>Students</span>
                <i class="fas fa-chevron-down arrow"></i>
            </a>
            <div class="collapse" id="studentsSubmenu">
                <ul class="submenu">
                    <li>
                        <a href="add-student.php" class="submenu-link">
                            <i class="fas fa-user-plus"></i> Add Student
                        </a>
                    </li>
                    <li>
                        <a href="all-students.php" class="submenu-link">
                            <i class="fas fa-users"></i> All Students
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Instructors Menu -->
        <li class="nav-item has-submenu">
            <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#instructorsSubmenu" aria-expanded="false">
                <i class="fas fa-chalkboard-teacher"></i>
                <span>Instructors</span>
                <i class="fas fa-chevron-down arrow"></i>
            </a>
            <div class="collapse" id="instructorsSubmenu">
                <ul class="submenu">
                    <li>
                        <a href="add-instructor.php" class="submenu-link">
                            <i class="fas fa-user-plus"></i> Add Instructor
                        </a>
                    </li>
                    <li>
                        <a href="all-instructors.php" class="submenu-link">
                            <i class="fas fa-users"></i> All Instructors
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Reports Menu -->
        <li class="nav-item has-submenu">
            <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#reportsSubmenu" aria-expanded="false">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
                <i class="fas fa-chevron-down arrow"></i>
            </a>
            <div class="collapse" id="reportsSubmenu">
                <ul class="submenu">
                    <li>
                        <a href="course-reports.php" class="submenu-link">
                            <i class="fas fa-chart-line"></i> Course Reports
                        </a>
                    </li>
                    <li>
                        <a href="student-reports.php" class="submenu-link">
                            <i class="fas fa-chart-pie"></i> Student Reports
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Settings -->
        <li class="nav-item">
            <a href="settings.php" class="nav-link <?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>

        <!-- Logout -->
        <li class="nav-item">
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </li>
    </ul>
</div>

<!-- Sidebar Toggle Button -->
<!-- <button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button> -->

