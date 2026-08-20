<?php
session_start();
// Koodii kanaan dura dabalte sana haqiirraatii kan qofa kaa'i:
$test_user = "admin";
$test_pass = password_hash("admin123", PASSWORD_BCRYPT);
$conn->query("INSERT INTO users (username, password, gosa_user) VALUES ('$test_user', '$test_pass', 'admin') ON DUPLICATE KEY UPDATE password='$test_pass'");

// 1. HIDHAMSA KUUSAA ODEEFFANNOO (DATABASE CONNECTION)
$conn = new mysqli("mysql-anewar.alwaysdata.net", "anewar_admin", "015661Emran@", "anewar_school_db");
if ($conn->connect_error) { 
    die("Kuusaa odeeffannoo waliin walitti hidhuun hin danda'amne: " . $conn->connect_error); 
}

$msg = ""; 
$success_msg = "";

// LOGOUT LOGIC
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: index.php");
    exit();
}

// LOGIN LOGIC
if (isset($_POST['submit_login'])) {
    $uname = $conn->real_escape_string($_POST['username']);
    $pword = $_POST['password'];
    
    $res = $conn->query("SELECT * FROM users WHERE username='$uname'");
    if ($res && $res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if (password_verify($pword, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['gosa_user'] = $user['gosa_user'];
            header("Location: index.php?page=dashboard");
            exit();
        } else { $msg = "Jechi iccitii (Password) sirrii miti!"; }
    } else { $msg = "Maqaan seensaa (Username) kun hin jiru!"; }
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Baay'ina barattootaa fi barsiisotaa lakkaa'uuf
$t_st = $conn->query("SELECT COUNT(*) as t FROM students")->fetch_assoc()['t'] ?? 0;
$t_tc = $conn->query("SELECT COUNT(*) as t FROM teachers")->fetch_assoc()['t'] ?? 0;

if (isset($_SESSION['user_id'])) {
    // USER REGISTER LOGIC
    if (isset($_POST['submit_register_user']) && $_SESSION['gosa_user'] == 'admin') {
        $u_name = $conn->real_escape_string($_POST['reg_username']);
        $u_type = $conn->real_escape_string($_POST['reg_gosa_user']);
        $u_pass = password_hash($_POST['reg_password'], PASSWORD_BCRYPT);
        if ($conn->query("INSERT INTO users (username, password, gosa_user) VALUES ('$u_name', '$u_pass', '$u_type')")) {
            $success_msg = "User haaraan milkiin dabalameera!";
        } else { $msg = "Dogoggora: Username kun duraan jira!"; }
    }
    // DARE DADDABALU
    if (isset($_POST['submit_class'])) {
        $maqaa = $conn->real_escape_string($_POST['maqaa_daree']);
        if ($conn->query("INSERT INTO classes (maqaa_daree) VALUES ('$maqaa')")) { $success_msg = "Daree dabalameera!"; }
    }
    // SECTION DADDABALU
    if (isset($_POST['submit_section'])) {
        $d_id = (int)$_POST['daree_id']; $m_kutaa = $conn->real_escape_string($_POST['maqaa_kutaa']);
        if ($conn->query("INSERT INTO sections (daree_id, maqaa_kutaa) VALUES ($d_id, '$m_kutaa')")) { $success_msg = "Kutaa dabalameera!"; }
    }
    // SUBJECT DADDABALU
    if (isset($_POST['submit_subject'])) {
        $d_id = (int)$_POST['daree_id']; $sub_name = $conn->real_escape_string($_POST['maqaa_subject']);
        if ($conn->query("INSERT INTO subjects (daree_id, maqaa_gosa_barnootaa) VALUES ($d_id, '$sub_name')")) { $success_msg = "Subject dabalameera!"; }
    }
    // ATTENDANCE GALMEESSU
    if (isset($_POST['submit_attendance'])) {
        $guyyaa = $conn->real_escape_string($_POST['guyyaa']);
        if (!empty($_POST['status'])) {
            foreach ($_POST['status'] as $b_id => $status) {
                $b_id = (int)$b_id; $status = $conn->real_escape_string($status);
                $conn->query("INSERT INTO attendance (barataa_id, haala_hirmaannaa, guyyaa) VALUES ($b_id, '$status', '$guyyaa') ON DUPLICATE KEY UPDATE haala_hirmaannaa='$status'");
            }
            $success_msg = "Hirmaannaan galmeeffameera!";
        }
    }
    // EXAM DADDABALU
    if (isset($_POST['submit_exam'])) {
        $m_qorannoo = $conn->real_escape_string($_POST['maqaa_qorannoo']); $semisteera = $conn->real_escape_string($_POST['semisteera']); $bara = $conn->real_escape_string($_POST['bara_barnootaa']);
        if ($conn->query("INSERT INTO exams (maqaa_qorannoo, semisteera, bara_barnootaa) VALUES ('$m_qorannoo', '$semisteera', '$bara')")) { $success_msg = "Qorannoon dabalameera!"; }
    }
    // QABXII GALMEESSU
    if (isset($_POST['submit_marks'])) {
        $q_id = (int)$_POST['qorannoo_id']; $s_id = (int)$_POST['subject_id'];
        if (!empty($_POST['qabxii'])) {
            foreach ($_POST['qabxii'] as $b_id => $qabxii_val) {
                $b_id = (int)$b_id; $qabxii_val = (float)$qabxii_val;
                $conn->query("INSERT INTO marks (qorannoo_id, barataa_id, subject_id, qabxii) VALUES ($q_id, $b_id, $s_id, $qabxii_val) ON DUPLICATE KEY UPDATE qabxii=$qabxii_val");
            }
            $success_msg = "Qabxiin barattootaa kuusameera!";
        }
    }
    // BARATAA GALMEESSU
    if (isset($_POST['submit_student'])) {
        $d = $conn->real_escape_string($_POST['daree']); $k = $conn->real_escape_string($_POST['kutaa']); $r = $conn->real_escape_string($_POST['roll_no']); $m = $conn->real_escape_string($_POST['maqaa_guutuu']); $s = $conn->real_escape_string($_POST['saala']);
        if ($conn->query("INSERT INTO students (daree, kutaa, roll_no, maqaa_guutuu, saala) VALUES ('$d', '$k', '$r', '$m', '$s')")) { header("Location: ?page=students"); exit(); }
    }
    // BARSIISAA GALMEESSU
    if (isset($_POST['submit_teacher'])) {
        $m = $conn->real_escape_string($_POST['maqaa_barsiisaa']); $g = $conn->real_escape_string($_POST['gosa_barnootaa']); $i = $conn->real_escape_string($_POST['id_nambarii']);
        if ($conn->query("INSERT INTO teachers (maqaa_barsiisaa, gosa_barnootaa, id_nambarii) VALUES ('$m', '$g', '$i')")) { header("Location: ?page=dashboard"); exit(); }
    }
}
?>
<!DOCTYPE html>
<html lang="om">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>ICTVision School System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f4f6f9; font-family: sans-serif; color: #333; }
        .navbar-custom { background-color: #1d8ecd; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; }
        .navbar-custom a { color: white; text-decoration: none; font-weight: bold; }
        .main-container { display: flex; margin: 20px; gap: 20px; }
        .sidebar { width: 220px; background-color: white; border-radius: 6px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .sidebar a { color: #555; padding: 10px; display: block; text-decoration: none; border-radius: 4px; margin-bottom: 5px; font-size: 14px; }
        .sidebar a:hover { background-color: #f0f7fc; color: #1d8ecd; }
        .sidebar a.active { background-color: #1d8ecd; color: white; font-weight: bold; }
        .content-body { flex-grow: 1; background-color: white; border-radius: 6px; padding: 30px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .content-body h3 { font-size: 20px; margin-bottom: 20px; border-bottom: 2px solid #f4f6f9; padding-bottom: 10px; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px; }
        .form-group { display: flex; flex-direction: column; margin-bottom: 10px; }
        .form-label { font-size: 13px; margin-bottom: 5px; font-weight: 500; }
        .form-control, .form-select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .btn-submit { background-color: #1d8ecd; color: white; border: none; padding: 10px 25px; border-radius: 4px; font-weight: bold; cursor: pointer; float: right; margin-top: 15px; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px; }
        .data-table th, .data-table td { padding: 10px; border-bottom: 1px solid #eef2f5; text-align: left; }
        .data-table th { background-color: #f8f9fa; color: #555; }
        .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px; }
        .card { padding: 25px; border-radius: 6px; color: white; font-weight: bold; }
        .card-students { background-color: #28a745; }
        .card-teachers { background-color: #fd7e14; }
        .card p { font-size: 30px; }
        .alert-error { padding: 12px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { padding: 12px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 15px; }
        .login-box { max-width: 400px; margin: 80px auto; padding: 30px; background: white; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .login-box h2 { text-align: center; margin-bottom: 20px; color: #1d8ecd; }
    </style>
</head>
<body>

    <div class="navbar-custom">
        <a href="index.php">ICTVision School System</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="index.php?action=logout">Ba'i (Logout)</a>
        <?php endif; ?>
    </div>

    <div class="main-container">
        <?php if(!isset($_SESSION['user_id'])): ?>
            <!-- SEENSA (LOGIN FORM) -->
            <div class="login-box">
                <h2>Seensa Hojjettootaa</h2>
                <?php if($msg != ""): ?> <div class="alert-error"><?php echo $msg; ?></div> <?php endif; ?>
                <form action="index.php" method="POST">
                    <div class="form-group">
                        <label class="form-label">Maqaa Seensaa (Username)</label>
                        <input type="text" name="username" class="form-control" required placeholder="Username galchi">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jecha Iccitii (Password)</label>
                        <input type="password" name="password" class="form-control" required placeholder="Password galchi">
                    </div>
                    <button type="submit" name="submit_login" class="btn-submit" style="float:none; width:100%;">Seeni</button>
                </form>
            </div>
        <?php else: ?>
            <!-- SIDEBAR BAR -->
            <div class="sidebar">
                <a href="?page=dashboard" class="<?php echo $page=='dashboard'?'active':''; ?>">Dashboard</a>
                <a href="?page=students" class="<?php echo $page=='students'?'active':''; ?>">Bu'aa Barattootaa</a>
                <?php if($_SESSION['gosa_user'] == 'admin'): ?>
                    <a href="?page=settings" class="<?php echo $page=='settings'?'active':''; ?>">Galleewwan Seeraa</a>
                <?php endif; ?>
            </div>
            
            <!-- MAIN CONTENT BODY -->
            <div class="content-body">
                <?php if($msg != ""): ?> <div class="alert-error"><?php echo $msg; ?></div> <?php endif; ?>
                <?php if($success_msg != ""): ?> <div class="alert-success"><?php echo $success_msg; ?></div> <?php endif; ?>
                
                <?php if($page == 'dashboard'): ?>
                    <h3>Hanaqa Dashboard</h3>
                    <div class="dashboard-grid">
                        <div class="card card-students">
                            <label>Baay'ina Barattootaa</label>
                            <p><?php echo $t_st; ?></p>
                        </div>
                        <div class="card card-teachers">
                            <label>Baay'ina Barsiisotaa</label>
                            <p><?php echo $t_tc; ?></p>
                        </div>
                    </div>
                
                <?php elseif($page == 'students'): ?>
                    <h3>Bu'aa fi Sadarkaa Barattootaa</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Roll No</th>
                                <th>Maqaa Guutuu</th>
                                <th>Qabxii Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res = $conn->query("SELECT s.roll_no, s.maqaa_guutuu, SUM(m.qabxii) as total_mark FROM students s LEFT JOIN marks m ON s.id = m.barataa_id GROUP BY s.id");
                            if ($res && $res->num_rows > 0):
                                while($row = $res->fetch_assoc()): 
                            ?>
                                <tr>
                                    <td><?php echo $row['roll_no']; ?></td>
                                    <td><?php echo htmlspecialchars($row['maqaa_guutuu']); ?></td>
                                    <td><?php echo $row['total_mark'] ?? 0; ?></td>
                                </tr>
                            <?php 
                                endwhile; 
                            endif;
                            ?>
                        </tbody>
                    </table>

                <?php elseif ($page == 'settings' && $_SESSION['gosa_user'] == 'admin'): ?>
                    <h3>Uumama Hojjetaa Seensaa</h3>
                    <form action="?page=settings" method="POST">
                        <input type="text" name="reg_username" class="form-control" required placeholder="Username haaraa"><br>
                        <input type="password" name="reg_password" class="form-control" required placeholder="Password haaraa"><br>
                        <select name="reg_gosa_user" class="form-select">
                            <option value="barsiisaa">Barsiisaa</option>
                            <option value="admin">Admin</option>
                        </select><br>
                        <button type="submit" name="submit_register_user" class="btn-submit">User Uumi</button>
                    </form>
                <?php endif; ?>
            </div> 
        <?php endif; ?>
    </div>

</body>
</html>
