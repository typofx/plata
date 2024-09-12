<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php
// Include connection file
include 'conexao.php';

// Check if asset ID is passed via GET
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    
    // Check if form is submitted
    if(isset($_POST['submit'])) {
        // Get form data with bind values
        $contract_name = $_POST['contract_name'];
        $aka_contract_name = $_POST['aka_contract_name'];
        $ticker_symbol = $_POST['ticker_symbol'];
        $decimal_value = $_POST['decimal_value'];
        $price = $_POST['price'];
        $name = $_POST['name'];
        $pool = $_POST['pool'];

        // Prepare update statement with bind values
        $query = "UPDATE granna80_bdlinks.assets SET
                    contract_name = ?,
                    aka_contract_name = ?,
                    ticker_symbol = ?,
                    decimal_value = ?,
                    price = ?,
                    name = ?,
                    pool =?
                  WHERE id = ?";

        if ($stmt = mysqli_prepare($conn, $query)) {
            // Bind parameters
            mysqli_stmt_bind_param($stmt, "sssdsssi", $contract_name, $aka_contract_name, $ticker_symbol, $decimal_value, $price, $name, $pool, $id);

            // Execute update query
            if(mysqli_stmt_execute($stmt)) {
                echo "Asset updated successfully.";
            } else {
                echo "Error updating asset: " . mysqli_stmt_error($stmt);
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    }

    // Select asset data based on ID
    $query_select = "SELECT * FROM granna80_bdlinks.assets WHERE id = ?";
    if ($stmt = mysqli_prepare($conn, $query_select)) {
        // Bind parameter for select query
        mysqli_stmt_bind_param($stmt, "i", $id);

        // Execute query
        mysqli_stmt_execute($stmt);

        // Get result
        $result_select = mysqli_stmt_get_result($stmt);

        if(mysqli_num_rows($result_select) == 1) {
            // Fetch data
            $row = mysqli_fetch_assoc($result_select);
            $contract_name = $row['contract_name'];
            $aka_contract_name = $row['aka_contract_name'];
            $ticker_symbol = $row['ticker_symbol'];
            $decimal_value = $row['decimal_value'];
            $price = $row['price'];
            $name = $row['name'];
            $pool = $row['pool'];

            // Form to edit data
?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Edit Asset</title>
            </head>
            <body>
                <h2>Edit Asset</h2>
                <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                    <label>Contract:</label>
                    <input type="text" name="contract_name" value="<?php echo htmlspecialchars($contract_name); ?>"><br><br>
                    <label>Pool:</label>
                    <input type="text" name="pool" value="<?php echo htmlspecialchars($pool); ?>"><br><br>
                    <label>Name:</label>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>"><br><br>
                    <label>AKA Contract:</label>
                    <input type="text" name="aka_contract_name" value="<?php echo htmlspecialchars($aka_contract_name); ?>"><br><br>
                    <label>Ticker Symbol:</label>
                    <input type="text" name="ticker_symbol" value="<?php echo htmlspecialchars($ticker_symbol); ?>"><br><br>
                    <label>Decimal Value:</label>
                    <input type="number" name="decimal_value" value="<?php echo htmlspecialchars($decimal_value); ?>"><br><br>
                    <label>Price:</label>
                    <input type="text" name="price" value="<?php echo htmlspecialchars($price); ?>"><br><br>
                    <input type="submit" name="submit" value="Save">
                    <a href="index.php">Back</a>
                </form>
            </body>
            </html>
<?php
        } else {
            echo "Asset not found.";
        }

        // Free result
        mysqli_stmt_free_result($stmt);
    } else {
        echo "Error preparing select statement: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    echo "Asset ID not specified.";
}

// Close connection
mysqli_close($conn);
?>
