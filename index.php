<?php
// 1. HIDHAMSA KUUSAA ODEEFFANNOO (DATABASE CONNECTION)
$conn = new mysqli("mysql-anewar.alwaysdata.net", "anewar_admin", "015661Emran@", "anewar_school_db");
if ($conn->connect_error) { 
    die("Kuusaa odeeffannoo waliin walitti hidhuun hin danda'amne: " . $conn->connect_error); 
}

$msg = ""; 
$success_msg = "";
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Baay'ina barattootaa fi barsiisotaa lakkaa'uuf
$t_st = $conn->query("SELECT COUNT(*) as t FROM students")->fetch_assoc()['t'] ?? 0;
$t_tc = $conn->query("SELECT COUNT(*) as t FROM teachers")->fetch_assoc()['t'] ?? 0;

// ==========================================
// DATA POST HANDLING (GALMEESSA ADDA ADDAA)
// ==========================================

// A. Class Dabaluu
if (isset($_POST['submit_class'])) {
    $maqaa = $conn->real_escape_string($_POST['maqaa_daree']);
    if ($conn->query("INSERT INTO classes (maqaa_daree) VALUES ('$maqaa')")) {
        $success_msg = "Daree haaraan milkiin dabalameera!";
    } else { $msg = "Dogoggora: " . $conn->error; }
}

// B. Section Dabaluu
if (isset($_POST['submit_section'])) {
    $d_id = (int)$_POST['daree_id'];
    $m_kutaa = $conn->real_escape_string($_POST['maqaa_kutaa']);
    if ($conn->query("INSERT INTO sections (daree_id, maqaa_kutaa) VALUES ($d_id, '$m_kutaa')")) {
        $success_msg = "Kutaa haaraan milkiin dabalameera!";
    } else { $msg = "Dogoggora: " . $conn->error; }
}

// C. Subject Dabaluu (HAARAA)
if (isset($_POST['submit_subject'])) {
    $d_id = (int)$_POST['daree_id'];
    $sub_name = $conn->real_escape_string($_POST['maqaa_subject']);
    $t_id = !empty($_POST['barsiisaa_id']) ? (int)$_POST['barsiisaa_id'] : "NULL";
    if ($conn->query("INSERT INTO subjects (daree_id, maqaa_gosa_barnootaa, barsiisaa_id) VALUES ($d_id, '$sub_name', $t_id)")) {
        $success_msg = "Gosa barnootaa (Subject) milkiin dabalameera!";
    } else { $msg = "Dogoggora: " . $conn->error; }
}

// D. Attendance Galmeessuu
if (isset($_POST['submit_attendance'])) {
    $guyyaa = $conn->real_escape_string($_POST['guyyaa']);
    if (!empty($_POST['status'])) {
        foreach ($_POST['status'] as $b_id => $status) {
            $b_id = (int)$b_id;
            $status = $conn->real_escape_string($status);
            $conn->query("INSERT INTO attendance (barataa_id, haala_hirmaannaa, guyyaa) VALUES ($b_id, '$status', '$guyyaa')
                          ON DUPLICATE KEY UPDATE haala_hirmaannaa='$status'");
        }
        $success_msg = "Hirmaannaan barattootaa milkiin galmeeffameera!";
    }
}

// E. Exam Dabaluu
if (isset($_POST['submit_exam'])) {
    $m_qorannoo = $conn->real_escape_string($_POST['maqaa_qorannoo']);
    $semisteera = $conn->real_escape_string($_POST['semisteera']);
    $bara = $conn->real_escape_string($_POST['bara_barnootaa']);
    if ($conn->query("INSERT INTO exams (maqaa_qorannoo, semisteera, bara_barnootaa) VALUES ('$m_qorannoo', '$semisteera', '$bara')")) {
        $success_msg = "Qorannoon haaraan milkiin dabalameera!";
    } else { $msg = "Dogoggora: " . $conn->error; }
}

// F. Qabxii (Marks) Galmeessuu (SIRREEFFAMEE JIRA)
if (isset($_POST['submit_marks'])) {
    $q_id = (int)$_POST['qorannoo_id'];
    $s_id = (int)$_POST['subject_id'];
    if (!empty($_POST['qabxii'])) {
        foreach ($_POST['qabxii'] as $b_id => $qabxii_val) {
            $b_id = (int)$b_id;
            $qabxii_val = (float)$qabxii_val;
            $conn->query("INSERT INTO marks (qorannoo_id, barataa_id, subject_id, qabxii) VALUES ($q_id, $b_id, $s_id, $qabxii_val)
                          ON DUPLICATE KEY UPDATE qabxii=$qabxii_val");
        }
        $success_msg = "Qabxiin barattootaa guutummaatti kuusameera!";
    }
}

// G. Barataa Galmeessuu
if (isset($_POST['submit_student'])) {
    $d = $conn->real_escape_string($_POST['daree']); $k = $conn->real_escape_string($_POST['kutaa']);
    $r = $conn->real_escape_string($_POST['roll_no']); $m = $conn->real_escape_string($_POST['maqaa_guutuu']);
    $s = $conn->real_escape_string($_POST['saala']); $a = $conn->real_escape_string($_POST['amantii']);
    $ba = $conn->real_escape_string($_POST['bilbila_abbaa']); $bh = $conn->real_escape_string($_POST['bilbila_haadha']);
    if ($conn->query("INSERT INTO students (daree, kutaa, roll_no, maqaa_guutuu, saala, amantii, bilbila_abbaa, bilbila_haadha) VALUES ('$d', '$k', '$r', '$m', '$s', '$a', '$ba', '$bh')")) { header("Location: ?page=student_list"); exit(); }
}

// H. Barsiisaa Galmeessuu
if (isset($_POST['submit_teacher'])) {
    $m = $conn->real_escape_string($_POST['maqaa_barsiisaa']); $s = $conn->real_escape_string($_POST['saala']);
    $g = $conn->real_escape_string($_POST['gosa_barnootaa']); $b = $conn->real_escape_string($_POST['bilbila']);
    $i = $conn->real_escape_string($_POST['id_nambarii']); $t = $conn->real_escape_string($_POST['teessoo']);
    if ($conn->query("INSERT INTO teachers (maqaa_barsiisaa, saala, gosa_barnootaa, bilbila, id_nambarii, teessoo) VALUES ('$m', '$s', '$g', '$b', '$i', '$t')")) { header("Location: ?page=teacher_list"); exit(); }
}

// I. Settings Save (HAARAA)
if (isset($_POST['submit_settings'])) {
    $s_name = $conn->real_escape_string($_POST['school_name']);
    $s_phone = $conn->real_escape_string($_POST['school_phone']);
    $success_msg = "Sirreeffamni kee milkiin ol-ka'eera! (Simulated)";
}
?>
<!DOCTYPE html>
<html lang="om">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICTVision School System</title>
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
        .form-section-title { color: #1d8ecd; font-size: 15px; font-weight: bold; border-bottom: 1px solid #eef2f5; padding-bottom: 5px; margin: 20px 0 10px 0; text-transform: uppercase; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px; }
        .form-group { display: flex; flex-direction: column; }
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
        @media (max-width: 768px) { .main-container { flex-direction: column; } .sidebar { width: 100%; } .form-grid, .dashboard-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="navbar-custom">
        <div style="font-size:18px; font-weight:bold;">ICTVision School System</div>
        <div><strong>anewar_admin</strong> | <a href="#">Ba'i</a></div>
    </div>
    <div class="main-container">
        <!-- MENU SIDEBAR NAVIGATION -->
        <div class="sidebar">
            <a class="<?php echo ($page == 'dashboard')?'active':''; ?>" href="?page=dashboard">Dashboard</a>
            <a class="<?php echo ($page == 'class')?'active':''; ?>" href="?page=class">Class</a>
            <a class="<?php echo ($page == 'section')?'active':''; ?>" href="?page=section">Section</a>
            <a class="<?php echo ($page == 'subject')?'active':''; ?>" href="?page=subject">Subject</a>
            <a class="<?php echo ($page == 'student_form')?'active':''; ?>" href="?page=student_form">Student Form</a>
            <a class="<?php echo ($page == 'student_list')?'active':''; ?>" href="?page=student_list">Student List</a>
            <a class="<?php echo ($page == 'teacher_form')?'active':''; ?>" href="?page=teacher_form">Teacher Form</a>
            <a class="<?php echo ($page == 'teacher_list')?'active':''; ?>" href="?page=teacher_list">Teacher List</a>
            <a class="<?php echo ($page == 'attendance')?'active':''; ?>" href="?page=attendance">Attendance</a>
            <a class="<?php echo ($page == 'exams')?'active':''; ?>" href="?page=exams">Exams</a>
            <a class="<?php echo ($page == 'mark_manage')?'active':''; ?>" href="?page=mark_manage">Mark Manage</a>
            <a class="<?php echo ($page == 'result')?'active':''; ?>" href="?page=result">Result</a>
            <a class="<?php echo ($page == 'promotion')?'active':''; ?>" href="?page=promotion">Promotion</a>
            <a class="<?php echo ($page == 'voice_sms')?'active':''; ?>" href="?page=voice_sms">Voice / SMS</a>
            <a class="<?php echo ($page == 'settings')?'active':''; ?>" href="?page=settings">Settings</a>
        </div>
        <div class="content-body">
            <?php if(!empty($msg)): ?><div class="alert-error"><?php echo $msg; ?></div><?php endif; ?>
            <?php if(!empty($success_msg)): ?><div class="alert-success"><?php echo $success_msg; ?></div><?php endif; ?>
            <!-- 1. DASHBOARD -->
            <?php if ($page == 'dashboard'): ?>
                <h3>Fuula Dashboard</h3>
                <div class="dashboard-grid">
                    <div class="card card-students"><h4>Baay'ina Barattootaa</h4><p><?php echo $t_st; ?></p></div>
                    <div class="card card-teachers"><h4>Baay'ina Barsiisotaa</h4><p><?php echo $t_tc; ?></p></div>
                </div>

            <!-- 2. CLASS MANAGEMENT -->
            <?php elseif ($page == 'class'): ?>
                <h3>Hoggansa Dareewwanii (Class)</h3>
                <form action="?page=class" method="POST" style="margin-bottom:30px;">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Maqaa Daree:</label><input type="text" name="maqaa_daree" class="form-control" placeholder="Fakkeenya: Class - 1" required></div>
                    </div>
                    <button type="submit" name="submit_class" class="btn-submit">Daree Dabali</button>
                </form>
                <h4>Tarree Dareewwanii</h4>
                <table class="data-table">
                    <thead><tr><th>ID</th><th>Maqaa Daree</th><th>Guyyaa Uumame</th></tr></thead>
                    <tbody>
                        <?php $res = $conn->query("SELECT * FROM classes");
                        while($row = $res->fetch_assoc()): ?>
                            <tr><td><?php echo $row['id']; ?></td><td><?php echo htmlspecialchars($row['maqaa_daree']); ?></td><td><?php echo $row['guyyaa_uumame']; ?></td></tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <!-- 3. SECTION MANAGEMENT -->
            <?php elseif ($page == 'section'): ?>
                <h3>Hoggansa Kutaalee (Section)</h3>
                <form action="?page=section" method="POST" style="margin-bottom:30px;">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Daree Filadhu:</label>
                            <select name="daree_id" class="form-select">
                                <?php $cl = $conn->query("SELECT * FROM classes"); while($c = $cl->fetch_assoc()): ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['maqaa_daree']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Maqaa Kutaa:</label><input type="text" name="maqaa_kutaa" class="form-control" placeholder="Fakkeenya: Blue" required></div>
                    </div>
                    <button type="submit" name="submit_section" class="btn-submit">Kutaa Dabali</button>
                </form>

            <!-- 4. SUBJECT MANAGEMENT (HAARAA) -->
            <?php elseif ($page == 'subject'): ?>
                <h3>Hoggansa Gosoota Barnootaa (Subject)</h3>
                <form action="?page=subject" method="POST" style="margin-bottom:30px;">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Daree Filadhu:</label>
                            <select name="daree_id" class="form-select">
                                <?php $cl = $conn->query("SELECT * FROM classes"); while($c = $cl->fetch_assoc()): ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['maqaa_daree']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Maqaa Subject:</label><input type="text" name="maqaa_subject" class="form-control" placeholder="Fakkeenya: Afaan Oromoo" required></div>
                        <div class="form-group"><label class="form-label">Barsiisaa Waliin Hidhi (Ooptional):</label>
                            <select name="barsiisaa_id" class="form-select">
                                <option value="">Filadhu...</option>
                                <?php $tc = $conn->query("SELECT * FROM teachers"); while($t = $tc->fetch_assoc()): ?>
                                    <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['maqaa_barsiisaa']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="submit_subject" class="btn-submit">Subject Dabali</button>
                </form>

            <!-- 5. STUDENT FORM -->
            <?php elseif ($page == 'student_form'): ?>
                <h3>Unka Galmeessa Barataa</h3>
                <form action="?page=student_form" method="POST">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Daree:</label>
                            <select name="daree" class="form-select">
                                <?php $cl = $conn->query("SELECT * FROM classes"); while($c = $cl->fetch_assoc()): ?>
                                    <option value="<?php echo htmlspecialchars($c['maqaa_daree']); ?>"><?php echo htmlspecialchars($c['maqaa_daree']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Kutaa:</label><select name="kutaa" class="form-select"><option value="Blue">Blue</option><option value="Red">Red</option></select></div>
                        <div class="form-group"><label class="form-label">Roll No:</label><input type="text" name="roll_no" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Maqaa Guutuu:</label><input type="text" name="maqaa_guutuu" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Saala:</label><select name="saala" class="form-select"><option value="Korma">Korma</option><option value="Dhalaa">Dhalaa</option></select></div>
                        <div class="form-group"><label class="form-label">Amantii:</label><input type="text" name="amantii" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Bilbila Abbaa:</label><input type="text" name="bilbila_abbaa" class="form-control"></div>
                        <div class="form-group"><label class="form-label">Bilbila Haadha:</label><input type="text" name="bilbila_haadha" class="form-control"></div>
                    </div>
                    <button type="submit" name="submit_student" class="btn-submit">Galmeessi</button>
                </form>

            <!-- 6. STUDENT LIST -->
            <?php elseif ($page == 'student_list'): ?>
                <h3>Tarree Barattootaa</h3>
                <table class="data-table">
                    <thead><tr><th>Roll No</th><th>Maqaa Guutuu</th><th>Daree</th><th>Kutaa</th><th>Saala</th></tr></thead>
                    <tbody>
                        <?php $res = $conn->query("SELECT * FROM students"); while($row = $res->fetch_assoc()): ?>
                            <tr><td><?php echo $row['roll_no']; ?></td><td><?php echo $row['maqaa_guutuu']; ?></td><td><?php echo $row['daree']; ?></td><td><?php echo $row['kutaa']; ?></td><td><?php echo $row['saala']; ?></td></tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <!-- 7. TEACHER FORM & LIST -->
            <?php elseif ($page == 'teacher_form'): ?>
                <h3>Unka Galmeessa Barsiisaa</h3>
                <form action="?page=teacher_form" method="POST">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Maqaa Barsiisaa:</label><input type="text" name="maqaa_barsiisaa" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Saala:</label><select name="saala" class="form-select"><option value="Korma">Korma</option><option value="Dhalaa">Dhalaa</option></select></div>
                        <div class="form-group"><label class="form-label">Gosa Barnootaa:</label><input type="text" name="gosa_barnootaa" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Bilbila:</label><input type="text" name="bilbila" class="form-control"></div>
                        <div class="form-group"><label class="form-label">ID Nambarii:</label><input type="text" name="id_nambarii" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Teessoo:</label><input type="text" name="teessoo" class="form-control"></div>
                    </div>
                    <button type="submit" name="submit_teacher" class="btn-submit">Galmeessi</button>
                </form>

            <?php elseif ($page == 'teacher_list'): ?>
                <h3>Tarree Barsiisotaa</h3>
                <table class="data-table">
                    <thead><tr><th>ID</th><th>Maqaa</th><th>Gosa Barnootaa</th><th>Bilbila</th></tr></thead>
                    <tbody>
                        <?php $res = $conn->query("SELECT * FROM teachers"); while($row = $res->fetch_assoc()): ?>
                            <tr><td><?php echo $row['id_nambarii']; ?></td><td><?php echo $row['maqaa_barsiisaa']; ?></td><td><?php echo $row['gosa_barnootaa']; ?></td><td><?php echo $row['bilbila']; ?></td></tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <!-- 8. ATTENDANCE -->
            <?php elseif ($page == 'attendance'): ?>
                <h3>Hordoffii Hirmaannaa Barattootaa</h3>
                <form action="?page=attendance" method="POST">
                    <div class="form-group" style="width:250px; margin-bottom:20px;">
                        <label class="form-label">Guyyaa Filadhu:</label>
                        <input type="date" name="guyyaa" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <table class="data-table">
                        <thead><tr><th>Roll No</th><th>Maqaa Guutuu</th><th>Daree</th><th>Haala Hirmaannaa</th></tr></thead>
                        <tbody>
                            <?php $st = $conn->query("SELECT * FROM students"); 
                            while($row = $st->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['roll_no']; ?></td>
                                    <td><?php echo $row['maqaa_guutuu']; ?></td>
                                    <td><?php echo $row['daree']; ?></td>
                                    <td>
                                        <select name="status[<?php echo $row['id']; ?>]" class="form-select">
                                            <option value="Argame">Argame</option>
                                            <option value="Hafe">Hafe</option>
                                            <option value="Eeyyamame">Eeyyamame</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="submit_attendance" class="btn-submit">Hirmaannaa Galmeessi</button>
                </form>

            <!-- 9. EXAMS MANAGEMENT -->
            <?php elseif ($page == 'exams'): ?>
                <h3>Hoggansa Qorannoof Semisteeraa</h3>
                <form action="?page=exams" method="POST" style="margin-bottom:30px;">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Maqaa Qorannoo:</label><input type="text" name="maqaa_qorannoo" class="form-control" placeholder="Fakkeenya: Mid-Exam" required></div>
                        <div class="form-group"><label class="form-label">Semisteera:</label><select name="semisteera" class="form-select"><option value="Semester 1">Semester 1</option><option value="Semester 2">Semester 2</option></select></div>
                        <div class="form-group"><label class="form-label">Bara Barnootaa:</label><input type="text" name="bara_barnootaa" class="form-control" value="2018 E.C." required></div>
                    </div>
                    <button type="submit" name="submit_exam" class="btn-submit">Qorannoo Dabali</button>
                </form>

            <!-- 10. MARK MANAGEMENT (DYNAMICS SUBJECT) -->
            <?php elseif ($page == 'mark_manage'): ?>
                <h3>Galmeessa Qabxii Barattootaa</h3>
                <form action="?page=mark_manage" method="POST">
                    <div class="form-grid" style="margin-bottom:20px;">
                        <div class="form-group"><label class="form-label">Qorannoo Filadhu:</label>
                            <select name="qorannoo_id" class="form-select">
                                <?php $ex = $conn->query("SELECT * FROM exams"); while($e = $ex->fetch_assoc()): ?>
                                    <option value="<?php echo $e['id']; ?>"><?php echo htmlspecialchars($e['maqaa_qorannoo']." (".$e['semisteera'].")"); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group"><label class="form-label">Subject Filadhu:</label>
                            <select name="subject_id" class="form-select">
                                <?php $sub = $conn->query("SELECT * FROM subjects"); while($s = $sub->fetch_assoc()): ?>
                                    <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['maqaa_gosa_barnootaa']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                    <h4>Tarree Barattootaa fi Bakka Qabxii</h4>
                    <table class="data-table">
                        <thead><tr><th>Roll No</th><th>Maqaa Guutuu</th><th>Daree</th><th>Qabxii (100%)</th></tr></thead>
                        <tbody>
                            <?php $st = $conn->query("SELECT * FROM students"); 
                            while($row = $st->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['roll_no']; ?></td>
                                    <td><?php echo $row['maqaa_guutuu']; ?></td>
                                    <td><?php echo $row['daree']; ?></td>
                                    <td><input type="number" step="0.01" name="qabxii[<?php echo $row['id']; ?>]" class="form-control" style="width:120px;" placeholder="0-100" min="0" max="100"></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="submit_marks" class="btn-submit">Qabxii Kuusi</button>
                </form>

            <!-- 11. RESULT VIEW PAGE -->
            <?php elseif ($page == 'result'): ?>
                <h3>Bu'aa fi Sadarkaa Barattootaa (Result)</h3>
                <table class="data-table">
                    <thead><tr><th>Roll No</th><th>Maqaa Guutuu</th><th>Qabxii Ida'amaa</th><th>Haala darbiinsaa</th></tr></thead>
                    <tbody>
                        <?php 
                        $res = $conn->query("SELECT s.roll_no, s.maqaa_guutuu, SUM(m.qabxii) as total_mark FROM students s LEFT JOIN marks m ON s.id = m.barataa_id GROUP BY s.id ORDER BY total_mark DESC");
                        while($row = $res->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['roll_no']; ?></td>
                                <td><?php echo htmlspecialchars($row['maqaa_guutuu']); ?></td>
                                <td><?php echo $row['total_mark'] ?? '0'; ?></td>
                                <td><span style="color:green; font-weight:bold;">Darbaa</span></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

            <!-- 12. PROMOTION PAGE -->
            <?php elseif ($page == 'promotion'): ?>
                <h3>Fuula Promotion (Kutaa Dabarsuu)</h3>
                <p style="color:#555;">Kutaa dabarre barattoota hunda gara daree itti aanutti daddabarsuuf tajaajila.</p>
                <button class="btn-submit" style="float:left; margin-top:20px;">Barattoota Hunda Kutaa Dabarsi</button>

            <!-- 13. VOICE / SMS PAGE -->
            <?php elseif ($page == 'voice_sms'): ?>
                <h3>Ergaa SMS fi Sagalee (Voice / SMS)</h3>
                <div class="form-group" style="margin-top:15px;">
                    <label class="form-label">Gosa Ergaa:</label>
                    <select class="form-select" style="width:300px;"><option>Barattoota hundaaf</option><option>Barsiisota hundaaf</option></select>
                    <br>
                    <label class="form-label">Barreeffama Ergaa:</label>
                    <textarea class="form-control" rows="4" placeholder="Ergaa gabaabaa asitti barreessi..."></textarea>
                    <button class="btn-submit" style="float:left; margin-top:15px;">Ergaa SMS Ergi</button>
                </div>

            <!-- 14. SETTINGS VIEW PAGE (HAARAA) -->
            <?php elseif ($page == 'settings'): ?>
                <h3>Sirreeffama Sirnaa (System Settings)</h3>
                <form action="?page=settings" method="POST">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Maqaa Mana Barnootaa:</label><input type="text" name="school_name" class="form-control" value="ICTVision School" required></div>
                        <div class="form-group"><label class="form-label">Lakkoofsa Bilbilaa:</label><input type="text" name="school_phone" class="form-control" value="+251912345678"></div>
                    </div>
                    <button type="submit" name="submit_settings" class="btn-submit">Oolchi (Save)</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
