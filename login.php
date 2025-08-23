<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University Course Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/login-style.css" >
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
                        <form action="php/login-function.php" method="post" id="loginForm">

                            <!-- Email/Username Input -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="text" class="form-control" id="email" name="username" placeholder="Enter your username" required>
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

                            <!-- Forgot Password -->
                            <div class="row mb-3">
                                <div class="col-6">
                                    
                                </div>
                                <div class="col-6 text-end">
                                    <a href="#" class="forgot-password">Forgot Password?</a>
                                </div>
                            </div>

                            <!-- Login Button -->
                            <button type="submit" class="btn btn-primary btn-login w-100">
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

</body>
</html>