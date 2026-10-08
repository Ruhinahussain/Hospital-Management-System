
<?php
require_once 'auth.php';
include 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $department_id = (int)($_POST['department_id'] ?? 0);

    if ($name === '' || $specialization === '' || $phone === '') {
        $message = 'Please fill in all required fields.';
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO doctor
            (Name, Specialization, Phone, Email, Department_ID)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssssi",
            $name,
            $specialization,
            $phone,
            $email,
            $department_id
        );

        if ($stmt->execute()) {
            $message = 'Doctor registered successfully!';
        } else {
            $message = 'Error: ' . $stmt->error;
        }

        $stmt->close();
    }
}

$departments = $conn->query(
    "SELECT Department_ID, Department_Name FROM department"
);

$doctors = $conn->query(
    "SELECT d.Doctor_ID, d.Name, d.Specialization,
            d.Phone, d.Email, dep.Department_Name
     FROM doctor d
     LEFT JOIN department dep
       ON d.Department_ID = dep.Department_ID
     ORDER BY d.Doctor_ID DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Doctor Management</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #eef3f8;
    margin: 0;
    padding: 25px;
    color: #243247;
}
.container {
    max-width: 1100px;
    margin: auto;
}
h1 { color: #087f8c; }
.card {
    background: white;
    padding: 22px;
    margin: 20px 0;
    border-radius: 12px;
    box-shadow: 0 3px 12px #00000012;
}
form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}
label { font-weight: bold; }
input, select {
    box-sizing: border-box;
    width: 100%;
    padding: 11px;
    margin-top: 7px;
    border: 1px solid #ccd5df;
    border-radius: 5px;
}
button {
    background: #087f8c;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 12px 18px;
    cursor: pointer;
}
table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}
th, td {
    padding: 11px;
    border: 1px solid #ddd;
    text-align: left;
}
th { background: #087f8c; color: white; }
.message { color: #087f50; font-weight: bold; }
@media (max-width: 650px) {
    form { grid-template-columns: 1fr; }
    .table-wrap { overflow-x: auto; }
}
</style>
</head>
<body>
<div class="container">
   <a href="dashboard.php">← Back to Dashboard</a>
    <h1>Doctor Management</h1>
    <p>Register and manage hospital doctors.</p>

    <div class="card">
        <h2>Register New Doctor</h2>

        <?php if ($message !== ''): ?>
            <p class="message">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label>Doctor Name *</label>
                <input type="text" name="name" required>
            </div>

            <div>
                <label>Specialization *</label>
                <input type="text" name="specialization" required>
            </div>

            <div>
                <label>Phone *</label>
                <input type="tel" name="phone" required>
            </div>

            <div>
                <label>Email</label>
                <input type="email" name="email">
            </div>

            <div>
                <label>Department *</label>
                <select name="department_id" required>
                    <option value="">Select Department</option>
                    <?php while ($dep = $departments->fetch_assoc()): ?>
                        <option value="<?= (int)$dep['Department_ID'] ?>">
                            <?= htmlspecialchars($dep['Department_Name']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div style="align-self:end">
                <button type="submit">Register Doctor</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h2>Registered Doctors</h2>
        <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Department</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($doctors && $doctors->num_rows > 0): ?>
                <?php while ($doc = $doctors->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$doc['Doctor_ID'] ?></td>
                        <td><?= htmlspecialchars($doc['Name']) ?></td>
                        <td><?= htmlspecialchars($doc['Specialization']) ?></td>
                        <td><?= htmlspecialchars($doc['Phone']) ?></td>
                        <td><?= htmlspecialchars($doc['Email'] ?? '') ?></td>
                        <td><?= htmlspecialchars($doc['Department_Name'] ?? '') ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No doctors registered yet.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
</body>
</html>
