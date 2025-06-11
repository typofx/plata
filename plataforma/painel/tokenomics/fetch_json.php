<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Json</title>

</head>

<body>
    <div class="container">

        <?php
   
        $json_file_url = 'https://typofx.ie/plataforma/panel/token-historical-data/token_data.json';

      
        $search_date = $_GET['date'] ?? null;

        if (!$search_date) {
            echo '<p class="error">Error</p>';
        } else {
            

            $json_content = @file_get_contents($json_file_url);

            if ($json_content === false) {
                echo '<p class="error">Error</p>';
            } else {
                $data = json_decode($json_content, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo '<p class="error">Error</p>';
                } else {
                    $found_entry = null;
                    foreach ($data as $entry) {
                        if (isset($entry['date']) && $entry['date'] === $search_date) {
                            $found_entry = $entry;
                            break;
                        }
                    }

                    if ($found_entry) {
                        $found_entry['price']      = number_format($found_entry['price'], 10, '.', '');
                        $found_entry['market_cap'] = number_format($found_entry['market_cap'], 4, '.', '');
                        $found_entry['volume']     = number_format($found_entry['volume'], 0, '.', '');

                        echo '<pre>{' . "\n";
                        $last_key = array_key_last($found_entry);
                        foreach ($found_entry as $key => $value) {
                         
                            if (!is_numeric($value)) {
                                $value = '"' . htmlspecialchars($value) . '"';
                            }
                            echo '  "' . htmlspecialchars($key) . '": ' . $value;
                            echo ($key !== $last_key) ? ",\n" : "\n";
                        }
                        echo '}</pre>';
                    } else {
                        echo '<p class="error">Error</p>';
                    }
                }
            }
        }
        ?>
        <br>

    </div>
</body>

</html>