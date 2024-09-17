<?php
// bound-assets.php


$jsonUrl = 'https://plata.ie/plataforma/painel/asset/assets_data.json';


$jsonData = file_get_contents($jsonUrl);
$data = json_decode($jsonData, true);


$assets = [];
foreach ($data as $key => $item) {
    if ($key !== 'timestamp') {

        $price_numeric = $item['price'];


        if ($item['ticker'] === 'PLT') {
            $price = sprintf('%.10f', $price_numeric);
        } else {
            $price = number_format($price_numeric, 4, '.', ',');
        }

        $assets[] = [
            'name' => $item['name'],
            'icon' => $item['icon'],
            'ticker' => $item['ticker'],
            'contract' => $item['contract'],
            'decimals' => $item['decimals'],
            'network' => $item['network'],
            'price' => $price,
            'price_numeric' => $price_numeric
        ];
    }
}


usort($assets, function ($a, $b) {
    return $b['price_numeric'] <=> $a['price_numeric'];
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bound Assets</title>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
           
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th,
        td,tr {
   
            text-align: center;
         
        }

        thead th {
     
        text-align: center; 
    }
  
    table.dataTable thead th {
        text-align: center; 
    }
    </style>

</head>

<body>
    <h1>Bound Assets</h1>
    <table id="assetsTable" class="display">
        <thead>
            <tr>
                <th>Name</th>
                <th>Icon</th>
                <th>Ticker</th>
                <th>Contract</th>
                <th>Decimals</th>

                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
  
            foreach ($assets as $asset) {
                $shortContract = substr($asset['contract'], 0, 5) . '...' . substr($asset['contract'], -5);
                if ($asset['ticker'] === 'PLT') {
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($asset['name']); ?></td>
                        <td><img src='/images/assets-icons/<?php echo htmlspecialchars($asset['icon']); ?>' alt='Asset Icon' style='width:20px; height:20px;'></td>
                        <td><?php echo htmlspecialchars($asset['ticker']); ?></td>
                        <td><a href="https://polygonscan.com/token/<?php echo htmlspecialchars($asset['contract']); ?>" target="_blank"><?php echo htmlspecialchars($shortContract); ?></a></td>
                        <td><?php echo htmlspecialchars($asset['decimals']); ?></td>

                        <td><?php echo htmlspecialchars($asset['price']); ?></td>
                    </tr>
                <?php
                    break; // Sai do loop após exibir o PLT
                }
            }

            // Agora exibe os outros ativos, exceto o PLT
            foreach ($assets as $asset) {
                $shortContract = substr($asset['contract'], 0, 5) . '...' . substr($asset['contract'], -5);
                if ($asset['ticker'] !== 'PLT') {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($asset['name']); ?></td>
                        <td><img src='/images/assets-icons/<?php echo htmlspecialchars($asset['icon']); ?>' alt='Asset Icon' style='width:20px; height:20px;'></td>
                        <td><?php echo htmlspecialchars($asset['ticker']); ?></td>
                        <td><a href="https://polygonscan.com/token/<?php echo htmlspecialchars($asset['contract']); ?>" target="_blank"><?php echo htmlspecialchars($shortContract); ?></a></td>
                        <td><?php echo htmlspecialchars($asset['decimals']); ?></td>

                        <td><?php echo htmlspecialchars($asset['price']); ?></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>


    </table>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#assetsTable').DataTable({
                "pageLength": 50,
                "ordering": false // Desativa a ordenação automática
            });
        });
    </script>

</body>

</html>