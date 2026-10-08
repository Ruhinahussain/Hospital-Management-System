
<?php
require_once 'auth.php';
require_once "db.php";

// Add a new patient
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $dob = $_POST["dob"];
    $gender = $_POST["gender"];
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $email = trim($_POST["email"]);

    $stmt = $conn->prepare(
        "INSERT INTO patient (Name, DOB, Gender, Phone, Address, Email)
         VALUES (?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssssss",
        $name, $dob, $gender, $phone, $address, $email
    );

    if ($stmt->execute()) {
        $message = "Patient registered successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch patients
$result = $conn->query("SELECT * FROM patient ORDER BY Patient_ID DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Management</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 25px;
            color: #243247;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            color: #087f8c;
        }

        form, .table-box {
            background: white;
            padding: 22px;
            border-radius: 12px;
            margin: 20px 0;
            box-shadow: 0 3px 12px #00000010;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        input, select, textarea {
            box-sizing: border-box;
            width: 100%;
            padding: 11px;
            border: 1px solid #ccd5df;
            border-radius: 6px;
        }

        button {
            margin-top: 18px;
            background: #087f8c;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 6px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #087f8c;
            color: white;
        }

        .message {
            color: #087f45;
            font-weight: bold;
        }

        .back {
            color: #087f8c;
        }

        @media (max-width: 600px) {
            .grid {
                grid-template-columns: 1fr;
            }

            .table-box {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <a href="dashboard.php">← Back to Dashboard</a>
    <h1>Patient Management</h1>
    <p>Register and manage hospital patient information.</p>

    <?php if (isset($message)): ?>
        <p class="message">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <h2>Register New Patient</h2>

        <div class="grid">
            <div>
                <label>Full Name</label>
                <input type="text" name="name" required maxlength="100">
            </div>

            <div>
                <label>Date of Birth</label>
                <input type="date" name="dob" required>
            </div>

            <div>
                <label>Gender</label>
                <select name="gender" required>
                    <option value="">Select gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div>
                <label>Phone</label>
                <input type="tel" name="phone" maxlength="15">
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email" maxlength="100">
            </div>

            <div>
                <label>Address</label>
                <textarea name="address" maxlength="255"></textarea>
            </div>
        </div>

        <button type="submit">Register Patient</button>
    </form>

    <div class="table-box">
        <h2>Registered Patients</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>DOB</th>
                    <th>Gender</th>
                    <th>Phone</th>
                    <th>Email</th>
                </tr>
            </thead>

            <tbody>
            <?php if ($result): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars((string)$row["Patient_ID"]) ?></td>
                        <td><?= htmlspecialchars($row["Name"]) ?></td>
                        <td><?= htmlspecialchars($row["DOB"]) ?></td>
                        <td><?= htmlspecialchars($row["Gender"]) ?></td>
                        <td><?= htmlspecialchars($row["Phone"] ?? "") ?></td>
                        <td><?= htmlspecialchars($row["Email"] ?? "") ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
