<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Student Management System</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <link rel="stylesheet" type="text/css" href="css/style.css">

    <!-- SweetAlert for notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            padding: 20px;
        }
        .settings-nav {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .settings-nav .nav-link {
            color: #495057;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .settings-nav .nav-link:hover {
            background-color: #e9ecef;
        }
        .settings-nav .nav-link.active {
            background-color: #007bff;
            color: white;
        }
        .settings-section {
            display: none;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .settings-section.active {
            display: block;
        }
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #f8f9fa;
            font-weight: 500;
        }
        .profile-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
        }
        .backup-item {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
        }
    </style>
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
                            <h1 class="h3 mb-1"><i class="fas fa-cog me-2"></i>Settings</h1>
                            <p class="text-muted small">Manage your system preferences</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btn-sm" onclick="saveSettings()">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Settings Navigation -->
            <div class="col-md-3">
                <div class="settings-nav">
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="#" onclick="showSection('general')">
                            <i class="fas fa-cog me-2"></i>General
                        </a>
                        <a class="nav-link" href="#" onclick="showSection('profile')">
                            <i class="fas fa-user me-2"></i>Profile
                        </a>
                        <a class="nav-link" href="#" onclick="showSection('backup')">
                            <i class="fas fa-database me-2"></i>Backup
                        </a>
                    </nav>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="col-md-9">
                <!-- General Settings -->
                <div id="general" class="settings-section active">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>General Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">System Name</label>
                                <input type="text" class="form-control" value="Student Management System" id="systemName">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Institution Name</label>
                                <input type="text" class="form-control" value="ABC University" id="institutionName">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Theme Color</label>
                                <input type="color" class="form-control" style="width: 60px; height: 40px;" value="#007bff" id="themeColor">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Settings -->
                <div id="profile" class="settings-section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profile Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <img src="https://via.placeholder.com/60x60/007bff/ffffff?text=User" alt="Profile" class="profile-avatar mb-3">
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-camera me-1"></i>Change Photo
                                    </button>
                                </div>
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" value="Admin User" id="userName">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="admin@university.edu" id="userEmail">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="newPassword">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backup Settings -->
                <div id="backup" class="settings-section">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-database me-2"></i>Backup Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Create Backup</h6>
                                    <button class="btn btn-primary w-100 mb-3" onclick="createBackup()">
                                        <i class="fas fa-download me-2"></i>Create Backup
                                    </button>
                                    <div class="mb-3">
                                        <label class="form-label">Restore Backup</label>
                                        <input type="file" class="form-control" accept=".zip,.sql" id="restoreFile">
                                    </div>
                                    <button class="btn btn-warning w-100" onclick="restoreBackup()">
                                        <i class="fas fa-upload me-2"></i>Restore Backup
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="mb-3">Recent Backups</h6>
                                    <div class="backup-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>Backup - Sep 1, 2025</strong>
                                                <div class="small text-muted">Size: 45 MB</div>
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteBackup('backup_2025_09_01.zip')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
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
        // Show section
        function showSection(sectionId) {
            document.querySelectorAll('.settings-section').forEach(section => {
                section.classList.remove('active');
            });
            document.querySelectorAll('.settings-nav .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            document.getElementById(sectionId).classList.add('active');
            event.target.classList.add('active');
        }

        // Save settings
        function saveSettings() {
            Swal.fire({
                title: 'Save Settings',
                text: 'Are you sure you want to save changes?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Save',
                confirmButtonColor: '#28a745'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Saved!',
                        text: 'Settings have been saved.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            });
        }

        // Backup functions
        function createBackup() {
            Swal.fire({
                title: 'Creating Backup',
                text: 'Backup is being created...',
                icon: 'info',
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                Swal.fire('Backup Created!', 'Backup was created successfully.', 'success');
            });
        }

        function restoreBackup() {
            if (!document.getElementById('restoreFile').files.length) {
                Swal.fire('Error', 'Please select a backup file.', 'error');
                return;
            }
            Swal.fire({
                title: 'Restore Backup',
                text: 'This will overwrite current data. Proceed?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Restore',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Restored!', 'Backup restored successfully.', 'success');
                }
            });
        }

        function deleteBackup(filename) {
            Swal.fire({
                title: 'Delete Backup',
                text: `Delete ${filename}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                confirmButtonColor: '#dc3545'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Deleted!', 'Backup deleted successfully.', 'success');
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Settings page loaded');
        });
    </script>
</body>
</html>