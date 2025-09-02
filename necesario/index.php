<?php 
    include_once('config.php');
    $error_message = '';

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM user WHERE username=? AND password=?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($result->num_rows > 0){
            header("Location: home.php");
            exit();
        }else{
            $error_message = "Invalid username or password!";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <div class="login-container">
        <div class="container-fluid h-100">
            <div class="row h-100">
                <!-- Left Side - School Info -->
                <div class="col-lg-6 d-none d-lg-flex school-info-section">
                    <div class="school-info-content">
                        <div class="school-logo-large">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h2 class="school-name">S.E.A.I.T</h2>
                        <p class="school-motto">"Excellence in Education, Character in Life"</p>
                        <div class="features">
                            <div class="feature-item">
                                <i class="fas fa-book-open"></i>
                                <span>Digital Learning Platform</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-users"></i>
                                <span>Student Community</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-chart-line"></i>
                                <span>Progress Tracking</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side - Login Form -->
                <div class="col-lg-6 col-12 login-form-section">
                    <div class="login-form-container">
                        <!-- Mobile Logo -->
                        <div class="mobile-logo d-lg-none">
                            <i class="fas fa-graduation-cap"></i>
                            <h4>Greenwood Academy</h4>
                        </div>
                        
                        <div class="login-form-wrapper">
                            <div class="form-header">
                                <h3 class="form-title">
                                    <i class="fas fa-user-graduate me-2"></i>
                                    Student Login
                                </h3>
                                <p class="form-subtitle">Access your student portal</p>
                            </div>
                <!----------ERROR MESSAGE----------------->
                    <?php if (!empty($error_message)): ?>
                        <div class="alert alert-error">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>
                            <!----------------------------FORM HERE------------------------------------------->
                            <form id="loginForm" class="login-form" method="post" action="index.php">
                                <div class="form-group mb-3">
                                    <label for="username" class="form-label">
                                        <i class="fas fa-user me-2"></i>Username
                                    </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="username" name="username" 
                                               placeholder="Enter your username" required>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <label for="password" class="form-label">
                                        <i class="fas fa-lock me-2"></i>Password
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password" 
                                               placeholder="Enter your password" required>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="rememberMe">
                                        <label class="form-check-label" for="rememberMe">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Sign In
                                </button>
                                
                                <div class="form-links">
                                    <a href="#" class="forgot-password">
                                        <i class="fas fa-question-circle me-1"></i>
                                        Forgot Password?
                                    </a>
                                </div>
                            </form>
                            
                            <div class="form-footer">
                                <p class="help-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Need help? Contact the school office
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    /* Root Variables */
:root {
    --primary-color: #2c5aa0;
    --secondary-color: #f8f9fa;
    --accent-color: #28a745;
    --text-dark: #333;
    --text-light: #666;
    --border-color: #e0e0e0;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gradient-school: linear-gradient(135deg, #2c5aa0 0%, #1e3c72 100%);
}

/* Global Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #0c8aff79, #000811ff);
    min-height: 100vh;
    overflow-x: hidden;
}
.container{
    width: 50%;
}

.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
}

/* School Info Section */
.school-info-section {
    background: var(--gradient-school);
    color: white;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}


.school-info-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.school-info-content {
    text-align: center;
    z-index: 2;
    position: relative;
    padding: 1rem;
}

.school-logo-large {
    font-size: 5rem;
    margin-bottom: 1rem;
    color: #ffd700;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.school-name {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
}

.school-motto {
    font-size: 1.1rem;
    font-style: italic;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.features {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
    opacity: 0.9;
}

.feature-item i {
    font-size: 1.2rem;
    color: #ffd700;
}

/* Login Form Section */
.login-form-section {
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem 1rem;
}

.login-form-container {
    width: 100%;
    max-width: 400px;
}

.mobile-logo {
    text-align: center;
    margin-bottom: 2rem;
    color: var(--primary-color);
}

.mobile-logo i {
    font-size: 3rem;
    margin-bottom: 0.5rem;
    display: block;
}

.mobile-logo h4 {
    font-weight: 600;
    margin: 0;
}

.login-form-wrapper {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border-color);
}

.form-header {
    text-align: center;
    margin-bottom: 2rem;
}

.form-title {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.form-subtitle {
    color: var(--text-light);
    margin: 0;
}

/* Form Styles */
.form-label {
    font-weight: 500;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.form-control {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 0.75rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    height: 50%;
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(44, 90, 160, 0.25);
}

.input-group-text {
    background: var(--secondary-color);
    border: 2px solid var(--border-color);
    border-right: none;
    color: var(--text-light);
}

.input-group:focus-within .input-group-text {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

#togglePassword {
    border: 2px solid var(--border-color);
    border-left: none;
}

.btn-login {
    background: var(--gradient-school);
    border: none;
    padding: 0.75rem 1.5rem;
    font-size: 1rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(44, 90, 160, 0.3);
}

.btn-login:active {
    transform: translateY(0);
}

.form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}

.forgot-password {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.forgot-password:hover {
    color: #1e3c72;
    text-decoration: underline;
}

.form-links {
    text-align: center;
}

.form-footer {
    text-align: center;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-color);
}

.help-text {
    color: var(--text-light);
    font-size: 0.9rem;
    margin: 0;
}

/* Loading Spinner */
.loading-spinner {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

 .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
/* Animations */
@keyframes fadeInUp {
    from {  
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.login-form-wrapper {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .login-form-section {
        background: var(--gradient-primary);
    }
    
    .login-form-wrapper {
        margin: 1rem 0;
    }
}

@media (max-width: 576px) {
    .login-form-wrapper {
        padding: 1.5rem;
        margin: 0.5rem;
    }
    
    .school-name {
        font-size: 2rem;
    }
    
    .mobile-logo i {
        font-size: 2.5rem;
    }
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #1e3c72;
}

/* Form Validation Styles */
.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control.is-valid {
    border-color: var(--accent-color);
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

/* SweetAlert2 Custom Styles */
.swal2-popup {
    border-radius: 15px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.swal2-title {
    color: var(--primary-color);
}

.swal2-confirm {
    background-color: var(--primary-color) !important;
    border-radius: 8px;
}

</style>

    <script src="script.js"></script>
</body>
</html>