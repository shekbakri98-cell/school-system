<?php
// Faayila database fi style asitti waamna
include('db.php');

// Barataa Galmeessuu
if (isset($_POST['submit_student'])) {
    $daree = $conn->real_escape_string($_POST['daree']);
    $kutaa = $conn->real_escape_string($_POST['kutaa']);
    $roll_no = $conn->real_escape_string($_POST['roll_no']);
    $maqaa_guutuu = $conn->real_escape_string($_POST['maqaa_guutuu']);
    $saala = $conn->real_escape_string($_POST['saala']);
    $amantii = $conn->real_escape_string($_POST['amantii']);
    $bilbila_abbaa = $conn->real_escape_string($_POST['bilbila_abbaa']);
    $bilbila_haadha = $conn->real_escape_string($_POST['bilbila_haadha']);

    $sql = "INSERT INTO students (daree, kutaa, roll_no, maqaa_guutuu, saala, amantii, bilbila_abbaa, bilbila_haadha) VALUES ('$daree', '$kutaa', '$roll_no', '$maqaa_guutuu', '$saala', '$amantii', '$bilbila_abbaa', '$bilbila_haadha')";
    if ($conn->query($sql) === TRUE) { $msg = "Milkaa'ina! Barataan haala sirriin galmeeffameera."; $msg_type = "success"; }
    else { $msg = "Dogoggorri uumame: " . $conn->error; $msg_type = "danger"; }
}

// Barsiisaa Galmeessuu
if (isset($_POST['submit_teacher'])) {
    $maqaa_barsiisaa = $conn->real_escape_string($_POST['maqaa_barsiisaa']);
    $saala = $conn->real_escape_string($_POST['saala']);
    $gosa_barnootaa = $conn->real_escape_string($_POST['gosa_barnootaa']);
    $bilbila = $conn->real_escape_string($_POST['bilbila']);
    $id_nambarii = $conn->real_escape_string($_POST['id_nambarii']);
    $teessoo = $conn->real_escape_string($_POST['teessoo']);

    $sql = "INSERT INTO teachers (maqaa_barsiisaa, saala, gosa_barnootaa, bilbila, id_nambarii, teessoo) VALUES ('$maqaa_barsiisaa', '$saala', '$gosa_barnootaa', '$bilbila', '$id_nambarii', '$teessoo')";
    if ($conn->query($sql) === TRUE) { $msg = "Milkaa'ina! Barsiisaan haala sirriin galmeeffameera."; $msg_type = "success"; }
    else { $msg = "Dogoggorri uumame: " . $conn->error; $msg_type = "danger"; }
}
?>
<!DOCTYPE html>
<html lang="om">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ICTVision School System</title>
    <?php include('style.php'); ?>
</head>
<body>
    <div class="navbar-custom"><div class="brand">ICTVision School System</div><div class="user-info"><strong>anewar_admin</strong> | <a href="#">Bahi</a></div></div>
    <div class="main-container">
        <div class="sidebar">
            <div class="menu-header">MAIN</div>
            <a class="nav-link" href="?page=dashboard">Dashboard</a>
            <a class="nav-link" href="?page=class">Class</a>
            <a class="nav-link" href="?page=section">Section</a>
            <a class="nav-link" href="?page=subject">Subject</a>
            <a class="nav-link <?php echo ($page == 'student_form') ? 'active' : ''; ?>" href="?page=student_form">Student Form</a>
            <a class="nav-link <?php echo ($page == 'student_list') ? 'active' : ''; ?>" href="?page=student_list">Student List</a>
            <a class="nav-link <?php echo ($page == 'teacher_form') ? 'active' : ''; ?>" href="?page=teacher_form">Teacher Form</a>
            <a class="nav-link" href="?page=attendance">Attendance</a><a class="nav-link" href="?page=exams">Exams</a><a class="nav-link" href="?page=mark_manage">Mark Manage</a><a class="nav-link" href="?page=result">Result</a><a class="nav-link" href="?page=promotion">Promotion</a><a class="nav-link" href="?page=voice_sms">Voice / SMS</a><a class="nav-link" href="?page=settings">Settings</a>
        </div>
        <div class="content-body">
            <?php if (!empty($msg)): ?><div class="alert alert-<?php echo $msg_type; ?>"><?php echo $msg; ?></div><?php endif; ?>
            
            <?php if ($page == 'student_form'): ?>
                <h3>Unka Galmeessa Barataa</h3>
                <form action="?page=student_form" method="POST">
                    <div class="form-section-title">Odeeffannoo Daree</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Daree:</label><select name="daree" class="form-select" required><option value="Class - 1">Class - 1</option><option value="Class - 2">Class - 2</option><option value="Class - 3">Class - 3</option></select></div>
                        <div class="form-group"><label class="form-label">Kutaa:</label><select name="kutaa" class="form-select" required><option value="Blue (25)">Blue (25)</option><option value="Red (20)">Red (20)</option><option value="Green (30)">Green (30)</option></select></div>
                    </div>
                    <div class="form-section-title">Odeeffannoo Barataa</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Roll No:</label><input type="text" name="roll_no" class="form-control" required/></div>
                        <div class="form-group"><label class="form-label">Maqaa Guutuu:</label><input type="text" name="maqaa_guutuu" class="form-control" required/></div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Saala:</label><div class="radio-group"><div class="radio-item"><input type="radio" name="saala" value="Dhiira" checked/> Dhiira</div><div class="radio-item"><input type="radio" name="saala" value="Dubara"/> Dubara</div></div></div>
                        <div class="form-group"><label class="form-label">Amantii:</label><input type="text" name="amantii" class="form-control"/></div>
                    </div>
                    <div class="form-section-title">Teessoo</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Bilbila Abbaa:</label><input type="tel" name="bilbila_abbaa" class="form-control" required/></div>
                        <div class="form-group"><label class="form-label">Bilbila Haadha:</label><input type="tel" name="bilbila_haadha" class="form-control"/></div>
                    </div>
                    <button type="submit" name="submit_student" class="btn-submit">Galmeessi</button><div class="clear"></div>
                </form>

            <?php elseif ($page == 'student_list'): ?>
                <h3>Tarree Barattootaa</h3>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead><tr><th>ID</th><th>Maqaa Guutuu</th><th>Roll No</th><th>Daree</th><th>Kutaa</th><th>Saala</th><th>Amantii</th><th>Bilbila Abbaa</th></tr></thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM students ORDER BY id DESC");
                            if ($result && $result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr><td>".$row['id']."</td><td style='color:#1d8ecd;font-weight:600;'>".$row['maqaa_guutuu']."</td><td>".$row['roll_no']."</td><td>".$row['daree']."</td><td>".$row['kutaa']."</td><td>".$row['saala']."</td><td>".$row['amantii']."</td><td>".$row['bilbila_abbaa']."</td></tr>";
                                }
                            } else { echo "<tr><td colspan='8' class='no-data'>Barataan hin jiru.</td></tr>"; }
                            ?>
                        </tbody>
                    </table>
                </div>

            <?php elseif ($page == 'teacher_form'): ?>
                <h3>Unka Galmeessa Barsiisotaa</h3>
                <form action="?page=teacher_form" method="POST">
                    <div class="form-section-title">Odeeffannoo Barsiisichaa</div>
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Maqaa Guutuu:</label><input type="text" name="maqaa_barsiisaa" class="form-control" required/></div>
                        <div class="form-group"><label class="form-label">ID Nambarii:</label><input type="text" name="id_nambarii" class="form-control" required/></div>
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
