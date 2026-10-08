
<?php
require_once 'auth.php';
include 'db.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = (int)($_POST['patient_id'] ?? 0);
    $doctor_id = (int)($_POST['doctor_id'] ?? 0);
    $date = $_POST['appointment_date'] ?? '';
    $time = $_POST['appointment_time'] ?? '';
    $reason = trim($_POST['reason'] ?? '');
    $status = 'Pending';

    if ($patient_id <= 0 || $doctor_id <= 0 ||
        $date === '' || $time === '') {
        $message = 'Please fill in all required fields.';
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO appointment
            (Patient_ID, Doctor_ID, Appointment_Date,
             Appointment_Time, Reason, Status)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "iissss",
            $patient_id,
            $doctor_id,
            $date,
            $time,
            $reason,
            $status
        );

        if ($stmt->execute()) {
            $message = 'Appointment booked successfully!';
        } else {
            $message = 'Error: ' . $stmt->error;
        }

        $stmt->close();
    }
}

$patients = $conn->query(
    "SELECT Patient_ID, Name FROM patient ORDER BY Name"
);

$doctors = $conn->query(
    "SELECT Doctor_ID, Name, Specialization
     FROM doctor ORDER BY Name"
);

$appointments = $conn->query(
    "SELECT a.Appointment_ID, p.Name AS Patient_Name,
            d.Name AS Doctor_Name, a.Appointment_Date,
            a.Appointment_Time, a.Reason, a.Status
     FROM appointment a
     JOIN patient p ON a.Patient_ID = p.Patient_ID
     JOIN doctor d ON a.Doctor_ID = d.Doctor_ID
     ORDER BY a.Appointment_Date DESC, a.Appointment_Time DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Appointment Management</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #eef3f8;
    color: #243247;
    margin: 0;
    padding: 25px;
}
.container {
    max-width: 1150px;
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
    gap: 16px;
}
label { font-weight: bold; }
input, select, textarea {
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
    padding: 12px 18px;
    border-radius: 5px;
    cursor: pointer;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    padding: 11px;
    border: 1px solid #ddd;
    text-align: left;
}
th { background: #087f8c; color: white; }
.message { color: #087f50; font-weight: bold; }
.table-wrap { overflow-x: auto; }
@media (max-width: 650px) {
    form { grid-template-columns: 1fr; }
}
</style>
</head>

<body>
<div class="container">

<a href="dashboard.php">← Back to Dashboard</a>
<h1>Appointment Management</h1>
<p>Book and manage hospital appointments.</p>

<div class="card">
    <h2>Book New Appointment</h2>

    <?php if ($message !== ''): ?>
        <p class="message">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST">
        <div>
            <label>Select Patient *</label>
            <select name="patient_id" required>
                <option value="">Choose Patient</option>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= (int)$p['Patient_ID'] ?>">
                        <?= htmlspecialchars($p['Name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label>Select Doctor *</label>
            <select name="doctor_id" required>
                <option value="">Choose Doctor</option>
                <?php while ($d = $doctors->fetch_assoc()): ?>
                    <option value="<?= (int)$d['Doctor_ID'] ?>">
                        <?= htmlspecialchars($d['Name']) ?>
                        — <?= htmlspecialchars($d['Specialization']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div>
            <label>Appointment Date *</label>
            <input type="date" name="appointment_date" required>
        </div>

        <div>
            <label>Appointment Time *</label>
            <input type="time" name="appointment_time" required>
        </div>

        <div>
            <label>Reason for Visit</label>
            <textarea name="reason" rows="3"
                      placeholder="Describe the reason for the visit"></textarea>
        </div>

        <div style="align-self:end">
            <button type="submit">Book Appointment</button>
        </div>
    </form>
</div>

<div class="card">
    <h2>Scheduled Appointments</h2>

    <div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Time</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($appointments && $appointments->num_rows > 0): ?>
            <?php while ($a = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?= (int)$a['Appointment_ID'] ?></td>
                    <td><?= htmlspecialchars($a['Patient_Name']) ?></td>
                    <td><?= htmlspecialchars($a['Doctor_Name']) ?></td>
                    <td><?= htmlspecialchars($a['Appointment_Date']) ?></td>
                    <td><?= htmlspecialchars($a['Appointment_Time']) ?></td>
                    <td><?= htmlspecialchars($a['Reason'] ?? '') ?></td>
                    <td><?= htmlspecialchars($a['Status']) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">No appointments found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

</div>
</body>
</html>
