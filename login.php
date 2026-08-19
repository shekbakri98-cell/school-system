<?php
include 'db_connect.php';
session_start();

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // SIRREEFFAMA: Iccitii bifa plain text (admin123) fi bifa hash ta'e lachuu ni mirkaneessa
        if ($password === $admin['password'] || password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Iccitii (Password) dogoggora!";
        }
    } else {
        $error = "Maqaan seensaa (Username) hin argamne!";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="om">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICTVision - Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f6f9; margin:0; }
        .login-container { background: #fff; width: 100%; max-width: 400px; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 5px solid #258cd1; }
        .error-msg { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align: center; margin-bottom: 20px; color: #333;">ICTVision Admin Login</h2>
        <?php if(isset($error)) { echo "<div class='error-msg'>$error</div>"; } ?>
        <form action="" method="POST">
            <div class="input-group" style="margin-bottom: 20px;">
                <label>Maqaa Seensaa (Username):</label>
                <input type="text" name="username" placeholder="admin" required>
            </div>
            <div class="input-group" style="margin-bottom: 25px;">
                <label>Iccitii (Password):</label>
                <input type="password" name="password" placeholder="admin123" required>
            </div>
            <button type="submit" class="submit-btn">Seeni (Login)</button>
        </form>
    </div>
</body>
</html>