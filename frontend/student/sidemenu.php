<?php
session_start();

// Validate session and role
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    // For REST API, return a JSON response with 401 status
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized: Admin access required']);
        exit();
    }
    // For non-API requests, redirect
    header('Location: ../../index.php?error=notLoggedIn');
    exit();
}

// Get current page for highlighting (handle query parameters)
$current_page = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Define menu structure (could be fetched from a REST API endpoint)
$menu_items = [
    'dashboard' => ['url' => 'dashboard.php', 'icon' => 'fas fa-tachometer-alt', 'label' => 'Dashboard'],
    'courses' => [
        'icon' => 'fas fa-book',
        'label' => 'Courses',
        'submenu' => [
            ['url' => 'all-courses.php', 'icon' => 'fas fa-list', 'label' => 'All Courses'],
            ['url' => 'enrolled-courses.php', 'icon' => 'fas fa-list', 'label' => 'Enrolled Courses'],
        ],
    ],
    
    
    'settings' => ['url' => 'settings.php', 'icon' => 'fas fa-cog', 'label' => 'Settings'],
    'logout' => ['url' => '../../api/logout.php', 'icon' => 'fas fa-sign-out-alt', 'label' => 'Logout'],
];

// Function to check if a menu item or its submenu is active
function is_menu_active($menu_item, $current_page) {
    if (isset($menu_item['url']) && $menu_item['url'] === $current_page) {
        return true;
    }
    if (isset($menu_item['submenu'])) {
        foreach ($menu_item['submenu'] as $submenu_item) {
            if ($submenu_item['url'] === $current_page) {
                return true;
            }
        }
    }
    return false;
}
?>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-graduation-cap"></i> Course Manager</h4>
    </div>
    
    <ul class="sidebar-nav">
        <?php foreach ($menu_items as $key => $item): ?>
            <li class="nav-item <?php echo isset($item['submenu']) ? 'has-submenu' : ''; ?>">
                <a href="<?php echo isset($item['url']) ? $item['url'] : '#'; ?>" 
                   class="nav-link <?php echo is_menu_active($item, $current_page) ? 'active' : ''; ?>" 
                   <?php if (isset($item['submenu'])): ?>
                       data-bs-toggle="collapse" 
                       data-bs-target="#<?php echo $key; ?>Submenu"
                       aria-expanded="<?php echo is_menu_active($item, $current_page) ? 'true' : 'false'; ?>"
                   <?php endif; ?>>
                    <i class="<?php echo $item['icon']; ?>"></i>
                    <span><?php echo $item['label']; ?></span>
                    <?php if (isset($item['submenu'])): ?>
                        <i class="fas fa-chevron-down arrow"></i>
                    <?php endif; ?>
                </a>
                <?php if (isset($item['submenu'])): ?>
                    <div class="collapse <?php echo is_menu_active($item, $current_page) ? 'show' : ''; ?>" 
                         id="<?php echo $key; ?>Submenu">
                        <ul class="submenu">
                            <?php foreach ($item['submenu'] as $sub_item): ?>
                                <li>
                                    <a href="<?php echo $sub_item['url']; ?>" 
                                       class="submenu-link <?php echo $sub_item['url'] === $current_page ? 'active' : ''; ?>">
                                        <i class="<?php echo $sub_item['icon']; ?>"></i> 
                                        <?php echo $sub_item['label']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Sidebar Toggle Button -->
<!-- <button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button> -->