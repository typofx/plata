<?php
// Set session duration to 8 hours (28800 seconds)
ini_set('session.gc_maxlifetime', 28800);
session_set_cookie_params(28800);

session_start();

if (!isset($_SESSION["user_logged_in"]) || $_SESSION["user_logged_in"] !== true) {
    header("Location: ../../index.php");
    exit();
}
// Include connection file
include 'conexao.php';

// Check if asset ID is passed via GET
if(isset($_GET['id']) && !empty($_GET['id'])) {
    $id = $_GET['id'];
    
    // Check if form is submitted
    if(isset($_POST['submit'])) {
        // Get form data
        $contract_name = $_POST['contract_name'];
        $aka_contract_name = $_POST['aka_contract_name'];
        $ticker_symbol = $_POST['ticker_symbol'];
        $decimal_value = $_POST['decimal_value'];
        $price = $_POST['price'];

        // Query to update asset data
        $query = "UPDATE granna80_bdlinks.assets SET
                    contract_name = '$contract_name',
                    aka_contract_name = '$aka_contract_name',
                    ticker_symbol = '$ticker_symbol',
                    decimal_value = $decimal_value,
                    price = $price
                  WHERE id = $id";

        // Execute update query
        if(mysqli_query($conn, $query)) {
            echo "Asset updated successfully.";
        } else {
            echo "Error updating asset: " . mysqli_error($conn);
        }
    }

    // Query to select asset data based on ID
    $query_select = "SELECT * FROM granna80_bdlinks.assets WHERE id = $id";
    $result_select = mysqli_query($conn, $query_select);

    if(mysqli_num_rows($result_select) == 1) {
        // Extract asset data
        $row = mysqli_fetch_assoc($result_select);
        $contract_name = $row['contract_name'];
        $aka_contract_name = $row['aka_contract_name'];
        $ticker_symbol = $row['ticker_symbol'];
        $decimal_value = $row['decimal_value'];
        $price = $row['price'];

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
                <label>Contract Name:</label>
                <input type="text" name="contract_name" value="<?php echo $contract_name; ?>"><br><br>
                <label>Alternate Contract Name:</label>
                <input type="text" name="aka_contract_name" value="<?php echo $aka_contract_name; ?>"><br><br>
                <label>Ticker Symbol:</label>
                <input type="text" name="ticker_symbol" value="<?php echo $ticker_symbol; ?>"><br><br>
                <label>Decimal Value:</label>
                <input type="number" name="decimal_value" value="<?php echo $decimal_value; ?>"><br><br>
                <label>Price:</label>
                <input type="text" name="price" value="<?php echo $price; ?>"><br><br>
                <input type="submit" name="submit" value="Save">
            </form>
        </body>
        </html>
<?php
    } else {
        echo "Asset not found.";
    }

    // Free result
    mysqli_free_result($result_select);
} else {
    echo "Asset ID not specified.";
}

// Close connection
mysqli_close($conn);
?>
