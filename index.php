<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University Course Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="frontend/css/login-style.css" >
</head>
<body>
    <div class="container-fluid">
        <div class="row login-container">
            <div class="col-12">
                <div class="login-card mx-auto">
                    <div class="login-header">
                        <i class="fas fa-graduation-cap fa-2x mb-3"></i>
                        <h2>Welcome</h2>
                        <p>University Course Management System</p>
                    </div>
                    
                    <div class="login-body">
                        
                        <form id="loginForm">
                            <!-- NIC Input -->
                                <div class="mb-3">
                                    <label for="nic" class="form-label">NIC</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input type="text" class="form-control" id="nic" name="nic" placeholder="Enter your NIC" required>
                                    </div>
                                </div>

                                <!-- Password Input -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Login Button -->
                                <button type="submit" class="btn btn-primary btn-login w-100 mb-2">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                            </form>

<!-- Alert for messages -->
<div id="loginAlert" class="alert d-none mt-3" role="alert"></div>


                       
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function() {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    // Show alert function (can be used later after PHP returns a message)
    function showAlert(message, type) {
        const alert = document.getElementById('loginAlert');
        alert.className = `alert alert-${type}`;
        alert.textContent = message;
        alert.classList.remove('d-none');
        
        if (type === 'success') {
            setTimeout(() => {
                alert.classList.add('d-none');
            }, 3000);
        }
    }
</script>

<script>
    document.getElementById("loginForm").addEventListener("submit", async function(event) {
    event.preventDefault();

    const nic = document.getElementById("nic").value;
    const password = document.getElementById("password").value;
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';

    try {
        const res = await fetch("api/auth/login.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nic, password })   
        });

        const data = await res.json();

        if (data.status === "success") {
            showAlert(data.message, "success");
            setTimeout(() => {
                if (data.role === "admin") {
                    window.location.href = "frontend/admin/index.php";
                } else if (data.role === "staff") {
                    window.location.href = "frontend/staff/index.php";
                } else if (data.role === "student") {
                    window.location.href = "frontend/student/index.php";
                }
            }, 2000);
        } else {
            showAlert(data.message, "danger");
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
        }
    } catch (error) {
        showAlert("Network error. Please try again.", "danger");
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Sign In';
    }
});
</script>



</body>
</html>