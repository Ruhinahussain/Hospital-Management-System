<?php
require_once 'auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard | Ruhina Hospital</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;

            background: #f1f5f9;

            color: #1f2937;

            min-height: 100vh;
        }


        /* ================= NAVBAR ================= */

        nav {

            background: white;

            padding: 18px 7%;

            display: flex;

            justify-content: space-between;

            align-items: center;

            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);

        }


        .logo {

            font-size: 24px;

            font-weight: bold;

            color: #0f766e;

        }


        .logo span {

            color: #2563eb;

        }


        .nav-right {

            display: flex;

            align-items: center;

            gap: 20px;

        }


        .welcome {

            color: #374151;

            font-weight: 600;

        }


        .logout {

            text-decoration: none;

            background: #dc2626;

            color: white;

            padding: 10px 18px;

            border-radius: 25px;

            font-weight: bold;

            transition: 0.3s;

        }


        .logout:hover {

            background: #b91c1c;

        }


        /* ================= MAIN ================= */

        .container {

            width: 90%;

            max-width: 1200px;

            margin: auto;

            padding: 60px 0;

        }


        .heading {

            text-align: center;

            margin-bottom: 45px;

        }


        .heading h1 {

            font-size: 38px;

            color: #12355b;

            margin-bottom: 12px;

        }


        .heading p {

            font-size: 17px;

            color: #475569;

        }


        /* ================= MODULE GRID ================= */

        .module-grid {

            display: grid;

            grid-template-columns: repeat(3, 1fr);

            gap: 25px;

        }


        .module-card {

            background: white;

            padding: 35px 25px;

            border-radius: 15px;

            text-align: center;

            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);

            transition: 0.3s;

        }


        .module-card:hover {

            transform: translateY(-6px);

            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);

        }


        .icon {

            font-size: 45px;

            margin-bottom: 15px;

        }


        .module-card h2 {

            color: #07838d;

            font-size: 22px;

            margin-bottom: 15px;

        }


        .module-card p {

            color: #475569;

            line-height: 1.6;

            margin-bottom: 25px;

        }


        .manage-btn {

            display: inline-block;

            text-decoration: none;

            background: #07838d;

            color: white;

            padding: 11px 18px;

            border-radius: 5px;

            font-weight: bold;

            transition: 0.3s;

        }


        .manage-btn:hover {

            background: #056b73;

            transform: translateY(-2px);

        }


        /* ================= FOOTER ================= */

        footer {

            margin-top: 40px;

            background: #111827;

            color: white;

            text-align: center;

            padding: 25px;

        }


        footer p {

            margin: 5px;

            color: #d1d5db;

        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 900px) {

            .module-grid {

                grid-template-columns: repeat(2, 1fr);

            }

        }


        @media (max-width: 600px) {

            nav {

                flex-direction: column;

                gap: 15px;

            }


            .nav-right {

                flex-direction: column;

                gap: 10px;

            }


            .module-grid {

                grid-template-columns: 1fr;

            }


            .heading h1 {

                font-size: 30px;

            }

        }

    </style>

</head>


<body>


<!-- ================= NAVBAR ================= -->

<nav>

    <div class="logo">

        🏥 Ruhina <span>Hospital</span>

    </div>


    <div class="nav-right">

        <div class="welcome">

            Welcome,
            <?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'Admin'); ?>

        </div>


        <a href="logout.php" class="logout">

            Logout

        </a>

    </div>

</nav>



<!-- ================= MAIN ================= -->

<div class="container">


    <div class="heading">

        <h1>
            Welcome to Our Hospital
        </h1>

        <p>
            Manage patients, doctors, appointments,
            prescriptions, medicines and bills in one place.
        </p>

    </div>



    <!-- ================= MODULES ================= -->

    <div class="module-grid">


        <!-- PATIENTS -->

        <div class="module-card">

            <div class="icon">
                👨‍⚕️
            </div>

            <h2>
                Patients
            </h2>

            <p>
                Register and manage patient information.
            </p>

            <a href="patients.php" class="manage-btn">

                Manage Patients

            </a>

        </div>



        <!-- DOCTORS -->

        <div class="module-card">

            <div class="icon">
                🩺
            </div>

            <h2>
                Doctors
            </h2>

            <p>
                Register doctors and manage their details.
            </p>

            <a href="doctors.php" class="manage-btn">

                Manage Doctors

            </a>

        </div>



        <!-- APPOINTMENTS -->

        <div class="module-card">

            <div class="icon">
                📅
            </div>

            <h2>
                Appointments
            </h2>

            <p>
                Book and view patient appointments.
            </p>

            <a href="appointments.php" class="manage-btn">

                Manage Appointments

            </a>

        </div>



        <!-- PRESCRIPTIONS -->

        <div class="module-card">

            <div class="icon">
                💊
            </div>

            <h2>
                Prescriptions
            </h2>

            <p>
                Create and view patient prescriptions.
            </p>

            <a href="prescriptions.php" class="manage-btn">

                Manage Prescriptions

            </a>

        </div>



        <!-- MEDICINES -->

        <div class="module-card">

            <div class="icon">
                💉
            </div>

            <h2>
                Medicines
            </h2>

            <p>
                Add medicines and manage prices and stock.
            </p>

            <a href="medicines.php" class="manage-btn">

                Manage Medicines

            </a>

        </div>



        <!-- BILLING -->

        <div class="module-card">

            <div class="icon">
                🧾
            </div>

            <h2>
                Billing
            </h2>

            <p>
                Generate bills and manage payment status.
            </p>

            <a href="billing.php" class="manage-btn">

                Manage Billing

            </a>

        </div>


    </div>

</div>



<!-- ================= FOOTER ================= -->

<footer>

    <p>
        © 2026 Ruhina Hospital Management System
    </p>

    <p>
        DBMS Mini Project |
        Department of Computer Science and Engineering
    </p>

</footer>


</body>

</html>