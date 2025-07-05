<?php
// Secure session settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
session_start();
session_regenerate_id(true); // Prevent session fixation

// Check if logout=true and destroy session
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    $_SESSION = [];
    session_unset();
    session_destroy();
    setcookie(session_name(), '', time() - 3600, '/'); // Delete session cookie

    header("Location: index.php");
    exit();
}

include './config.php'; // Database connection file

// Initialize login attempts counter
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}

$error = "";

// Brute-force protection
if ($_SESSION['login_attempts'] >= 10) {
    $error = "Too many failed attempts. Try again later.";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $gmail = trim($_POST['gmail']);
    $password = trim($_POST['password']);

    if (!empty($gmail) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE gmail_u = ?");
        $stmt->bind_param("s", $gmail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['gmail'] = $gmail;
                $_SESSION['last_activity'] = time();
                $_SESSION['login_attempts'] = 0;

                header("Location: dashboard.php");
                exit();
            } else {
                $_SESSION['login_attempts']++;
                $error = "Invalid password.";
            }
        } else {
            $_SESSION['login_attempts']++;
            $error = "Email not found.";
        }
        $stmt->close();
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel - Login</title>
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <section class="container">
        <div class="login-container">
            <div class="form-container">
                <h1 class="opacity">LOGIN</h1>
                <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
                <form action="" method="POST">
                    <input type="email" name="gmail" placeholder="EMAIL" required />
                    <input type="password" name="password" placeholder="PASSWORD" required />
                    <button type="submit" class="opacity">SUBMIT</button>
                </form>
                <div class="register-forget opacity">
                    <a href="forgot_password.php">FORGOT PASSWORD</a>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelector("form").addEventListener("submit", function(event) {
            const gmail = document.querySelector("input[name='gmail']").value.trim();
            const password = document.querySelector("input[name='password']").value.trim();

            if (gmail === "" || password === "") {
                event.preventDefault();
                alert("Please fill in all fields!");
            }
        });
    });
    </script>
    <script>
    document.getElementById('adminForm').addEventListener('submit', function(e) {
        const videoInput = document.getElementById('video');
        const imageInput = document.getElementById('image');

        const maxVideoSize = 100 * 1024 * 1024; // 100 MB
        const maxImageSize = 2 * 1024 * 1024; // 2 MB

        if (videoInput.files[0] && videoInput.files[0].size > maxVideoSize) {
            alert("Video file is too large! Max 100MB.");
            e.preventDefault();
            return;
        }

        if (imageInput.files[0] && imageInput.files[0].size > maxImageSize) {
            alert("Image file is too large! Max 2MB.");
            e.preventDefault();
            return;
        }
    });
    </script>
</body>

</html>