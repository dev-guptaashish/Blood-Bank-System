<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-image: url('./login.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background-image: url('./1l.jpeg');
            background-size: cover;
            background-position: center;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 500px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
            color: black;
        }

        .login-header h2 {
            font-size: 28px;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
        }

        .login-header p {
            opacity: 0.9;
            font-size: 14px;
            color: black;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: black;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
            transform: translateY(-2px);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .error-msg {
            background: rgba(244, 67, 54, 0.2);
            border: 2px solid #f44336;
            color: #d32f2f;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        .success-msg {
            background: rgba(76, 175, 80, 0.2);
            border: 2px solid #4caf50;
            color: #2e7d32;
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: bold;
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
<?php
require_once 'config.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: home_full.php');
        exit;
    } else {
        $message = 'Invalid username or password!';
    }
}
?>

    <div class="login-container">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p>Please sign in to your account</p>
        </div>
        <?php if ($message): ?>
            <div class="error-msg"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-btn">Sign In</button>
        </form>
        
    </div>
</body>
</html>
