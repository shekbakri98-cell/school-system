<?php
// 1. Walitti hidhamiinsa uumuu (Odeeffannoo kee isa sirrii)
$servername = "mysql-anewar.alwaysdata.net"; 
$username = "anewar_admin"; 
$password = "015661Emran@";      
$dbname = "anewar_school_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kuusaa odeeffannoo waliin walitti hidhuun hin danda'amne: " . $conn->connect_error);
}

// Ergaawwan agarsiisaniif (Notification messages)
$msg = "";
$msg_type = "";

// 2. Barataa Galmeessuu (Process Student Form Submission)
if (isset($_POST['submit_student'])) {
    $daree = $conn->real_escape_string($_POST['daree']);
    $kutaa = $conn->real_escape_string($_POST['kutaa']);
    $roll_no = $conn->real_escape_string($_POST['roll_no']);
    $maqaa_guutuu = $conn->real_escape_string($_POST['maqaa_guutuu']);
    $saala = $conn->real_escape_string($_POST['saala']);
    $amantii = $conn->real_escape_string($_POST['amantii']);
    $bilbila_abbaa = $conn->real_escape_string($_POST['bilbila_abbaa']);
    $bilbila_haadha = $conn->real_escape_string($_POST['bilbila_haadha']);

    $sql = "INSERT INTO students (daree, kutaa, roll_no, maqaa_guutuu, saala, amantii, bilbila_abbaa, bilbila_haadha) 
            VALUES ('$daree', '$kutaa', '$roll_no', '$maqaa_guutuu', '$saala', '$amantii', '$bilbila_abbaa', '$bilbila_haadha')";
    
    if ($conn->query($sql) === TRUE) {
        $msg = "Milkaa'ina! Barataan haala sirriin galmeeffameera.";
        $msg_type = "success";
    } else {
        $msg = "Dogoggorri uumame: " . $conn->error;
        $msg_type = "danger";
    }
}

// 3. Barsiisaa Galmeessuu (Process Teacher Form Submission)
if (isset($_POST['submit_teacher'])) {
    $maqaa_barsiisaa = $conn->real_escape_string($_POST['maqaa_barsiisaa']);
    $saala = $conn->real_escape_string($_POST['saala']);
    $gosa_barnootaa = $conn->real_escape_string($_POST['gosa_barnootaa']);
    $bilbila = $conn->real_escape_string($_POST['bilbila']);
    $id_nambarii = $conn->real_escape_string($_POST['id_nambarii']);
    $teessoo = $conn->real_escape_string($_POST['teessoo']);

    $sql = "INSERT INTO teachers (maqaa_barsiisaa, saala, gosa_barnootaa, bilbila, id_nambarii, teessoo) 
            VALUES ('$maqaa_barsiisaa', '$saala', '$gosa_barnootaa', '$bilbila', '$id_nambarii', '$teessoo')";
    
    if ($conn->query($sql) === TRUE) {
        $msg = "Milkaa'ina! Barsiisaan haala sirriin galmeeffameera.";
        $msg_type = "success";
    } else {
        $msg = "Dogoggorri uumame: " . $conn->error;
        $msg_type = "danger";
    }
}

// Fuula kamiin akka banamu to'achuuf (Navigation Tab)
$page = isset($_GET['page']) ? $_GET['page'] : 'student_form';
?>
<!DOCTYPE html>
<html lang="om">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ICTVision School System</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; }
        
        /* Gubaarra / Navbar */
        .navbar-custom { background-color: #1d8ecd; color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .navbar-custom .brand { font-size: 20px; font-weight: bold; }
        .navbar-custom .user-info { font-size: 14px; }
        .navbar-custom a { color: white; text-decoration: none; margin-left: 5px; font-weight: bold; }
        .navbar-custom a:hover { text-decoration: underline; }

        /* Caasaa Gidduu / Main Layout Grid */
        .main-container { display: flex; max-width: 100%; margin: 20px; gap: 20px; }
        
        /* Tarree Harka Bitaa / Sidebar CSS Fixed width */
        .sidebar { width: 250px; background-color: white; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 15px; flex-shrink: 0; }
        .sidebar .menu-header { font-size: 11px; font-weight: bold; color: #a0a0a0; text-transform: uppercase; margin-bottom: 15px; padding-left: 10px; }
        .sidebar .nav-link { color: #555; padding: 12px 15px; border-radius: 4px; margin-bottom: 5px; font-size: 14px; font-weight: 500; display: block; text-decoration: none; transition: all 0.2s ease; }
        .sidebar .nav-link:hover { background-color: #f0f7fc; color: #1d8ecd; }
        .sidebar .nav-link.active { background-color: #1d8ecd; color: white; font-weight: bold; }

        /* Unka Galmeessaa / Form Main Body */
        .content-body { flex-grow: 1; background-color: white; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); padding: 30px; }
        .content-body h3 { font-size: 22px; font-weight: bold; margin-bottom: 25px; border-bottom: 2px solid #f4f6f9; padding-bottom: 10px; }
        
        /* Form row configuration */
        .form-section-title { color: #1d8ecd; font-size: 16px; font-weight: 600; border-bottom: 1px solid #eef2f5; padding-bottom: 8px; margin-top: 25px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-section-title:first-of-type { margin-top: 0; }
        
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 15px; }
        .form-group { display: flex; flex-direction: column; }
        .form-label { font-weight: 500; color: #444; font-size: 14px; margin-bottom: 8px; }
        
        .form-control, .form-select { width: 100%; padding: 10px; border: 1px solid #cccccc; border-radius: 4px; font-size: 14px; background-color: #fff; }
        .form-control:focus, .form-select:focus { border-color: #1d8ecd; outline: none; box-shadow: 0 0 5px rgba(29, 142, 205, 0.3); }
        
        /* Radio Buttons */
        .radio-group { display: flex; gap: 20px; align-items: center; margin-top: 10px; }
        .radio-item { display: flex; align-items: center; gap: 5px; font-size: 14px; }
        
        /* Submit Button */
        .btn-submit { background-color: #1d8ecd; color: white; border: none; padding: 12px 30px; border-radius: 4px; font-size: 15px; font-weight: bold; cursor: pointer; display: inline-block; float: right; margin-top: 20px; transition: background 0.2s; }
        .btn-submit:hover { background-color: #157cb5; }
        
        .clear { clear: both; }
        .footer-text { font-size: 12px; color: #777; margin-top: 40px; text-align: center; }

        /* Responsive Layout for Mobile Screens */
        @media (max-width: 768px) {
            .main-container { flex-direction: column; }
            .sidebar { width: 100%; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- Gubaarra / Navbar -->
    <div class="navbar-custom">
        <div class="brand">ICTVision School System</div>
        <div class="user-info">
            <strong>anewar_admin</strong> | <a href="#">Bahi (Logout)</a>
        </div>
    </div>

    <!-- Caasaa Gidduu / Main Section -->
    <div class="main-container">
        
        <!-- Tarree Harka Bitaa / Sidebar Navigation -->
        <div class="sidebar">
            <div class="menu-header">MAIN</div>
            <a class="nav-link" href="#">Dashboard</a>
            <a class="nav-link" href="#">Class</a>
            <a class="nav-link" href="#">Section</a>
            <a class="nav-link" href="#">Subject</a>
            <a class="nav-link active" href="#">Student Form</a>
            <a class="nav-link" href="#">Student List</a>
            <a class="nav-link" href="#">Teacher</a>
            <a class="nav-link" href="#">Attendance</a>
            <a class="nav-link" href="#">Exams</a>
            <a class="nav-link" href="#">Mark Manage</a>
            <a class="nav-link" href="#">Result</a>
            <a class="nav-link" href="#">Promotion</a>
            <a class="nav-link" href="#">Voice / SMS</a>
            <a class="nav-link" href="#">Settings</a>
        </div>

        <!-- Unka Galmeessaa / Form Section -->
        <div class="content-body">
            <h3>Unka Galmeessa Barataa</h3>

            <form action="" method="POST">
                
                <!-- 1. Odeeffannoo Daree -->
                <div class="form-section-title">Odeeffannoo Daree</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Daree:</label>
                        <select name="daree" class="form-select" required>
                            <option value="Class - 1">Class - 1</option>
                            <option value="Class - 2">Class - 2</option>
                            <option value="Class - 3">Class - 3</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kutaa:</label>
                        <select name="kutaa" class="form-select" required>
                            <option value="Blue (25)">Blue (25)</option>
                            <option value="Red (20)">Red (20)</option>
                            <option value="Green (30)">Green (30)</option>
                        </select>
                    </div>
                </div>

                <!-- 2. Odeeffannoo Barataa -->
                <div class="form-section-title">Odeeffannoo Barataa</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Roll No:</label>
                        <input type="text" name="roll_no" class="form-control" placeholder="Lakk. Roll Nambarii galchi" required/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Maqaa Guutuu:</label>
                        <input type="text" name="maqaa_guutuu" class="form-control" placeholder="Maqaa Guutuu Barataa" required/>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Saala:</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" name="saala" id="dhiira" value="Dhiira" checked/>
                                <label for="dhiira">Dhiira</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" name="saala" id="dubara" value="Dubara"/>
                                <label for="dubara">Dubara</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Amantii:</label>
                        <input type="text" name="amantii" class="form-control" placeholder="Amantii galchi"/>
                    </div>
                </div>

                <!-- 3. Teessoo -->
                <div class="form-section-title">Teessoo</div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Bilbila Abbaa:</label>
                        <input type="tel" name="bilbila_abbaa" class="form-control" placeholder="09xxxxxxxx" required/>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Bilbila Haadha:</label>
                        <input type="tel" name="bilbila_haadha" class="form-control" placeholder="09xxxxxxxx"/>
                    </div>
                </div>

                <!-- Buttonii Erguu (Submit) -->
                <button type="submit" class="btn-submit">Galmeessi</button>
                <div class="clear"></div>
            </form>
            
            <p class="footer-text">ICTVision School System ©2017 - 2026</p>
        </div>

    </div>

</body>
</html>
