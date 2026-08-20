<?php
session_start();
include('db_connect.php'); // Sarvarii Alwaysdata waliin walitti hidhuuf

// Fuula weebsaayitiikee kam akka dhufe adda baasuuf
$page = isset($_GET['view']) ? $_GET['view'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="or">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICTVision School System</title>
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://jsdelivr.net">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 0.9rem; }
        .navbar-custom { background-color: #2980b9; color: white; padding: 12px 20px; font-weight: bold; }
        .navbar-right-btns .btn { color: white; background: rgba(255,255,255,0.15); border: none; margin-left: 5px; font-size: 0.85rem; }
        .sidebar { background: white; border: 1px solid #dee2e6; border-radius: 4px; }
        .sidebar-header { background: #f8f9fa; padding: 8px 15px; font-weight: bold; color: #adb5bd; text-transform: uppercase; font-size: 0.75rem; border-bottom: 1px solid #dee2e6; }
        .sidebar .list-group-item { border: none; border-bottom: 1px solid #f1f3f5; color: #495057; padding: 10px 15px; font-size: 0.85rem; }
        .sidebar .list-group-item:hover { background-color: #f8f9fa; color: #007bff; text-decoration: none; }
        .sidebar .list-group-item.active-menu { background-color: #3498db; color: white; font-weight: bold; }
        .sidebar .list-group-item i { margin-right: 8px; width: 18px; text-align: center; }
        .sub-menu { padding-left: 30px !important; background: #fafbfc; font-size: 0.8rem !important; }
        .card-custom { border: 1px solid #dee2e6; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .card-header-custom { background: #f8f9fa; font-weight: bold; color: #495057; border-bottom: 1px solid #dee2e6; }
        .action-btn { padding: 2px 6px; font-size: 0.75rem; border-radius: 3px; color: white; margin-right: 2px; }
        .table th { background: #f8f9fa; color: #333; font-weight: 600; font-size: 0.85rem; }
        footer { font-size: 0.75rem; color: #6c757d; margin-top: 30px; padding: 15px 0; border-top: 1px solid #dee2e6; }
    </style>
</head>
<body>

<!-- HEADER BAR -->
<div class="navbar-custom d-flex justify-content-between align-items-center">
    <div>ICTVision School System</div>
    <div class="navbar-right-btns d-flex">
        <button class="btn"><i class="fas fa-money-bill-wave"></i> Fees</button>
        <button class="btn"><i class="fas fa-file-invoice"></i> Reports</button>
        <button class="btn"><i class="fas fa-user-shield"></i> Mr. Admin</button>
    </div>
</div>

<div class="container-fluid mt-3">
    <div class="row">
        
        <!-- BITA IRRATTI: SIDEBAR MENU -->
        <div class="col-md-2 mb-3">
            <div class="sidebar">
                <div class="sidebar-header">Main</div>
                <div class="list-group list-group-flush">
                    <a href="?view=dashboard" class="list-group-item <?php echo $page=='dashboard'?'active-menu':''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    <a href="?view=class_list" class="list-group-item <?php echo $page=='class_list'?'active-menu':''; ?>"><i class="fas fa-home"></i> Class</a>
                    <a href="?view=sections" class="list-group-item <?php echo $page=='sections'?'active-menu':''; ?>"><i class="fas fa-folder"></i> Section</a>
                    <a href="?view=subjects" class="list-group-item <?php echo $page=='subjects'?'active-menu':''; ?>"><i class="fas fa-book"></i> Subject</a>
                    
                    <!-- STUDENT SUB-MENUS -->
                    <a href="?view=student_list" class="list-group-item <?php echo $page=='student_list'?'active-menu':''; ?>"><i class="fas fa-user"></i> Student</a>
                    <?php if($page=='student_list') { ?>
                        <a href="#" class="list-group-item sub-menu"><i class="fas fa-plus"></i> Add New</a>
                        <a href="?view=student_list" class="list-group-item sub-menu text-primary font-weight-bold"><i class="fas fa-list"></i> Student List</a>
                    <?php } ?>

                    <!-- TEACHER SUB-MENUS -->
                    <a href="?view=teacher_list" class="list-group-item <?php echo $page=='teacher_list' || $page=='timetable'?'active-menu':''; ?>"><i class="fas fa-chalkboard-teacher"></i> Teacher</a>
                    <?php if($page=='teacher_list' || $page=='timetable') { ?>
                        <a href="?view=teacher_list" class="list-group-item sub-menu <?php echo $page=='teacher_list'?'text-primary font-weight-bold':''; ?>"><i class="fas fa-list"></i> Teacher List</a>
                        <a href="?view=timetable" class="list-group-item sub-menu <?php echo $page=='timetable'?'text-primary font-weight-bold':''; ?>"><i class="fas fa-calendar-alt"></i> Timetable Mgmt</a>
                    <?php } ?>

                    <a href="?view=attendance" class="list-group-item <?php echo $page=='attendance'?'active-menu':''; ?>"><i class="fas fa-edit"></i> Attendance</a>
                    <a href="?view=exams" class="list-group-item <?php echo $page=='exams'?'active-menu':''; ?>"><i class="fas fa-graduation-cap"></i> Exams</a>
                    <a href="?view=mark_manage" class="list-group-item <?php echo $page=='mark_manage'?'active-menu':''; ?>"><i class="fas fa-file-signature"></i> Mark Manage</a>
                    <a href="?view=result" class="list-group-item <?php echo $page=='result'?'active-menu':''; ?>"><i class="fas fa-poll"></i> Result</a>
                    <a href="?view=promotion" class="list-group-item <?php echo $page=='promotion'?'active-menu':''; ?>"><i class="fas fa-arrow-up"></i> Promotion</a>
                    <a href="?view=voice_sms" class="list-group-item <?php echo $page=='voice_sms'?'active-menu':''; ?>"><i class="fas fa-envelope"></i> Voice / SMS</a>
                    
                    <!-- SETTINGS SUB-MENUS -->
                    <a href="?view=holidays" class="list-group-item <?php echo $page=='holidays' || $page=='offdays'?'active-menu':''; ?>"><i class="fas fa-cog"></i> Settings</a>
                    <?php if($page=='holidays' || $page=='offdays') { ?>
                        <a href="?view=holidays" class="list-group-item sub-menu <?php echo $page=='holidays'?'text-primary font-weight-bold':''; ?>"><i class="fas fa-umbrella-beach"></i> Holidays</a>
                        <a href="?view=offdays" class="list-group-item sub-menu <?php echo $page=='offdays'?'text-primary font-weight-bold':''; ?>"><i class="fas fa-calendar-times"></i> Class Off Days</a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- MIRGA IRRATTI: DYNAMIC MAIN CONTENT -->
        <div class="col-md-10">
            
            <!-- 1. DASHBOARD -->
            <?php if($page == 'dashboard') { ?>
                <div class="card card-custom">
                    <div class="card-header card-header-custom"><i class="fas fa-tachometer-alt"></i> Dashboard</div>
                    <div class="card-body">
                        <h5>Baga Gammadde!</h5>
                        <p>Sirna bulchiinsa mana barumsaa ICTVision keessatti milkiidhaan seenteetta. Menu bitaa jiru cuqaasuun hojii kee fufi.</p>
                    </div>
                </div>
            <?php } ?>

            <!-- 2. CLASS LIST -->
            <?php if($page == 'class_list') { ?>
                <div class="card card-custom">
                    <div class="card-header card-header-custom"><i class="fas fa-home"></i> Class List</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm table-striped">
                            <thead><tr><th>Code</th></tr></thead>
                            <tbody>
                                <?php
                                $c_res = $conn->query("SELECT * FROM classes");
                                if($c_res && $c_res->num_rows > 0) {
                                    while($c_row = $c_res->fetch_assoc()) {
                                        echo "<tr><td>" . htmlspecialchars($c_row['class_code'] ?? $c_row['id']) . "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td>cl1</td></tr><tr><td>cl10</td></tr><tr><td>cl2</td></tr><tr><td>cl3</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>

            <!-- 3. STUDENT LIST -->
            <?php if($page == 'student_list') { ?>
                <div class="card card-custom">
                    <div class="card-header card-header-custom"><i class="fas fa-user"></i> Student List</div>
                    <div class="card-body">
                        <table class="table table-bordered table-sm table-striped">
                            <thead><tr><th>Roll No</th><th>Class</th><th>Name</th><th>Gender</th><th>Religion</th><th>Action</th></tr></thead>
                            <tbody>
                                <?php
                                $res = $conn->query("SELECT * FROM students");
                                if($res && $res->num_rows > 0) {
                                    while($row = $res->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
