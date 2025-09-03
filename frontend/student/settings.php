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
            padding-bottom: 60px; /* Space for fixed footer */
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
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
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
                            <p class="text-muted small">Manage your profile</p>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-success btn-sm" id="saveSettingsBtn" onclick="saveSettings()" disabled>
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Settings Content -->
            <div class="col-md-12">
                <!-- Profile Settings -->
                <div id="profile" class="settings-section active">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profile Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" id="newPassword">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirmPassword">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>

    <!-- Footer -->
    <?php include 'footer.php'?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Password matching logic
        function checkPasswords() {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const saveButton = document.getElementById('saveSettingsBtn');

            if (newPassword && confirmPassword && newPassword === confirmPassword) {
                saveButton.disabled = false;
            } else {
                saveButton.disabled = true;
            }
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
                    document.getElementById('newPassword').value = '';
                    document.getElementById('confirmPassword').value = '';
                    document.getElementById('saveSettingsBtn').disabled = true;
                }
            });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            console.log('Settings page loaded');
            document.getElementById('newPassword').addEventListener('input', checkPasswords);
            document.getElementById('confirmPassword').addEventListener('input', checkPasswords);
        });
    </script>
</body>
</html>