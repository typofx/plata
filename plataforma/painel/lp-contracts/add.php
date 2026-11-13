<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';?>
<?php

include 'conexao.php'; 

$chains_sql = "SELECT id, networkName FROM granna80_bdlinks.networks WHERE isActive = 1";
$chains_result = $conn->query($chains_sql);
$chains = [];
if ($chains_result->num_rows > 0) {
    while($chain_row = $chains_result->fetch_assoc()) {
        $chains[$chain_row['id']] = $chain_row['networkName'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
    $name = $_POST['name'];
    $contract = $_POST['contract'];
    $asset_a = $_POST['asset_a'];
    $asset_b = $_POST['asset_b'];
    $liquidity = $_POST['liquidity'];
    $tokenContract_A = $_POST["contract_asset_a"];
    $tokenContract_B = $_POST["contract_asset_b"];
    $exchange = $_POST["exchange"];
    $router = $_POST["router"];
    $chain = $_POST["chain"];
    $visibletopublic = isset($_POST["visibletopublic"]) ? 1 : 0;

    
    $sql = "INSERT INTO granna80_bdlinks.lp_contracts 
            (name, contract, asset_a, asset_b, liquidity, contract_asset_a, contract_asset_b, exchange, router, chain, visibletopublic) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("ssssssssssi", $name, $contract, $asset_a, $asset_b, $liquidity, $tokenContract_A, $tokenContract_B, $exchange, $router, $chain, $visibletopublic);

    if ($stmt->execute()) {
        echo "New record created successfully";
    } else {
        echo "Error creating record: " . $stmt->error;
    }

  
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Contract</title>
</head>
<body>
    <h2>Add Contract</h2>
    <form method="POST" action="">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" maxlength="20" required><br>
        
        <label for="contract">Contract:</label><br>
        <input type="text" id="contract" name="contract" size="42" maxlength="42" required><br>
        
        <label for="asset_a">Asset A:</label><br>
        <input type="text" id="asset_a" name="asset_a" maxlength="22" required><br>
        
        <label for="contract_asset_a">Contract Asset A:</label><br>
        <input type="text" id="contract_asset_a" name="contract_asset_a" maxlength="42" required><br>
        
        <label for="asset_b">Asset B:</label><br>
        <input type="text" id="asset_b" name="asset_b" maxlength="22" required><br>
        
        <label for="contract_asset_b">Contract Asset B:</label><br>
        <input type="text" id="contract_asset_b" name="contract_asset_b" maxlength="42" required><br>
        
        <label for="liquidity">Liquidity:</label><br>
        <input type="text" id="liquidity" name="liquidity"><br>
        
        <label for="exchange">Exchange:</label><br>
        <input type="text" id="exchange" name="exchange"><br>
        
        <label for="router">Router:</label><br>
        <input type="text" id="router" name="router"><br>
        
        <label for="chain">Chain:</label><br>
        <select id="chain" name="chain" required>
            <option value="">Select a chain</option>
            <?php foreach ($chains as $id => $networkName): ?>
                <option value="<?php echo $id; ?>">
                    <?php echo $networkName; ?>
                </option>
            <?php endforeach; ?>
        </select><br>
        
        <label for="visibletopublic">Visible to Public:</label>
        <input type="checkbox" id="visibletopublic" name="visibletopublic" value="1"><br>
    
        <input type="submit" value="Add">
    </form>
    <a href='index.php'>Back to List</a>
</body>
</html>