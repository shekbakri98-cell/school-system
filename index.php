<?php
session_start();

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
?>
<?php
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
        if ($conn->query("INSERT INTO students (daree, kutaa, roll_no, maqaa_guutuu, saala) VALUES ('$d', '$k', '$r', '$m', '$s')")) { header("Location: ?page=student_list"); exit(); }
    }
    // BARSIISAA GALMEESSU
    if (isset($_POST['submit_teacher'])) {
        $m = $conn->real_escape_string($_POST['maqaa_barsiisaa']); $g = $conn->real_escape_string($_POST['gosa_barnootaa']); $i = $conn->real_escape_string($_POST['id_nambarii']);
        if ($conn->query("INSERT INTO teachers (maqaa_barsiisaa, gosa_barnootaa, id_nambarii) VALUES ('$m', '$g', '$i')")) { header("Location: ?page=teacher_list"); exit(); }
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
        .alert-error { padding: 10px; background-color: #f8d7da; color: #721c24; border-radius: 4px; margin-bottom: 15px; }
        .alert-success { padding: 10px; background-color: #d4edda; color: #155724; border-radius: 4px; margin-bottom: 15px; }
        .login-box { max-width: 400px; margin: 100px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .login-box h2 { text-align: center; color: #1d8ecd; margin-bottom: 20px; }
        @media (max-width: 768px) { .main-container { flex-direction: column; } .sidebar { width: 100%; } .form-grid, .dashboard-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['user_id'])): ?>
    <div class="login-box">
        <h2>Seensa ICTVision System</h2>
        <?php if(!empty($msg)): ?><div class="alert-error"><?php echo $msg; ?></div><?php endif; ?>
        <form action="index.php" method="POST">
            <div class="form-group"><label class="form-label">Username:</label><input type="text" name="username" class="form-control" required placeholder="admin"></div>
            <div class="form-group"><label class="form-label">Password:</label><input type="password" name="password" class="form-control" required placeholder="******"></div>
            <button type="submit" name="submit_login" class="btn-submit" style="width:100%; float:none;">Seeni</button>
        </form>
    </div>
<?php else: ?>
    <div class="navbar-custom">
        <div style="font-size:18px; font-weight:bold;">ICTVision School System</div>
        <div><strong><?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo ucfirst($_SESSION['gosa_user']); ?>)</strong> | <a href="?action=logout" style="color:yellow;">Bahi</a></div>
    </div>
    <div class="main-container">
        <div class="sidebar">
            <a class="<?php echo ($page == 'dashboard')?'active':''; ?>" href="?page=dashboard">Dashboard</a>
            <?php if ($_SESSION['gosa_user'] == 'admin'): ?>
                <a class="<?php echo ($page == 'class')?'active':''; ?>" href="?page=class">Class</a>
                <a class="<?php echo ($page == 'section')?'active':''; ?>" href="?page=section">Section</a>
                <a class="<?php echo ($page == 'subject')?'active':''; ?>" href="?page=subject">Subject</a>
            <?php endif; ?>
            <?php if ($_SESSION['gosa_user'] == 'admin' || $_SESSION['gosa_user'] == 'barsiisaa'): ?>
                <a class="<?php echo ($page == 'student_form')?'active':''; ?>" href="?page=student_form">Student Form</a>
                <a class="<?php echo ($page == 'student_list')?'active':''; ?>" href="?page=student_list">Student List</a>
                <a class="<?php echo ($page == 'teacher_form')?'active':''; ?>" href="?page=teacher_form">Teacher Form</a>
                <a class="<?php echo ($page == 'teacher_list')?'active':''; ?>" href="?page=teacher_list">Teacher List</a>
                <a class="<?php echo ($page == 'attendance')?'active':''; ?>" href="?page=attendance">Attendance</a>
                <a class="<?php echo ($page == 'exams')?'active':''; ?>" href="?page=exams">Exams</a>
                <a class="<?php echo ($page == 'mark_manage')?'active':''; ?>" href="?page=mark_manage">Mark Manage</a>
            <?php endif; ?>
            <a class="<?php echo ($page == 'result')?'active':''; ?>" href="?page=result">Result</a>
            <?php if ($_SESSION['gosa_user'] == 'admin'): ?>
                <a class="<?php echo ($page == 'promotion')?'active':''; ?>" href="?page=promotion">Promotion</a>
                <a class="<?php echo ($page == 'voice_sms')?'active':''; ?>" href="?page=voice_sms">Voice / SMS</a>
                <a class="<?php echo ($page == 'settings')?'active':''; ?>" href="?page=settings">Settings</a>
            <?php endif; ?>
        </div>

        <div class="content-body">
            <?php if(!empty($msg)): ?><div class="alert-error"><?php echo $msg; ?></div><?php endif; ?>
            <?php if(!empty($success_msg)): ?><div class="alert-success"><?php echo $success_msg; ?></div><?php endif; ?>

            <?php if ($page == 'dashboard'): ?>
                <h3>Fuula Dashboard</h3>
                <div class="dashboard-grid">
                    <div class="card card-students"><h4>Baay'ina Barattootaa</h4><p><?php echo $t_st; ?></p></div>
                    <div class="card card-teachers"><h4>Baay'ina Barsiisotaa</h4><p><?php echo $t_tc; ?></p></div>
                </div>
            <?php elseif ($page == 'class' && $_SESSION['gosa_user'] == 'admin'): ?>
                <h3>Hoggansa Dareewwanii (Class)</h3>
                <form action="?page=class" method="POST" style="margin-bottom:30px;">
                    <input type="text" name="maqaa_daree" class="form-control" required placeholder="Class - 1"><br>
                    <button type="submit" name="submit_class" class="btn-submit">Daree Dabali</button>
                </form>
            <?php elseif ($page == 'section' && $_SESSION['gosa_user'] == 'admin'): ?>
                <h3>Hoggansa Kutaalee (Section)</h3>
                <form action="?page=section" method="POST">
                    <select name="daree_id" class="form-select"><?php $cl = $conn->query("SELECT * FROM classes"); while($c = $cl->fetch_assoc()): ?><option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['maqaa_daree']); ?></option><?php endwhile; ?></select><br>
                    <input type="text" name="maqaa_kutaa" class="form-control" required placeholder="Blue"><br>
                    <button type="submit" name="submit_section" class="btn-submit">Kutaa Dabali</button>
                </form>
            <?php elseif ($page == 'subject' && $_SESSION['gosa_user'] == 'admin'): ?>
                <h3>Hoggansa Gosoota Barnootaa</h3>
                <form action="?page=subject" method="POST">
                    <select name="daree_id" class="form-select"><?php $cl = $conn->query("SELECT * FROM classes"); while($c = $cl->fetch_assoc()): ?><option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['maqaa_daree']); ?></option><?php endwhile; ?></select><br>
                    <input type="text" name="maqaa_subject" class="form-control" required placeholder="Afaan Oromoo"><br>
                    <button type="submit" name="submit_subject" class="btn-submit">Subject Dabali</button>
                </form>
            <?php elseif ($page == 'student_form' && ($_SESSION['gosa_user'] == 'admin' || $_SESSION['gosa_user'] == 'barsiisaa')): ?>
                <h3>Unka Galmeessa Barataa</h3>
                <form action="?page=student_form" method="POST">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Daree:</label><select name="daree" class="form-select"><?php $cl = $conn->query("SELECT * FROM classes"); while($c = $cl->fetch_assoc()): ?><option value="<?php echo htmlspecialchars($c['maqaa_daree']); ?>"><?php echo htmlspecialchars($c['maqaa_daree']); ?></option><?php endwhile; ?></select></div>
                        <div class="form-group"><label class="form-label">Kutaa:</label><input type="text" name="kutaa" class="form-control" placeholder="Blue"></div>
                        <div class="form-group"><label class="form-label">Roll No:</label><input type="text" name="roll_no" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Maqaa Guutuu:</label><input type="text" name="maqaa_guutuu" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Saala:</label><select name="saala" class="form-select"><option value="Korma">Korma</option><option value="Dhalaa">Dhalaa</option></select></div>
                    </div>
                    <button type="submit" name="submit_student" class="btn-submit">Galmeessi</button>
                </form>
            <?php elseif ($page == 'student_list'): ?>
                <h3>Tarree Barattootaa</h3>
                <table class="data-table">
                    <thead><tr><th>Roll No</th><th>Maqaa Guutuu</th><th>Daree</th><th>Kutaa</th></tr></thead>
                    <tbody>
                        <?php $res = $conn->query("SELECT * FROM students"); while($row = $res->fetch_assoc()): ?>
                            <tr><td><?php echo $row['roll_no']; ?></td><td><?php echo $row['maqaa_guutuu']; ?></td><td><?php echo $row['daree']; ?></td><td><?php echo $row['kutaa']; ?></td></tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php elseif ($page == 'teacher_form' && $_SESSION['gosa_user'] == 'admin'): ?>
                <h3>Unka Galmeessa Barsiisaa</h3>
                <form action="?page=teacher_form" method="POST">
                    <input type="text" name="maqaa_barsiisaa" class="form-control" required placeholder="Maqaa"><br>
                    <input type="text" name="gosa_barnootaa" class="form-control" required placeholder="Subject"><br>
                    <input type="text" name="id_nambarii" class="form-control" required placeholder="ID"><br>
                    <button type="submit" name="submit_teacher" class="btn-submit">Galmeessi</button>
                </form>
            <?php elseif ($page == 'teacher_list'): ?>
                <h3>Tarree Barsiisotaa</h3>
                <table class="data-table">
                    <thead><tr><th>ID</th><th>Maqaa</th><th>Gosa Barnootaa</th></tr></thead>
                    <tbody>
                        <?php $res = $conn->query("SELECT * FROM teachers"); while($row = $res->fetch_assoc()): ?>
                            <tr><td><?php echo $row['id_nambarii']; ?></td><td><?php echo $row['maqaa_barsiisaa']; ?></td><td><?php echo $row['gosa_barnootaa']; ?></td></tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php elseif ($page == 'attendance' && ($_SESSION['gosa_user'] == 'admin' || $_SESSION['gosa_user'] == 'barsiisaa'])): ?>
                <h3>Hordoffii Hirmaannaa</h3>
                <form action="?page=attendance" method="POST">
                    <input type="date" name="guyyaa" class="form-control" value="<?php echo date('Y-m-d'); ?>" required><br>
                    <table class="data-table">
                        <?php $st = $conn->query("SELECT * FROM students"); while($row = $st->fetch_assoc()): ?>
                            <tr><td><?php echo $row['maqaa_guutuu']; ?></td><td><select name="status[<?php echo $row['id']; ?>]" class="form-select"><option value="Argame">Argame</option><option value="Hafe">Hafe</option></select></td></tr>
                        <?php endwhile; ?>
                    </table><br><button type="submit" name="submit_attendance" class="btn-submit">Galmeessi</button>
                </form>
            <?php elseif ($page == 'exams' && ($_SESSION['gosa_user'] == 'admin' || $_SESSION['gosa_user'] == 'barsiisaa'])): ?>
                <h3>Hoggansa Qorannoof Semisteeraa</h3>
                <form action="?page=exams" method="POST"><input type="text" name="maqaa_qorannoo" class="form-control" required placeholder="Mid Exam"><br><input type="text" name="semisteera" class="form-control" value="Semester 1"><br><input type="text" name="bara_barnootaa" class="form-control" value="2018 E.C."><br><button type="submit" name="submit_exam" class="btn-submit">Qorannoo Dabali</button></form>
            <?php elseif ($page == 'mark_manage' && ($_SESSION['gosa_user'] == 'admin' || $_SESSION['gosa_user'] == 'barsiisaa'])): ?>
                <h3>Galmeessa Qabxii</h3>
                <form action="?page=mark_manage" method="POST">
                    <select name="qorannoo_id" class="form-select"><?php $ex = $conn->query("SELECT * FROM exams"); while($e = $ex->fetch_assoc()): ?><option value="<?php echo $e['id']; ?>"><?php echo htmlspecialchars($e['maqaa_qorannoo']); ?></option><?php endwhile; ?></select><br>
                    <select name="subject_id" class="form-select"><?php $sub = $conn->query("SELECT * FROM subjects"); while($s = $sub->fetch_assoc()): ?><option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['maqaa_gosa_barnootaa']); ?></option><?php endwhile; ?></select><br>
                    <table class="data-table">
                        <?php $st = $conn->query("SELECT * FROM students"); while($row = $st->fetch_assoc()): ?>
                            <tr><td><?php echo $row['maqaa_guutuu']; ?></td><td><input type="number" step="0.01" name="qabxii[<?php echo $row['id']; ?>]" class="form-control"></td></tr>
                        <?php endwhile; ?>
                    </table><br><button type="submit" name="submit_marks" class="btn-submit">Qabxii Kuusi</button>
                </form>
            <?php elseif ($page == 'result'): ?>
                <h3>Bu'aa fi Sadarkaa Barattootaa</h3>
                <table class="data-table">
                    <?php $res = $conn->query("SELECT s.roll_no, s.maqaa_guutuu, SUM(m.qabxii) as total_mark FROM students s LEFT JOIN marks m ON s.id = m.barataa_id GROUP BY s.id ORDER BY total_mark DESC"); while($row = $res->fetch_assoc()): ?>
                        <tr><td><?php echo $row['roll_no']; ?></td><td><?php echo htmlspecialchars($row['maqaa_guutuu']); ?></td><td><?php echo $row['total_mark'] ?? '0'; ?></td></tr>
                    <?php endwhile; ?>
                </table>
            <?php elseif ($page == 'promotion' && $_SESSION['gosa_user'] == 'admin'): ?>
                <h3>Kutaa Dabarsuu</h3><button class="btn-submit">Barattoota Hunda Kutaa Dabarsi</button>
            <?php elseif ($page == 'voice_sms' && $_SESSION['gosa_user'] == 'admin'): ?>
                <h3>Ergaa SMS Ergi</h3><textarea class="form-control" rows="4" placeholder="Ergaa..."></textarea><br><button class="btn-submit">Ergi</button>
            <?php elseif ($page == 'settings' && $_SESSION['gosa_user'] == 'admin'): ?>
                <h3>Uumama Hojjetaa Seensaa</h3>
                <form action="?page=settings" method="POST">
                    <input type="text" name="reg_username" class="form-control" required placeholder="Username"><br>
                    <input type="password" name="reg_password" class="form-control" required placeholder="******"><br>
                    <select name="reg_gosa_user" class="form-select"><option value="barsiisaa">Barsiisaa</option><option value="admin">Admin</option></select><br>
                    <button type="submit" name="submit_register_user" class="btn-submit">User Uumi</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
</body>
</html>
