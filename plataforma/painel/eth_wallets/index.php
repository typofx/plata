<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';?>
<?php

require 'conexao.php';

// Initialize variables
$wallet_address = '';
$identifier = '';
$edit_id = 0;
$update_mode = false;
$message = '';
$message_type = '';

// Handle POST request for creating or updating a wallet
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $wallet_address = $_POST['wallet_address'];
    $identifier = $_POST['identifier'];
    $id = $_POST['id'];

    // Check for empty fields
    if (empty($wallet_address) || empty($identifier)) {
        $_SESSION['message'] = 'Wallet Address and Identifier are required.';
        $_SESSION['message_type'] = 'error';
    } else {
        if (!empty($id)) {
            // --- UPDATE OPERATION ---
            $stmt = $conn->prepare("UPDATE granna80_bdlinks.eth_wallets SET wallet_address = ?, identifier = ? WHERE id = ?");
            $stmt->bind_param("ssi", $wallet_address, $identifier, $id);
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Wallet updated successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error updating wallet: ' . $stmt->error;
                $_SESSION['message_type'] = 'error';
            }
            $stmt->close();
        } else {
            // --- CREATE OPERATION ---
            $stmt = $conn->prepare("INSERT INTO granna80_bdlinks.eth_wallets (wallet_address, identifier) VALUES (?, ?)");
            $stmt->bind_param("ss", $wallet_address, $identifier);
            if ($stmt->execute()) {
                $_SESSION['message'] = 'Wallet added successfully!';
                $_SESSION['message_type'] = 'success';
            } else {
                $_SESSION['message'] = 'Error adding wallet: ' . $stmt->error;
                $_SESSION['message_type'] = 'error';
            }
            $stmt->close();
        }
    }
    // Redirect to avoid form resubmission
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// Handle GET request for deleting a wallet
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    // --- DELETE OPERATION ---
    $stmt = $conn->prepare("DELETE FROM granna80_bdlinks.eth_wallets WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['message'] = 'Wallet deleted successfully!';
        $_SESSION['message_type'] = 'success';
    } else {
        $_SESSION['message'] = 'Error deleting wallet: ' . $stmt->error;
        $_SESSION['message_type'] = 'error';
    }
    $stmt->close();
    // Redirect to clean the URL
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// Handle GET request for editing a wallet
if (isset($_GET['edit'])) {
    $update_mode = true;
    $edit_id = $_GET['edit'];
    // --- READ FOR UPDATE ---
    $result = $conn->query("SELECT * FROM granna80_bdlinks.eth_wallets WHERE id = $edit_id");
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $wallet_address = $row['wallet_address'];
        $identifier = $row['identifier'];
    }
}

// Display session messages
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $message_type = $_SESSION['message_type'];
    // Unset the session message so it doesn't show again
    unset($_SESSION['message']);
    unset($_SESSION['message_type']);
}

// --- READ OPERATION ---
// Fetch all wallets to display in the table
$wallets_result = $conn->query("SELECT * FROM granna80_bdlinks.eth_wallets ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ethereum Wallet Manager</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 25px;
        }

        h1,
        h2 {
            color: #333;
            text-align: left;
            margin-top: 0;
        }

        h1 {
            margin-bottom: 25px;
        }

        h2 {
            font-size: 1.2em;
            margin-bottom: 15px;
        }

        /* Container for the form to keep it small */
        .form-wrapper {
            max-width: 450px;
            /* Defines the width of the form area */
            margin-bottom: 40px;
            /* Space between form and data table */
        }

        .data-wrapper {
            margin-top: 40px;
        }

        /* General Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #67458b;
            color: white;
        }

        /* Form Table */
        .form-table {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
        }

        .form-table td {
            border: none;
            border-bottom: 1px solid #eee;
        }

        .form-table tr:last-child td {
            border-bottom: none;
        }

        .form-table label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-table input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-table .actions-cell {
            padding-top: 15px;
        }

        /* Data Table */
        #walletsTable thead th {
            background-color: #67458b;
        }

        #walletsTable tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        #walletsTable tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* DataTables Controls alignment */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 15px;
        }

        /* Buttons & Actions */
        button,
        .button-link {
            background-color: #67458b;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin-right: 8px;
        }

        button:hover,
        .button-link:hover {
            background-color: #9362C6;
        }

        .button-link.cancel {
            background-color: #6c757d;
        }

        a {
            color: #67458b;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .actions a.edit,
        .actions a.delete {
            color: #337ab7;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        /* Message Styles */
        .message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
            border: 1px solid transparent;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
    </style>
</head>

<body>

    <h1>Ethereum Wallet Manager</h1>

    <?php if ($message): ?>
        <div class="message <?php echo htmlspecialchars($message_type); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="form-wrapper">
        <h2><?php echo $update_mode ? 'Update Wallet' : 'Add New Wallet'; ?></h2>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <table class="form-table">
                <tbody>
                    <tr>
                        <td>
                            <label for="wallet_address">Wallet Address</label>
                            <input type="text" id="wallet_address" name="wallet_address" value="<?php echo htmlspecialchars($wallet_address); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="identifier">Identifier</label>
                            <input type="text" id="identifier" name="identifier" value="<?php echo htmlspecialchars($identifier); ?>" required>
                        </td>
                    </tr>
                    <tr>
                        <td class="actions-cell">
                            <input type="hidden" name="id" value="<?php echo $edit_id; ?>">
                            <button type="submit"><?php echo $update_mode ? 'Update Wallet' : 'Add Wallet'; ?></button>
                            <?php if ($update_mode): ?>
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="button-link cancel">Cancel</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>

    <div class="data-wrapper">
        <h2>Registered Wallets</h2>
        <table id="walletsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Wallet Address</th>
                    <th>Identifier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $cont = 1;
                if ($wallets_result && $wallets_result->num_rows > 0): ?>
                    <?php while ($row = $wallets_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $cont; ?></td>
                            <td><?php echo htmlspecialchars($row['wallet_address']); ?></td>
                            <td><?php echo htmlspecialchars($row['identifier']); ?></td>
                            <td class="actions">
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?edit=<?php echo $row['id']; ?>" class="edit">Edit</a>
                                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this wallet?');">Delete</a>
                            </td>
                        </tr>
                    <?php $cont++;
                    endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align:center;">No wallets registered yet.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#walletsTable').DataTable({
                "lengthMenu": [100, 250, 500, 1000],
                "pageLength": 100
            });
        });
    </script>

</body>

</html>
<?php
// Close the database connection
if (isset($conn)) {
    $conn->close();
}
?>