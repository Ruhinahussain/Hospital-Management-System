<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ruhina Hospital | Hospital Management System</title>


    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }


        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
            background: #f8fafc;
            line-height: 1.6;
        }


        /* ================= NAVBAR ================= */

        nav {

            position: fixed;

            top: 0;
            left: 0;

            width: 100%;

            background: white;

            display: flex;


            justify-content: space-between;

            align-items: center;

            padding: 18px 7%;

            box-shadow: 0 2px 10px rgba(0,0,0,0.08);

            z-index: 9999;
        }


        .logo {
    display: flex;
    align-items: center;
}

.logo img {
    width: 150px;
    height: 55px;
    object-fit: contain;
}


        .logo span {

            color: #2563eb;
        }


        nav ul {

            list-style: none;

            display: flex;

            align-items: center;

            gap: 30px;
        }


        nav ul li {

            list-style: none;
        }


        nav ul li a {

            text-decoration: none;

            color: #374151;

            font-weight: 600;

            transition: 0.3s;
        }


        nav ul li a:hover {

            color: #2563eb;
        }


        /* ADMIN LOGIN BUTTON */

        .login-btn {

            background: #2563eb;

            color: white !important;

            padding: 10px 20px;

            border-radius: 25px;

            display: inline-block;
        }


        .login-btn:hover {

            background: #1d4ed8;
        }


        /* ================= HERO ================= */

     .hero {
    min-height: 680px;
    display: flex;
    align-items: center;
    padding: 110px 7% 70px;
    background: linear-gradient(
        135deg,
        #f4fbff 0%,
        #eaf7ff 55%,
        #dff5f5 100%
    );
    color: #12345b;
    position: relative;
    overflow: hidden;
}
.hero::after {
    content: "";
    position: absolute;
    right: 0;
    top: 50%;
    transform: translateY(-45%);

    width: 52%;
    height: 90%;

    background-image: url("images/landing-medical.png");
    background-repeat: no-repeat;
    background-position: center right;
    background-size: contain;

    pointer-events: none;
    z-index: 1;
}
  .hero-content {
    width: 52%;
    max-width: 650px;
    position: relative;
    z-index: 3;
    text-align: left;
}

.hero-logo {
    margin-bottom: 25px;
}

.hero-logo img {
    width: 220px;
    height: auto;
    object-fit: contain;
}


       .hero h1 {
    font-size: 55px;
    line-height: 1.15;
    margin-bottom: 22px;
    color: #12345b;
}

.hero h1 span {
    color: #159b9b;
}

        .hero p {
    font-size: 19px;
    line-height: 1.6;
    margin-bottom: 35px;
    color: #52677d;
    max-width: 600px;
}


        .hero-buttons {

            display: flex;

            justify-content: center;

            gap: 15px;

            flex-wrap: wrap;
        }


        .btn {

            display: inline-block;

            text-decoration: none;

            padding: 13px 28px;

            border-radius: 30px;

            font-weight: bold;

            transition: 0.3s;
        }


        .btn:hover {

            transform: translateY(-3px);
        }


        .primary-btn {
    background: #2563eb;
    color: white;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.20);
}

        .primary-btn:hover {
    background: #1d4ed8;
    color: white;
}


       .secondary-btn {
    border: 2px solid #2563eb;
    color: #2563eb;
}


        .secondary-btn:hover {
    background: #2563eb;
    color: white;
}


        /* ================= GENERAL ================= */

        section {

            padding: 90px 7%;
        }


    .section-title {
    text-align: center;
    margin-bottom: 50px;
    position: relative;
    z-index: 1;
}

        .section-title h2 {

            font-size: 36px;

            color: #111827;

            margin-bottom: 10px;
        }


        .section-title p {

            color: #6b7280;

            max-width: 700px;

            margin: auto;
        }


        /* ================= ABOUT ================= */

      .about {
    background: #f4faff;
    position: relative;
    overflow: hidden;
}
.about::before {
    content: "+";
    position: absolute;
    left: 5%;
    top: 10%;
    font-size: 220px;
    font-weight: bold;
    color: rgba(37, 99, 235, 0.08);
    z-index: 0;
    pointer-events: none;
}   
.about::after {
    content: "✚";
    position: absolute;
    right: 8%;
    bottom: 5%;
    font-size: 150px;
    font-weight: bold;
    color: rgba(15, 118, 110, 0.08);
    z-index: 0;
    pointer-events: none;
}
  .about-container {
    max-width: 1000px;
    margin: auto;
    text-align: center;
    position: relative;
    z-index: 1;
}


        .about-container p {

            font-size: 18px;

            color: #4b5563;
        }


        /* ================= MODULES ================= */

      .modules {
    background: #eaf6ff;
    position: relative;
    overflow: hidden;
}
.modules::before {
    content: "✚";
    position: absolute;
    left: 4%;
    top: 15%;
    font-size: 180px;
    font-weight: bold;
    color: rgba(37, 99, 235, 0.14);
    pointer-events: none;
}

.modules::after {
    content: "✚";
    position: absolute;
    right: 5%;
    bottom: 10%;
    font-size: 140px;
    font-weight: bold;
   color: rgba(15, 118, 110, 0.14);
    pointer-events: none;
}

        .module-grid {
    display: grid;
    grid-template-columns: repeat(3,1fr);
    gap: 25px;
    max-width: 1100px;
    margin: auto;
    position: relative;
    z-index: 1;
}

      .module-card {
    background: rgba(255, 255, 255, 0.28);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    border: 1px solid rgba(255, 255, 255, 0.55);
    padding: 35px 25px;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
    transition: 0.3s;
}

        .module-card:hover {

            transform: translateY(-8px);

            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }


        .module-icon {

            font-size: 42px;

            margin-bottom: 15px;
        }


        .module-card h3 {

            margin-bottom: 10px;

            color: #111827;
        }


        .module-card p {

            color: #6b7280;
        }


        /* ================= FEATURES ================= */
.features {
    background: rgba(239, 248, 255, 0.75);
}


        .feature-grid {

            display: grid;

            grid-template-columns: repeat(3,1fr);

            gap: 25px;

            max-width: 1000px;

            margin: auto;
        }


        .feature {

            text-align: center;

            padding: 25px;
        }


        .feature h3 {

            margin: 15px 0 10px;
        }


        .feature p {

            color: #6b7280;
        }


        /* ================= HOW IT WORKS ================= */

      .how-it-works {
    background: rgba(232, 247, 255, 0.85);
}

        .steps {

            display: grid;

            grid-template-columns: repeat(4,1fr);

            gap: 20px;

            max-width: 1100px;

            margin: auto;
        }


        .step {

            background: white;

            padding: 30px 20px;

            text-align: center;

            border-radius: 15px;

            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }


        .step-number {

            width: 45px;

            height: 45px;

            background: #2563eb;

            color: white;

            border-radius: 50%;

            display: flex;

            justify-content: center;

            align-items: center;

            margin: auto;

            font-weight: bold;

            font-size: 20px;
        }


        .step h3 {

            margin: 15px 0 10px;
        }


        /* ================= ADMIN SECTION ================= */

        .admin-section {

            background:
                linear-gradient(
                    135deg,
                    #0f766e,
                    #2563eb
                );

            color: white;

            text-align: center;
        }


        .admin-section h2 {

            font-size: 38px;

            margin-bottom: 15px;
        }


        .admin-section p {

            margin-bottom: 25px;

            font-size: 18px;
        }


        /* ================= FOOTER ================= */

        footer {

            background: #111827;

            color: white;

            text-align: center;

            padding: 30px 7%;
        }


        footer p {

            color: #d1d5db;

            margin: 5px;
        }


        /* ================= RESPONSIVE ================= */

        @media (max-width: 900px) {

            nav ul {

                gap: 12px;
            }


            .module-grid,
            .feature-grid {

                grid-template-columns: repeat(2,1fr);
            }


            .steps {

                grid-template-columns: repeat(2,1fr);
            }


            .hero h1 {

                font-size: 42px;
            }
        }


        @media (max-width: 600px) {

            nav {

                flex-direction: column;

                gap: 15px;
            }


            nav ul {

                flex-wrap: wrap;

                justify-content: center;

                gap: 15px;
            }


            .module-grid,
            .feature-grid,
            .steps {

                grid-template-columns: 1fr;
            }


            .hero {

                padding-top: 170px;
            }


            .hero h1 {

                font-size: 34px;
            }


            .hero p {

                font-size: 17px;
            }


            .section-title h2 {

                font-size: 30px;
            }
        }

    </style>

</head>


<body>


<!-- ================= NAVIGATION ================= -->

<nav>

    <div class="logo">
    <img src="images/hospital-logo.png" alt="Ruhina Hospital Logo">
</div>


    <ul>

        <li>
            <a href="#home">
                Home
            </a>
        </li>


        <li>
            <a href="#about">
                About
            </a>
        </li>


        <li>
            <a href="#modules">
                Modules
            </a>
        </li>


        <li>
            <a href="#features">
                Features
            </a>
        </li>


        <li>
            <a href="login.php" class="login-btn">
                Admin Login
            </a>
        </li>

    </ul>

</nav>



<!-- ================= HERO ================= -->

<section class="hero" id="home">

    <div class="hero-content">

        <div class="hero-logo">
            <img src="images/hospital-logo.png" alt="Ruhina Hospital Logo">
        </div>

        <h1>
            Smart <span>Hospital</span>
            <br>
            Management System
        </h1>

        <p>

            A centralized web-based system for managing
            hospital records efficiently and securely.

        </p>


        <div class="hero-buttons">

            <a href="login.php" class="btn primary-btn">

                Admin Login

            </a>


            <a href="#about" class="btn secondary-btn">

                Explore System

            </a>

        </div>

    </div>

</section>



<!-- ================= ABOUT ================= -->

<section class="about" id="about">

    <div class="section-title">

        <h2>
            About the System
        </h2>

        <p>
            Hospital Management System
        </p>

    </div>


    <div class="about-container">

        <p>

            The Hospital Management System is a
            web-based application developed to organize
            important hospital administrative records
            through a centralized database.

        </p>


        <br>


        <p>

            The system provides modules for managing
            patients, doctors, appointments,
            prescriptions, medicines and billing
            information.

        </p>

    </div>

</section>



<!-- ================= MODULES ================= -->

<section class="modules" id="modules">

    <div class="section-title">

        <h2>
            Our Modules
        </h2>

        <p>

            The system provides different modules
            for managing essential hospital records.

        </p>

    </div>


    <div class="module-grid">


        <div class="module-card">

            <div class="module-icon">
                👨‍⚕️
            </div>

            <h3>
                Patient Management
            </h3>

            <p>
                Maintain and manage patient
                information in an organized database.
            </p>

        </div>


        <div class="module-card">

            <div class="module-icon">
                🩺
            </div>

            <h3>
                Doctor Management
            </h3>

            <p>
                Store doctor information including
                specialization and department.
            </p>

        </div>


        <div class="module-card">

            <div class="module-icon">
                📅
            </div>

            <h3>
                Appointments
            </h3>

            <p>
                Manage appointment details,
                dates, times and status.
            </p>

        </div>


        <div class="module-card">

            <div class="module-icon">
                💊
            </div>

            <h3>
                Prescriptions
            </h3>

            <p>
                Maintain prescription information
                associated with appointments.
            </p>

        </div>


        <div class="module-card">

            <div class="module-icon">
                💉
            </div>

            <h3>
                Medicines
            </h3>

            <p>
                Manage medicine names,
                prices and available stock.
            </p>

        </div>


        <div class="module-card">

            <div class="module-icon">
                🧾
            </div>

            <h3>
                Billing
            </h3>

            <p>
                Maintain billing records,
                amounts and payment status.
            </p>

        </div>


    </div>

</section>



<!-- ================= FEATURES ================= -->

<section class="features" id="features">

    <div class="section-title">

        <h2>
            Key Features
        </h2>

        <p>
            Designed to make hospital record
            management simple and organized.
        </p>

    </div>


    <div class="feature-grid">


        <div class="feature">

            <div class="module-icon">
                🔐
            </div>

            <h3>
                Secure Access
            </h3>

            <p>
                Administrator authentication protects
                the management pages.
            </p>

        </div>


        <div class="feature">

            <div class="module-icon">
                🗄️
            </div>

            <h3>
                Centralized Database
            </h3>

            <p>
                Hospital records are stored in
                a structured relational database.
            </p>

        </div>


        <div class="feature">

            <div class="module-icon">
                ⚡
            </div>

            <h3>
                Easy Management
            </h3>

            <p>
                Manage different hospital records
                through dedicated modules.
            </p>

        </div>


        <div class="feature">

            <div class="module-icon">
                🔗
            </div>

            <h3>
                Related Records
            </h3>

            <p>
                Database relationships connect
                patients, doctors and appointments.
            </p>

        </div>


        <div class="feature">

            <div class="module-icon">
                📊
            </div>

            <h3>
                Organized Information
            </h3>

            <p>
                Information is separated into
                related database tables.
            </p>

        </div>


        <div class="feature">

            <div class="module-icon">
                🌐
            </div>

            <h3>
                Web Based
            </h3>

            <p>
                The system can be accessed through
                a modern web browser.
            </p>

        </div>


    </div>

</section>



<!-- ================= HOW IT WORKS ================= -->

<section class="how-it-works">

    <div class="section-title">

        <h2>
            How It Works
        </h2>

        <p>
            Simple workflow for authorized administrators.
        </p>

    </div>


    <div class="steps">


        <div class="step">

            <div class="step-number">
                1
            </div>

            <h3>
                Login
            </h3>

            <p>
                Administrator logs into the system.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                2
            </div>

            <h3>
                Manage
            </h3>

            <p>
                Access the required hospital module.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                3
            </div>

            <h3>
                Store
            </h3>

            <p>
                Records are stored in the database.
            </p>

        </div>


        <div class="step">

            <div class="step-number">
                4
            </div>

            <h3>
                Track
            </h3>

            <p>
                View and manage stored information.
            </p>

        </div>


    </div>

</section>



<!-- ================= ADMIN ACCESS ================= -->

<section class="admin-section">

    <h2>
        Administrator Access
    </h2>


    <p>
        Login to manage hospital records.
    </p>


    <a href="login.php" class="btn primary-btn">

        Login Now

    </a>

</section>



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