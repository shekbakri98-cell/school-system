<!DOCTYPE html>
<html lang="om">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ICTVision School System</title>
    <!-- Bootstrap 5 CSS linkii -->
    <link href="https://jsdelivr.net" rel="stylesheet"/>
    <!-- FontAwesome Icons for Sidebar -->
    <link href="https://cloudflare.com" rel="stylesheet"/>
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar-custom { background-color: #1d8ecd; color: white; padding: 12px 20px; }
        .sidebar { background-color: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); padding: 15px; }
        .sidebar .menu-header { font-size: 11px; font-weight: bold; color: #a0a0a0; text-transform: uppercase; margin-bottom: 10px; }
        .sidebar .nav-link { color: #555; padding: 10px 15px; border-radius: 4px; margin-bottom: 4px; font-size: 14px; font-weight: 500; display: flex; align-items: center; text-decoration: none; }
        .sidebar .nav-link i { margin-right: 12px; width: 20px; text-align: center; color: #1d8ecd; }
        .sidebar .nav-link:hover { background-color: #f0f7fc; color: #1d8ecd; }
        .sidebar .nav-link.active { background-color: #1d8ecd; color: white; }
        .sidebar .nav-link.active i { color: white; }
        .form-container { background-color: white; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.08); padding: 30px; }
        .form-section-title { color: #1d8ecd; font-size: 16px; font-weight: 600; border-bottom: 1px solid #eef2f5; padding-bottom: 8px; margin-top: 25px; margin-bottom: 15px; }
        .form-section-title:first-of-type { margin-top: 0; }
        .form-label { font-weight: 500; color: #444; font-size: 14px; }
        .footer-text { font-size: 12px; color: #777; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>

    <!-- Gubaarra / Navbar -->
    <nav class="navbar navbar-custom d-flex justify-content-between align-items-center">
        <span class="fw-bold fs-5">ICTVision School System</span>
        <div class="fs-6">
            <i class="fa fa-user-circle"></i> <span class="fw-bold">anewar_admin</span> | <a href="#" class="text-white text-decoration-none">Bahi (Logout)</a>
        </div>
    </nav>

    <div class="container-fluid my-4">
        <div class="row g-4">
            
            <!-- Tarree Harka Bitaa / Sidebar Navigation -->
            <div class="col-md-3 col-lg-2">
                <div class="sidebar">
                    <div class="menu-header">MAIN</div>
                    <nav class="nav flex-column">
                        <a class="nav-link" href="#"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-school"></i> Class</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-layer-group"></i> Section</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-book"></i> Subject</a>
                        <a class="nav-link active" href="#"><i class="fa-solid fa-user-graduate"></i> Student Form</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-list-ol"></i> Student List</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-chalkboard-teacher"></i> Teacher</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-calendar-check"></i> Attendance</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-file-pen"></i> Exams</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-list-check"></i> Mark Manage</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-poll"></i> Result</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-arrow-up-right-from-square"></i> Promotion</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-comment-sms"></i> Voice / SMS</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-sliders"></i> Settings</a>
                    </nav>
                </div>
            </div>

            <!-- Unka Galmeessaa / Form Section -->
            <div class="col-md-9 col-lg-10">
                <div class="form-container">
                    <h3 class="fw-bold mb-4" style="color: #333;">Unka Galmeessa Barataa</h3>
                    
                    <!-- PHP Data Processing Node -->
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Data asirraa gara Database erguuf bakka qophaaye
                        echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>Milkaa'ina!</strong> Galmeessi barataa haala sirriin raawwatameera.
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                              </div>";
                    }
                    ?>

                    <form action="" method="POST">
                        
                        <!-- 1. Odeeffannoo Daree -->
                        <div class="form-section-title">Odeeffannoo Daree</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Daree:</label>
                                <select name="daree" class="form-select" required>
                                    <option value="Class - 1">Class - 1</option>
                                    <option value="Class - 2">Class - 2</option>
                                    <option value="Class - 3">Class - 3</option>
                                </select>
                            </div>
                            <div class="col-md-6">
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
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Roll No:</label>
                                <input type="text" name="roll_no" class="form-control" placeholder="Lakk. Roll Nambarii galchi" required/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Maqaa Guutuu:</label>
                                <input type="text" name="maqaa_guutuu" class="form-control" placeholder="Maqaa Guutuu Barataa" required/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-block">Saala:</label>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="radio" name="saala" id="dhiira" value="Dhiira" checked/>
                                    <label class="form-check-label" for="dhiira">Dhiira</label>
                                </div>
                                <div class="form-check form-check-inline mt-2">
                                    <input class="form-check-input" type="radio" name="saala" id="dubara" value="Dubara"/>
                                    <label class="form-check-label" for="dubara">Dubara</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Amantii:</label>
                                <input type="text" name="amantii" class="form-control" placeholder="Amantii galchi"/>
                            </div>
                        </div>

                        <!-- 3. Teessoo -->
                        <div class="form-section-title">Teessoo</div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Bilbila Abbaa:</label>
                                <input type="tel" name="bilbila_abbaa" class="form-control" placeholder="09xxxxxxxx" required/>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Bilbila Haadha:</label>
                                <input type="tel" name="bilbila_haadha" class="form-control" placeholder="09xxxxxxxx"/>
                            </div>
                        </div>

                        <!-- Buttonii Erguu (Submit) -->
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4 py-2" style="background-color: #1d8ecd; border-color: #1d8ecd;">
                                <i class="fa fa-save"></i> Galmeessi
                            </button>
                        </div>
                    </form>
                </div>
                
                <p class="footer-text">ICTVision School System ©2017 - 2026</p>
            </div>

        </div>
    </div>

    <!-- Bootstrap 5 JavaScript Bundle Linkii -->
