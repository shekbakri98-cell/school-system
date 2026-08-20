<?php
// 1. Database Connection
$conn = new mysqli("mysql-anewar.alwaysdata.net", "anewar_admin", "015661Emran@", "anewar_school_db");
if ($conn->connect_error) { die("Kuusaa odeeffannoo waliin walitti hidhuun hin danda'amne: " . $conn->connect_error); }

$msg = ""; $msg_type = "";
$page = isset($_GET['page']) ? $_GET['page'] : 'student_form';

// 2. Data Insertion Logic
if (isset($_POST['submit_student'])) {
    $daree = $conn->real_escape_string($_POST['daree']); $kutaa = $conn->real_escape_string($_POST['kutaa']);
    $roll = $conn->real_escape_string($_POST['roll_no']); $maqaa = $conn->real_escape_string($_POST['maqaa_guutuu']);
    $saala = $conn->real_escape_string($_POST['saala']); $amantii = $conn->real_escape_string($_POST['amantii']);
    $b_abbaa = $conn->real_escape_string($_POST['bilbila_abbaa']); $b_haadha = $conn->real_escape_string($_POST['bilbila_haadha']);
    if ($conn->query("INSERT INTO students (daree, kutaa, roll_no, maqaa_guutuu, saala, amantii, bilbila_abbaa, bilbila_haadha) VALUES ('$daree', '$kutaa', '$roll', '$maqaa', '$saala', '$amantii', '$b_abbaa', '$b_haadha')")) { $msg = "Milkaa'ina! Barataan galmeeffameera."; $msg_type = "success"; }
}
if (isset($_POST['submit_teacher'])) {
    $maqaa_b = $conn->real_escape_string($_POST['maqaa_barsiisaa']); $saala = $conn->real_escape_string($_POST['saala']);
    $gosa = $conn->real_escape_string($_POST['gosa_barnootaa']); $bilbila = $conn->real_escape_string($_POST['bilbila']);
    $id_n = $conn->real_escape_string($_POST['id_nambarii']); $teessoo = $conn->real_escape_string($_POST['teessoo']);
    if ($conn->query("INSERT INTO teachers (maqaa_barsiisaa, saala, gosa_barnootaa, bilbila, id_nambarii, teessoo) VALUES ('$maqaa_b', '$saala', '$gosa', '$bilbila', '$id_n', '$teessoo')")) { $msg = "Milkaa'ina! Barsiisaan galmeeffameera."; $msg_type = "success"; }
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
        .sidebar { width: 230px; background-color: white; border-radius: 6px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
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
        .alert { padding: 12px; margin-bottom: 15px; border-radius: 4px; font-size: 14px; font-weight: bold; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .data-table { width: 100%; border-collapse: collapse; font-size: 14px; margin-top: 10px; }
        .data-table th, .data-table td { padding: 10px; border-bottom: 1px solid #eef2f5; text-align: left; }
        .data-table th { background-color: #f8f9fa; color: #555; }
        @media (max-width: 768px) { .main-container { flex-direction: column; } .sidebar { width: 100%; } .form-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
    <div class="navbar-custom"><div style="font-size:18px; font-weight:bold;">ICTVision School System</div><div><strong>anewar_admin</strong> | <a href="#">Bahi</a></div></div>
    <div class="main-container">
        <div class="sidebar">
            <a href="?page=dashboard">Dashboard</a><a href="?page=class">Class</a><a href="?page=section">Section</a><a href="?page=subject">Subject</a>
            <a class="<?php echo ($page == 'student_form')?'active':''; ?>" href="?page=student_form">Student Form</a>
            <a class="<?php echo ($page == 'student_list')?'active':''; ?>" href="?page=student_list">Student List</a>
            <a class="<?php echo ($page == 'teacher_form')?'active':''; ?>" href="?page=teacher_form">Teacher Form</a>
            <a href="?page=attendance">Attendance</a><a href="?page=exams">Exams</a><a href="?page=mark_manage">Mark Manage</a><a href="?page=result">Result</a><a href="?page=promotion">Promotion</a><a href="?page=voice_sms">Voice / SMS</a><a href="?page=settings">Settings</a>
        </div>
        <div class="content-body">
            <?php if (!empty($msg)): ?><div class="alert alert-success"><?php echo $msg; ?></div><?php endif; ?>

            <?php if ($page == 'student_form'): ?>
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
                <h3>Tarree Barattootaa</h3>
                <div class="table-responsive"><table class="data-table">
                    <thead><tr><th>ID</th><th>Maqaa Guutuu</th><th>Roll No</th><th>Daree</th><th>Kutaa</th><th>Saala</th><th>Amantii</th><th>Bilbila Abbaa</th></tr></thead>
                    <tbody>
                        <?php
                        $res = $conn->query("SELECT * FROM students ORDER BY id DESC");
                        if ($res && $res->num_rows > 0) { while($row = $res->fetch_assoc()) { echo "<tr><td>".$row['id']."</td><td style='color:#1d8ecd;font-weight:bold;'>".$row['maqaa_guutuu']."</td><td>".$row['roll_no']."</td><td>".$row['daree']."</td><td>".$row['kutaa']."</td><td>".$row['saala']."</td><td>".$row['amantii']."</td><td>".$row['bilbila_abbaa']."</td></tr>"; } }
                        else { echo "<tr><td colspan='8' style='text-align:center;color:#999;'>Barataan hin jiru.</td></tr>"; }
                        ?>
                    </tbody>
                </table></div>

            <?php elseif ($page == 'teacher_form'): ?>
                <h3>Unka Galmeessa Barsiisotaa</h3>
                <form action="?page=teacher_form" method="POST">
                    <div class="form-section-title">Odeeffannoo Barsiisichaa</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Maqaa Guutuu:</label><input type="text" name="maqaa_barsiisaa" class="form-control" required></div>
                        <div class="form-group"><label class="form-label">ID Nambarii:</label><input type="text" name="id_nambarii" class="form-control" required></div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Gosa Barnootaa:</label><input type="text" name="gosa_barnootaa" class="form-control" required/></div>
                        <div class="form-group"><label class="form-label">Bilbila:</label><input type="tel" name="bilbila" class="form-control" required/></div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Saala:</label><div class="radio-group"><div class="radio-item"><input type="radio" name="saala" value="Dhiira" checked/> Dhiira</div><div class="radio-item"><input type="radio" name="saala" value="Dubara"/> Dubara</div></div></div>
                        <div class="form-group"><label class="form-label">Teessoo:</label><input type="text" name="teessoo" class="form-control"/></div>
                    </div>
                    <button type="submit" name="submit_teacher" class="btn-submit">Barsiisaa Galmeessi</button><div class="clear"></div>
                </form>

            <?php else: ?>
                <h3>Fuula <?php echo ucfirst($page); ?></h3><p style="color:#666; font-size:14px;">Fuulli kun qorannoo irra jira.</p>
            <?php endif; ?>
            <p class="footer-text">ICTVision School System ©2017 - 2026</p>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
