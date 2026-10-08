
<?php
require_once 'auth.php';
require_once 'db.php';

$message = "";
$messageType = "";

// Handle bill submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $appointmentID = filter_var(
        $_POST["appointment_id"] ?? "",
        FILTER_VALIDATE_INT
    );

    $consultationRaw = trim($_POST["consultation_charge"] ?? "");
    $medicineRaw = trim($_POST["medicine_charge"] ?? "");
    $paymentStatus = $_POST["payment_status"] ?? "";

    // Validate values before inserting into the database
    if (
        $appointmentID === false ||
        $appointmentID === null ||
        $appointmentID <= 0 ||
        $consultationRaw === "" ||
        !is_numeric($consultationRaw) ||
        $medicineRaw === "" ||
        !is_numeric($medicineRaw) ||
        !in_array($paymentStatus, ["Pending", "Paid"], true)
    ) {
        $message = "Please enter valid billing details.";
        $messageType = "error";

    } else {

        $consultationCharge = (float)$consultationRaw;
        $medicineCharge = (float)$medicineRaw;

        if (
            !is_finite($consultationCharge) ||
            !is_finite($medicineCharge) ||
            $consultationCharge < 0 ||
            $medicineCharge < 0 ||
            $consultationCharge > 9999999999 ||
            $medicineCharge > 9999999999
        ) {
            $message = "Charges must be valid, non-negative amounts.";
            $messageType = "error";

        } else {

            try {
                // Verify that the appointment exists
                $check = $conn->prepare(
                    "SELECT Appointment_ID
                     FROM appointment
                     WHERE Appointment_ID = ?"
                );

                $check->bind_param("i", $appointmentID);
                $check->execute();

                $appointmentResult = $check->get_result();
                $appointmentExists = $appointmentResult->num_rows > 0;

                $check->close();

                if (!$appointmentExists) {
                    $message = "Selected appointment was not found.";
                    $messageType = "error";

                } else {

                    // Check whether a bill already exists
                    $duplicateCheck = $conn->prepare(
                        "SELECT Bill_ID
                         FROM bill
                         WHERE Appointment_ID = ?
                         LIMIT 1"
                    );

                    $duplicateCheck->bind_param("i", $appointmentID);
                    $duplicateCheck->execute();

                    $duplicateResult = $duplicateCheck->get_result();
                    $billAlreadyExists = $duplicateResult->num_rows > 0;

                    $duplicateCheck->close();

                    if ($billAlreadyExists) {
                        $message =
                            "A bill already exists for this appointment. "
                            . "Please select a different appointment.";
                        $messageType = "error";

                    } else {

                        // Calculate the total
                        $amount = round(
                            $consultationCharge + $medicineCharge,
                            2
                        );

                        // Insert the bill
                        $insert = $conn->prepare(
                            "INSERT INTO bill
                                (Appointment_ID, Amount, Bill_Date, Payment_Status)
                             VALUES (?, ?, CURDATE(), ?)"
                        );

                        $insert->bind_param(
                            "ids",
                            $appointmentID,
                            $amount,
                            $paymentStatus
                        );

                        try {
                            $insert->execute();
                            $insert->close();

                            // Redirect after successful submission
                            header("Location: billing.php?success=1");
                            exit;

                        } catch (mysqli_sql_exception $e) {

                            $insert->close();

                            // MySQL error 1062 means duplicate entry
                            if ((int)$e->getCode() === 1062) {
                                $message =
                                    "A bill already exists for this appointment. "
                                    . "Please select a different appointment.";
                            } else {
                                error_log(
                                    "Billing insert error: " . $e->getMessage()
                                );

                                $message =
                                    "Unable to generate the bill. Please try again.";
                            }

                            $messageType = "error";
                        }
                    }
                }

            } catch (mysqli_sql_exception $e) {

                error_log("Billing database error: " . $e->getMessage());

                $message =
                    "A database error occurred. Please try again.";
                $messageType = "error";
            }
        }
    }
}

// Success message after redirect
if (isset($_GET["success"]) && $_GET["success"] === "1") {
    $message = "Bill generated successfully!";
    $messageType = "success";
}

// Fetch appointments with patient and doctor names
$appointments = $conn->query(
    "SELECT
        a.Appointment_ID,
        a.Appointment_Date,
        p.Name AS Patient_Name,
        d.Name AS Doctor_Name
     FROM appointment a
     JOIN patient p ON a.Patient_ID = p.Patient_ID
     JOIN doctor d ON a.Doctor_ID = d.Doctor_ID
     ORDER BY a.Appointment_Date DESC"
);

// Fetch existing bills
$bills = $conn->query(
    "SELECT
        b.Bill_ID,
        b.Amount,
        b.Bill_Date,
        b.Payment_Status,
        b.Appointment_ID,
        a.Appointment_Date,
        p.Name AS Patient_Name,
        d.Name AS Doctor_Name
     FROM bill b
     JOIN appointment a ON b.Appointment_ID = a.Appointment_ID
     JOIN patient p ON a.Patient_ID = p.Patient_ID
     JOIN doctor d ON a.Doctor_ID = d.Doctor_ID
     ORDER BY b.Bill_ID DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Billing Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            color: #087f8c;
        }

        .card {
            background: white;
            padding: 25px;
            margin: 20px 0;
            border-radius: 12px;
            box-shadow: 0 3px 12px #00000012;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        input, select {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            font-size: 15px;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            background: #087f8c;
            color: white;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #066874;
        }

        .total {
            margin-top: 18px;
            font-size: 20px;
            font-weight: bold;
            color: #087f8c;
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
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #087f8c;
            color: white;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        .back {
            color: #087f8c;
            text-decoration: none;
        }

        @media (max-width: 650px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            body {
                padding: 12px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <p>
        <a href="dashboard.php">← Back to Dashboard</a>
    </p>

    <h1>Billing Management</h1>
    <p>Generate patient bills and manage payment status.</p>

    <div class="card">
        <h2>Generate New Bill</h2>

        <?php if ($message !== ""): ?>
            <div class="message <?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="billing.php">

            <div class="form-grid">

                <div>
                    <label for="appointment_id">Select Appointment *</label>

                    <select name="appointment_id"
                            id="appointment_id" required>

                        <option value="">Choose an appointment</option>

                        <?php if ($appointments): ?>
                            <?php while ($a = $appointments->fetch_assoc()): ?>
                                <option value="<?= (int)$a["Appointment_ID"] ?>">
                                    <?= htmlspecialchars($a["Patient_Name"]) ?>
                                    -
                                    <?= htmlspecialchars($a["Doctor_Name"]) ?>
                                    -
                                    <?= htmlspecialchars($a["Appointment_Date"]) ?>
                                    (ID: <?= (int)$a["Appointment_ID"] ?>)
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>

                    </select>
                </div>

                <div>
                    <label for="consultation_charge">
                        Consultation Charge (₹) *
                    </label>

                    <input type="number"
                           id="consultation_charge"
                           name="consultation_charge"
                           min="0"
                           max="9999999999"
                           step="0.01"
                           value="500"
                           required>
                </div>

                <div>
                    <label for="medicine_charge">
                        Additional Medicine Charges (₹) *
                    </label>

                    <input type="number"
                           id="medicine_charge"
                           name="medicine_charge"
                           min="0"
                           max="9999999999"
                           step="0.01"
                           value="0"
                           required>
                </div>

                <div>
                    <label for="payment_status">Payment Status *</label>

                    <select name="payment_status"
                            id="payment_status" required>
                        <option value="Pending">Pending</option>
                        <option value="Paid">Paid</option>
                    </select>
                </div>

            </div>

            <div class="total">
                Total Amount: ₹<span id="total_amount">500.00</span>
            </div>

            <button type="submit">Generate Bill</button>

        </form>
    </div>

    <div class="card">
        <h2>Generated Bills</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Bill ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Appointment Date</th>
                        <th>Bill Date</th>
                        <th>Amount (₹)</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if ($bills && $bills->num_rows > 0): ?>

                        <?php while ($b = $bills->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int)$b["Bill_ID"] ?></td>

                                <td>
                                    <?= htmlspecialchars($b["Patient_Name"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($b["Doctor_Name"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($b["Appointment_Date"]) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($b["Bill_Date"] ?? "") ?>
                                </td>

                                <td>
                                    <?= number_format((float)$b["Amount"], 2) ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($b["Payment_Status"]) ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7">No bills found.</td>
                        </tr>

                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    const consultation = document.getElementById("consultation_charge");
    const medicine = document.getElementById("medicine_charge");
    const total = document.getElementById("total_amount");

    function calculateTotal() {
        const c = Math.max(0, Number(consultation.value) || 0);
        const m = Math.max(0, Number(medicine.value) || 0);

        total.textContent = (c + m).toFixed(2);
    }

    consultation.addEventListener("input", calculateTotal);
    medicine.addEventListener("input", calculateTotal);
</script>

</body>
</html>
