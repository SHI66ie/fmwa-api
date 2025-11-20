<?php
require_once '../config.php';
require_once 'auth.php';

$auth = new Auth($pdo);

// If already logged in, redirect to dashboard
if ($auth->isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_POST) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($auth->login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FMWA Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            width: 100%;
            overflow-x: hidden;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 15px;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
        }
        
        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }
        
        .login-right {
            padding: 60px 40px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 2rem;
        }
        
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
            color: white;
        }
        
        .input-group {
            position: relative;
            margin-bottom: 25px;
        }
        
        .input-group i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 10;
        }
        
        .input-group .form-control {
            padding-left: 55px;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .welcome-text {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .welcome-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
        }
        
        @media (max-width: 991px) {
            .login-left {
                display: none !important;
            }
        }
        
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }
            
            .login-right {
                padding: 40px 25px;
            }
            
            .welcome-text {
                font-size: 1.8rem;
            }
        }
        
        @media (max-width: 576px) {
            .login-right {
                padding: 30px 20px;
            }
            
            .welcome-text {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="row g-0">
                        <!-- Left Side - Welcome -->
                        <div class="col-lg-6 d-none d-lg-block">
                            <div class="login-left">
                                <div class="logo">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h1 class="welcome-text">Welcome Back!</h1>
                                <p class="welcome-subtitle">
                                    Federal Ministry of Women Affairs<br>
                                    Admin Dashboard Access Portal
                                </p>
                                <div class="mt-4">
                                    <i class="fas fa-lock fa-2x mb-3"></i>
                                    <p>Secure authentication system protecting your administrative access</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Side - Login Form -->
                        <div class="col-lg-6">
                            <div class="login-right">
                                <div class="text-center mb-4 d-lg-none">
                                    <h2 class="text-primary">FMWA Admin</h2>
                                </div>
                                
                                <h3 class="mb-4 text-center d-none d-lg-block">Admin Login</h3>
                                
                                <?php if ($error): ?>
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <?php echo htmlspecialchars($error); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <form method="POST" action="">
                                    <div class="input-group">
                                        <i class="fas fa-user"></i>
                                        <input type="text" 
                                               class="form-control" 
                                               name="username" 
                                               placeholder="Username" 
                                               required 
                                               autocomplete="username">
                                    </div>
                                    
                                    <div class="input-group">
                                        <i class="fas fa-lock"></i>
                                        <input type="password" 
                                               class="form-control" 
                                               name="password" 
                                               placeholder="Password" 
                                               required 
                                               autocomplete="current-password">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-login">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Sign In
                                    </button>
                                </form>
                                
                                <div class="text-center mt-4">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Contact IT support if you need assistance
                                    </small>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <a href="../index.php" class="text-decoration-none">
                                        <i class="fas fa-arrow-left me-1"></i>
                                        Back to Website
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Focus on username field when page loads
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="username"]').focus();
        });
        
        // Add loading state to form submission
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.querySelector('.btn-login');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
            btn.disabled = true;
        });
    </script>
</body>
</html>
