
<?php
require_once 'auth.php';
require_once 'db.php';

$message = '';
$messageType = 'success';

// Display success message after redirect
if (isset($_GET['success']) && $_GET['success'] === '1') {
    $message = 'Prescription saved successfully!';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $appointmentID = filter_var(
        $_POST['appointment_id'] ?? '',
        FILTER_VALIDATE_INT
    );

    $diagnosis = trim($_POST['diagnosis'] ?? '');
    $prescriptionDate = trim($_POST['prescription_date'] ?? '');

    // Validate required fields
    if (
        $appointmentID === false ||
        $appointmentID === null ||
        $appointmentID <= 0 ||
        $diagnosis === '' ||
        $prescriptionDate === ''
    ) {
        $message = 'Please fill in all fields with valid values.';
        $messageType = 'error';

    } elseif (strlen($diagnosis) > 255) {
        $message = 'Diagnosis must not exceed 255 characters.';
        $messageType = 'error';

    } else {
        // Validate date format and actual calendar date
        $dateObject = DateTime::createFromFormat(
            '!Y-m-d',
            $prescriptionDate
        );

        if (
            !$dateObject ||
            $dateObject->format('Y-m-d') !== $prescriptionDate
        ) {
            $message = 'Please enter a valid prescription date.';
            $messageType = 'error';

        } else {
            // Check whether the appointment exists
            $check = $conn->prepare(
                "SELECT Appointment_ID
                 FROM appointment
                 WHERE Appointment_ID = ?"
            );

            $check->bind_param('i', $appointmentID);
            $check->execute();
            $check->store_result();

            if ($check->num_rows === 0) {
                $message = 'Selected appointment does not exist.';
                $messageType = 'error';

            } else {
                $check->close();

                // Check whether this appointment already has a prescription
                $duplicateCheck = $conn->prepare(
                    "SELECT Prescription_ID
                     FROM prescription
                     WHERE Appointment_ID = ?"
                );

                $duplicateCheck->bind_param('i', $appointmentID);
                $duplicateCheck->execute();
                $duplicateCheck->store_result();

                if ($duplicateCheck->num_rows > 0) {
                    $message =
                        'A prescription already exists for this appointment. '
                        . 'Please select a different appointment.';

                    $messageType = 'error';

                } else {
                    // Insert prescription
                    try {
                        $insert = $conn->prepare(
                            "INSERT INTO prescription
                             (Appointment_ID, Diagnosis, Prescription_Date)
                             VALUES (?, ?, ?)"
                        );

                        $insert->bind_param(
                            'iss',
                            $appointmentID,
                            $diagnosis,
                            $prescriptionDate
                        );

                        $insert->execute();
                        $insert->close();
                        $duplicateCheck->close();

                        // Redirect to prevent resubmission on refresh
                        header('Location: prescriptions.php?success=1');
                        exit;

                    } catch (mysqli_sql_exception $e) {
                        // Handle duplicate key and other database errors
                        if ((int)$e->getCode() === 1062) {
                            $message =
                                'A prescription already exists for this appointment.';
                        } else {
                            error_log(
                                'Prescription insert error: ' . $e->getMessage()
                            );

                            $message =
                                'Unable to save prescription. Please try again.';
                        }

                        $messageType = 'error';
                    }
                }

                $duplicateCheck->close();

            }

            if (isset($check) && $check instanceof mysqli_stmt) {
  
}
        }
    }
}

// Fetch appointments with patient and doctor names
$appointments = $conn->query(
    "SELECT
        a.Appointment_ID,
        a.Appointment_Date,
        p.Name AS Patient_Name,
        d.Name AS Doctor_Name
     FROM appointment a
     INNER JOIN patient p
        ON a.Patient_ID = p.Patient_ID
     INNER JOIN doctor d
        ON a.Doctor_ID = d.Doctor_ID
     ORDER BY a.Appointment_Date DESC"
);

// Fetch saved prescriptions
$prescriptions = $conn->query(
    "SELECT
        pr.Prescription_ID,
        pr.Appointment_ID,
        pr.Diagnosis,
        pr.Prescription_Date,
        a.Appointment_Date,
        p.Name AS Patient_Name,
        d.Name AS Doctor_Name
     FROM prescription pr
     INNER JOIN appointment a
        ON pr.Appointment_ID = a.Appointment_ID
     INNER JOIN patient p
        ON a.Patient_ID = p.Patient_ID
     INNER JOIN doctor d
        ON a.Doctor_ID = d.Doctor_ID
     ORDER BY pr.Prescription_ID DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Prescription Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #eef3f8;
            color: #24334a;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            color: #07838d;
        }

        .back {
            color: #07838d;
            text-decoration: none;
        }

        .card {
            background: white;
            padding: 24px;
            margin: 20px 0;
            border-radius: 12px;
            box-shadow: 0 4px 14px #0000000d;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            font: inherit;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        button {
            margin-top: 18px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            background: #07838d;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #066871;
        }

        .message {
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .success {
            background: #dcfce7;
            color: #166534;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background: #07838d;
            color: white;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        @media (max-width: 650px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            body {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
<div class="container">

   <a href="dashboard.php">← Back to Dashboard</a>

    <h1>Prescription Management</h1>
    <p>Create and view patient prescriptions.</p>

    <div class="card">
        <h2>Create New Prescription</h2>

        <?php if ($message !== ''): ?>
            <div class="message <?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="prescriptions.php">

            <div class="form-grid">

                <div>
                    <label for="appointment_id">Select Appointment *</label>

                    <select name="appointment_id"
                            id="appointment_id"
                            required>
                        <option value="">Choose an appointment</option>

                        <?php if ($appointments): ?>
                            <?php while ($a = $appointments->fetch_assoc()): ?>
                                <option
                                    value="<?= (int)$a['Appointment_ID'] ?>">
                                    <?= htmlspecialchars(
                                        $a['Patient_Name'] . ' - ' .
                                        $a['Doctor_Name'] . ' - ' .
                                        $a['Appointment_Date'] .
                                        ' (ID: ' .
                                        $a['Appointment_ID'] . ')'
                                    ) ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>

                    </select>
                </div>

                <div>
                    <label for="prescription_date">
                        Prescription Date *
                    </label>

                    <input
                        type="date"
                        name="prescription_date"
                        id="prescription_date"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >
                </div>

                <div style="grid-column: 1 / -1;">
                    <label for="diagnosis">
                        Diagnosis / Prescription Details *
                    </label>

                    <textarea
                        name="diagnosis"
                        id="diagnosis"
                        maxlength="255"
                        placeholder="Enter diagnosis and prescription details"
                        required
                    ></textarea>
                </div>

            </div>

            <button type="submit">Save Prescription</button>
        </form>
    </div>

    <div class="card">
        <h2>Saved Prescriptions</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Appointment Date</th>
                        <th>Diagnosis</th>
                        <th>Prescription Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($prescriptions && $prescriptions->num_rows > 0): ?>

                        <?php while ($pr = $prescriptions->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?= (int)$pr['Prescription_ID'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pr['Patient_Name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pr['Doctor_Name']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pr['Appointment_Date']) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pr['Diagnosis'] ?? '') ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pr['Prescription_Date'] ?? '') ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <tr>
                            <td colspan="6">No prescriptions found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
