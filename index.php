<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 1. Database Connection waliin walitti hidhuu
$conn = new mysqli("mysql-anewar.alwaysdata.net", "anewar_admin", "015661Emran@", "anewar_school_db");
if ($conn->connect_error) { 
    die("Kuusaa odeeffannoo waliin walitti hidhuun hin danda'amne: " . $conn->connect_error); 
}

$msg = ""; $msg_type = "";
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Baay'ina Barattootaa fi Barsiisotaa herreguuf
$t_st = 0; $t_tc = 0;
$st_q = $conn->query("SELECT COUNT(*) as t FROM students");
if ($st_q) { $t_st = $st_q->fetch_assoc()['t']; }
$tc_q = $conn->query("SELECT COUNT(*) as t FROM teachers");
if ($tc_q) { $t_tc = $tc_q->fetch_assoc()['t']; }

// 2. Data Insertion Logic
if (isset($_POST['submit_student'])) {
    $d = $conn->real_escape_string($_POST['daree']); 
    $k = $conn->real_escape_string($_POST['kutaa']);
    $r = $conn->real_escape_string($_POST['roll_no']); 
    $m = $conn->real_escape_string($_POST['maqaa_guutuu']);
    $s = $conn->real_escape_string($_POST['saala']); 
    $a = $conn->real_escape_string($_POST['amantii']);
    $ba = $conn->real_escape_string($_POST['bilbila_abbaa']); 
    $bh = $conn->real_escape_string($_POST['bilbila_haadha']);
    
    if ($conn->query("INSERT INTO students (daree, kutaa, roll_no, maqaa_guutuu, saala, amantii, bilbila_abbaa, bilbila_haadha) VALUES ('$d', '$k', '$r', '$m', '$s', '$a', '$ba', '$bh')")) { 
        header("Location: ?page=student_list"); 
        exit(); 
    }
}

if (isset($_POST['submit_teacher'])) {
    $m = $conn->real_escape_string($_POST['maqaa_barsiisaa']); 
    $s = $conn->real_escape_string($_POST['saala']);
    $g = $conn->real_escape_string($_POST['gosa_barnootaa']); 
    $b = $conn->real_escape_string($_POST['bilbila']);
    $i = $conn->real_escape_string($_POST['id_nambarii']); 
    $t = $conn->real_escape_string($_POST['teessoo']);
    
    if ($conn->query("INSERT INTO teachers (maqaa_barsiisaa, saala, gosa_barnootaa, bilbila, id_nambarii, teessoo) VALUES ('$m', '$s', '$g', '$b', '$i', '$t')")) { 
        header("Location: ?page=teacher_list"); 
        exit(); 
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
        .form-section-title { color: #1d8ecd; font-size: 15px; font-weight: bold; border-bottom: 1px solid #eef2f5; padding-bottom: 5px; margin: 20px 0 10px 0; text-transform: uppercase; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 10px; }
        .form-group { display: flex; flex-direction: column; }
        .form-label { font-size: 13px; margin-bottom: 5px; font-weight: 500; }
        .form-control, .form-select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .radio-group { display: flex; gap: 20px; margin-top: 5px; }
        .btn-submit { background-color: #1d8ecd; color: white; border: none; padding: 10px 25px; border-radius: 4px; font-weight: bold; cursor: pointer; float: right; margin-top: 15px; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px; }
        .data-table th, .data-table td { padding: 10px; border-bottom: 1px solid #eef2f5; text-align: left; }
        .data-table th { background-color: #f8f9fa; color: #555; }
        .dashboard-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px; }
        .card { padding: 25px; border-radius: 6px; color: white; font-weight: bold; }
        .card-students { background-color: #28a745; }
        .card-teachers { background-color: #fd7e14; }
        .card h4 { font-size: 14px; margin-bottom: 5px; text-transform: uppercase; opacity: 0.9; }
        .card p { font-size: 30px; }
        @media (max-width: 768px) { .main-container { flex-direction: column; } .sidebar { width: 100%; } .form-grid, .dashboard-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="navbar-custom"><div style="font-size:18px; font-weight:bold;">ICTVision School System</div><div><strong>anewar_admin</strong> | <a href="#">Bahi</a></div></div>
    <div class="main-container">
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
            <?php if ($page == 'dashboard'): ?>
                <h3>Fuula Dashboard</h3>
                <div class="dashboard-grid">
                    <div class="card card-students"><h4>Baay'ina Barattootaa</h4><p><?php echo $t_st; ?></p></div>
                    <div class="card card-teachers"><h4>Baay'ina Barsiisotaa</h4><p><?php echo $t_tc; ?></p></div>
                </div>
            <?php elseif ($page == 'student_form'): ?>
                <h3>Unka Galmeessa Barataa</h3>
                <form action="?page=student_form" method="POST">
                    <div class="form-section-title">Odeeffannoo Daree</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Daree:</label><select name="daree" class="form-select"><option value="Class - 1">Class - 1</option><option value="Class - 2">Class - 2</option></select></div>
                        <div class="form-group"><label class="form-label">Kutaa:</label><select name="kutaa" class="form-select"><option value="Blue (25)">Blue (25)</option><option value="Red (20)">Red (20)</option></select></div>
                    </div>
                    <div class="form-section-title">Odeeffannoo Barataa</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Roll No:</label><input type="text" name="roll_no" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Maqaa Guutuu:</label><input type="text" name="maqaa_guutuu" class="form-control" required></div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Saala:</label><div class="radio-group"><label><input type="radio" name="saala" value="Dhiira" checked> Dhiira</label><label><input type="radio" name="saala" value="Dubara"> Dubara</label></div></div>
                        <div class="form-group"><label class="form-label">Amantii:</label><input type="text" name="amantii" class="form-control"></div>
                    </div>
                    <div class="form-section-title">Teessoo</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Bilbila Abbaa:</label><input type="tel" name="bilbila_abbaa" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">Bilbila Haadha:</label><input type="tel" name="bilbila_haadha" class="form-control"></div>
                    </div>
                    <button type="submit" name="submit_student" class="btn-submit">Galmeessi</button><div style="clear:both;"></div>
                </form>
            <?php elseif ($page == 'student_list'): ?>
