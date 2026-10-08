
<?php
require_once 'auth.php';
require_once 'db.php';

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicineName = trim($_POST['medicine_name'] ?? '');
    $price = $_POST['price'] ?? '';
    $stock = $_POST['stock'] ?? '';

    if (
        $medicineName === '' ||
        $price === '' ||
        $stock === '' ||
        !is_numeric($price) ||
        !is_numeric($stock) ||
        (float)$price < 0 ||
        (float)$stock < 0 ||
        (float)$stock != (int)$stock
    ) {
        $message = 'Please enter valid medicine details.';
        $messageType = 'error';
    } else {
        $price = (float)$price;
        $stock = (int)$stock;

        $stmt = $conn->prepare(
            "INSERT INTO medicine (Medicine_Name, Price, Stock)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sdi", $medicineName, $price, $stock);

        if ($stmt->execute()) {
            header('Location: medicines.php?success=1');
            exit;
        } else {
            $message = 'Error adding medicine: ' . $stmt->error;
            $messageType = 'error';
        }

        $stmt->close();
    }
}

if (isset($_GET['success'])) {
    $message = 'Medicine added successfully!';
    $messageType = 'success';
}

$medicines = $conn->query(
    "SELECT Medicine_ID, Medicine_Name, Price, Stock
     FROM medicine
     ORDER BY Medicine_ID DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medicine Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 25px;
            font-family: Arial, sans-serif;
            background: #eef3f8;
            color: #17324d;
        }

        .container {
            max-width: 1100px;
            margin: auto;
        }

        h1 {
            color: #07838c;
        }

        .back {
            color: #07838c;
            text-decoration: none;
        }

        .card {
            background: white;
            padding: 25px;
            margin: 22px 0;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.07);
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

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 5px;
            font-size: 15px;
        }

        button {
            margin-top: 20px;
            background: #07838c;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 15px;
        }

        button:hover {
            background: #056b72;
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
            background: #07838c;
            color: white;
        }

        tr:nth-child(even) {
            background: #f8fafc;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <a href="dashboard.php">← Back to Dashboard</a>

    <h1>Medicine Management</h1>
    <p>Add medicines, check prices and manage available stock.</p>

    <div class="card">
        <h2>Add New Medicine</h2>

        <?php if ($message !== ''): ?>
            <div class="message <?= htmlspecialchars($messageType) ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="medicines.php">
            <div class="form-grid">
                <div>
                    <label for="medicine_name">Medicine Name *</label>
                    <input
                        type="text"
                        id="medicine_name"
                        name="medicine_name"
                        maxlength="100"
                        required
                    >
                </div>

                <div>
                    <label for="price">Price per Unit (₹) *</label>
                    <input
                        type="number"
                        id="price"
                        name="price"
                        min="0"
                        step="0.01"
                        required
                    >
                </div>

                <div>
                    <label for="stock">Available Stock *</label>
                    <input
                        type="number"
                        id="stock"
                        name="stock"
                        min="0"
                        step="1"
                        required
                    >
                </div>
            </div>

            <button type="submit">Add Medicine</button>
        </form>
    </div>

    <div class="card">
        <h2>Available Medicines</h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Medicine Name</th>
                        <th>Price (₹)</th>
                        <th>Stock</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($medicines && $medicines->num_rows > 0): ?>
                    <?php while ($row = $medicines->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <?= (int)$row['Medicine_ID'] ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['Medicine_Name']) ?>
                            </td>
                            <td>
                                <?= number_format((float)$row['Price'], 2) ?>
                            </td>
                            <td>
                                <?= (int)$row['Stock'] ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No medicines found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>
