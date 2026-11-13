<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php'; ?>
<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Contract</title>
</head>

<body>
    <?php
    include 'conexao.php';

    // Buscar todas as redes ativas
    $chains_sql = "SELECT id, networkName FROM granna80_bdlinks.networks WHERE isActive = 1";
    $chains_result = $conn->query($chains_sql);
    $chains = [];
    if ($chains_result->num_rows > 0) {
        while ($chain_row = $chains_result->fetch_assoc()) {
            $chains[$chain_row['id']] = $chain_row['networkName'];
        }
    }

    // Buscar todas as DEXs disponíveis
    $dexs_sql = "SELECT id, name, router_address FROM granna80_bdlinks.dex_liquidity WHERE type = 'dex'";
    $dexs_result = $conn->query($dexs_sql);
    $dexs = [];
    if ($dexs_result->num_rows > 0) {
        while ($dex_row = $dexs_result->fetch_assoc()) { 
            $dexs[$dex_row['id']] = [
                'name' => $dex_row['name'],
                'router_address' => $dex_row['router_address']
            ];
        }
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // SQL query to get data of the specific contract
        $sql = "SELECT name, contract, asset_a, asset_b, contract_asset_a, contract_asset_b, exchange, router, chain, visibletopublic FROM granna80_bdlinks.lp_contracts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "No record found";
            exit();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $contract = $_POST['contract'];
        $asset_a = $_POST['asset_a'];
        $asset_b = $_POST['asset_b'];
        $contract_asset_a = $_POST['contract_asset_a'];
        $contract_asset_b = $_POST['contract_asset_b'];
        $exchange = $_POST['exchange'];
        $router = $_POST['router'];
        $chain = $_POST['chain'];
        $visibletopublic = isset($_POST['visibletopublic']) ? 1 : 0;

        // Update the record in the database
        $sql = "UPDATE granna80_bdlinks.lp_contracts SET name=?, contract=?, asset_a=?, asset_b=?, contract_asset_a=?, contract_asset_b=?, exchange=?, router=?, chain=?, visibletopublic=? WHERE id=?";
        $stmt = $conn->prepare($sql);

        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("ssssssssssi", $name, $contract, $asset_a, $asset_b, $contract_asset_a, $contract_asset_b, $exchange, $router, $chain, $visibletopublic, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully";
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "Error updating record: " . $stmt->error;
        }
    }
    ?>

    <h2>Edit Contract</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>"><br>

        <label for="contract">Contract:</label><br>
        <input type="text" id="contract" name="contract" value="<?php echo $row['contract']; ?>"><br>

        <label for="asset_a">Asset A:</label><br>
        <input type="text" id="asset_a" name="asset_a" value="<?php echo $row['asset_a']; ?>"><br>

        <label for="asset_b">Asset B:</label><br>
        <input type="text" id="asset_b" name="asset_b" value="<?php echo $row['asset_b']; ?>"><br>

        <label for="contract_asset_a">Contract Asset A:</label><br>
        <input type="text" id="contract_asset_a" name="contract_asset_a" value="<?php echo $row['contract_asset_a']; ?>"><br>

        <label for="contract_asset_b">Contract Asset B:</label><br>
        <input type="text" id="contract_asset_b" name="contract_asset_b" value="<?php echo $row['contract_asset_b']; ?>"><br>

        <label for="exchange">Exchange:</label><br>
        <select id="exchange" name="exchange" onchange="updateRouter()">
            <option value="">-- Select Exchange --</option>
            <?php foreach ($dexs as $dex_id => $dex): ?>
                <option value="<?php echo $dex_id; ?>" <?php echo ($row['exchange'] == $dex_id) ? 'selected' : ''; ?>>
                    <?php echo $dex['name']; ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="chain">Chain:</label><br>
        <select id="chain" name="chain">
            <?php foreach ($chains as $id => $networkName): ?>
                <option value="<?php echo $id; ?>" <?php echo ($row['chain'] == $id) ? 'selected' : ''; ?>>
                    <?php echo $networkName; ?>
                </option>
            <?php endforeach; ?>
        </select><br>

        <label for="visibletopublic">Visible to Public:</label>
        <input type="checkbox" id="visibletopublic" name="visibletopublic" <?php echo $row['visibletopublic'] ? 'checked' : ''; ?>><br><br>

        <input type="submit" value="Update">
    </form>
    <a href='index.php'>Back to List</a>
</body>

</html>