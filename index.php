<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include 'db_connect.php'; 

// Daataa database keessaa fiduu
$students = $conn->query("SELECT * FROM students ORDER BY id DESC");
$teachers = $conn->query("SELECT * FROM teachers ORDER BY id DESC");
$marks = $conn->query("SELECT * FROM student_marks ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="om">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICTVision School System - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- HEADER (Gubbaa) -->
    <header class="main-header">
        <div class="logo">ICTVision School System</div>
        <div class="user-profile">
            👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?> | 
            <a href="logout.php" style="color: #ffcccc; text-decoration: none; font-weight: bold; margin-left: 10px;">Bahi (Logout)</a>
        </div>
    </header>

    <div class="app-container">
        <!-- SIDEBAR (Gulaala Bitaa) -->
        <aside class="sidebar">
            <div class="menu-label">MAIN</div>
            <nav class="menu-links">
                <a href="#" class="menu-item active" onclick="showForm('student-form-section', this)">👤 Student Form</a>
                <a href="#" class="menu-item" onclick="showForm('student-list-section', this)">📋 Student List</a>
                <a href="#" class="menu-item" onclick="showForm('teacher-form-section', this)">🧑‍🏫 Teacher Form</a>
                <a href="#" class="menu-item" onclick="showForm('teacher-list-section', this)">📋 Teacher List</a>
                <a href="#" class="menu-item" onclick="showForm('marks-form-section', this)">📌 Mark Manage</a>
                <a href="#" class="menu-item" onclick="showForm('marks-list-section', this)">📊 Result List</a>
            </nav>
        </aside>

        <!-- MAIN CONTENT (Kutaa Hojii) -->
        <main class="main-content">
            
            <!-- 1. STUDENT FORM -->
            <div id="student-form-section" class="form-container page-section active">
                <h2 class="form-title">Unka Galmeessa Barataa</h2>
                <form action="process_student.php" method="POST">
                    <div class="form-section">
                        <h3>Odeeffannoo Daree</h3>
                        <div class="input-row">
                            <div class="input-group"><label>Daree:</label><select name="class" required><option value="Class - 1">Class - 1</option><option value="Class - 2">Class - 2</option></select></div>
                            <div class="input-group"><label>Kutaa:</label><select name="section" required><option value="Blue (25)">Blue (25)</option><option value="Green">Green</option></select></div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3>Odeeffannoo Barataa</h3>
                        <div class="input-row">
                            <div class="input-group"><label>Roll No:</label><input type="text" name="roll_no" required></div>
                            <div class="input-group"><label>Maqaa Guutuu:</label><input type="text" name="name" required></div>
                        </div>
                        <div class="input-row">
                            <div class="input-group"><label>Saala:</label><div class="radio-group"><input type="radio" name="gender" value="Male" required>Dhiira <input type="radio" name="gender" value="Female">Dubara</div></div>
                            <div class="input-group"><label>Amantii:</label><input type="text" name="religion"></div>
                        </div>
                    </div>
                    <div class="form-section">
                        <h3>Teessoo</h3>
                        <div class="input-row">
                            <div class="input-group"><label>Bilbila Abbaa:</label><input type="tel" name="father_contact" required></div>
                            <div class="input-group"><label>Bilbila Haadha:</label><input type="tel" name="mother_contact"></div>
                        </div>
                        <div class="input-group"><label>Address:</label><textarea name="address" rows="2" required></textarea></div>
                    </div>
                    <button type="submit" class="submit-btn">Barataa Galmeessi</button>
                </form>
            </div>

            <!-- 2. STUDENT LIST -->
            <div id="student-list-section" class="form-container page-section">
                <h2 class="form-title">Tarreeffama Barattootaa</h2>
                <div class="table-responsive">
                    <table class="student-table">
                        <thead><tr><th>Roll No</th><th>Class</th><th>Section</th><th>Name</th><th>Gender</th><th>Religion</th><th>Guardian</th><th>Address</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php while($row = $students->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['section_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo htmlspecialchars($row['religion']); ?></td>
                                <td>Abbaa: <?php echo htmlspecialchars($row['father_contact']); ?><br>Haadha: <?php echo htmlspecialchars($row['mother_contact']); ?></td>
                                <td><?php echo htmlspecialchars($row['address']); ?></td>
                                <td><div class="action-buttons"><a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn-edit">📝</a><a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Haquu mirkaneessi?')">🗑️</a></div></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 3. TEACHER FORM -->
            <div id="teacher-form-section" class="form-container page-section">
                <h2 class="form-title">Unka Galmeessa Barsiisotaa</h2>
                <form action="process_teacher.php" method="POST">
                    <div class="form-section">
                        <div class="input-row">
                            <div class="input-group"><label>Maqaa Guutuu:</label><input type="text" name="teacher_name" required></div>
                            <div class="input-group"><label>Saala:</label><div class="radio-group"><input type="radio" name="gender" value="Male" required>Dhiira <input type="radio" name="gender" value="Female">Dubara</div></div>
                        </div>
                        <div class="input-row">
                            <div class="input-group"><label>Amantii:</label><input type="text" name="religion"></div>
                            <div class="input-group"><label>Bilbila (Phone):</label><input type="tel" name="phone" required></div>
                        </div>
                        <div class="input-row">
                            <div class="input-group"><label>Guardian Phone:</label><input type="text" name="guardian_contact"></div>
                            <div class="input-group"><label>Address:</label><input type="text" name="address" required></div>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn" style="background-color:#28a745;">Barsiisaa Galmeessi</button>
                </form>
            </div>

            <!-- 4. TEACHER LIST -->
            <div id="teacher-list-section" class="form-container page-section">
                <h2 class="form-title">Tarreeffama Barsiisotaa</h2>
                <div class="table-responsive">
                    <table class="student-table">
                        <thead><tr><th>Name</th><th>Gender</th><th>Religion</th><th>Phone</th><th>Guardian</th><th>Address</th><th>Action</th></tr></thead>
                        <tbody>
                            <?php while($t = $teachers->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($t['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($t['gender']); ?></td>
                                <td><?php echo htmlspecialchars($t['religion']); ?></td>
                                <td><?php echo htmlspecialchars($t['phone']); ?></td>
                                <td><?php echo htmlspecialchars($t['guardian_contact']); ?></td>
                                <td><?php echo htmlspecialchars($t['address']); ?></td>
                                <td><div class="action-buttons"><a href="edit_teacher.php?id=<?php echo $t['id']; ?>" class="btn-edit">📝</a><a href="delete_teacher.php?id=<?php echo $t['id']; ?>" class="btn-delete" onclick="return confirm('Haquu mirkaneessi?')">🗑️</a></div></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. MARK MANAGE -->
            <div id="marks-form-section" class="form-container page-section">
                <h2 class="form-title">Galmeessa Qabxii Barataa</h2>
                <form action="process_marks.php" method="POST">
                    <div class="input-row">
function showForm(sectionId, element) {document.querySelectorAll('.page-section').forEach(sec => sec.classList.remove('active'));document.querySelectorAll('.menu-item').forEach(item => item.classList.remove('active'));document.getElementById(sectionId).classList.add('active');element.classList.add('active');}
