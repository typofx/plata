<?php
include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/painel/is_logged.php';
include 'conexao.php';

// Função para buscar idiomas disponíveis
function fetchLanguages($conn) {
    $sql = "SELECT code FROM granna80_bdlinks.languages";
    $result = $conn->query($sql);

    $languages = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $languages[] = $row['code'];
        }
    }
    return $languages;
}

// Buscar os idiomas disponíveis
$languages = fetchLanguages($conn);

// Verificar se o 'uindex' foi passado
$uindex = isset($_GET['uindex']) ? $_GET['uindex'] : null;

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pegar os dados do formulário
    $name = $_POST['name'];
    $desktopTexts = [];
    $mobileTexts = [];

    // Separar os textos para desktop e mobile
    foreach ($languages as $language) {
        $desktopTexts[$language] = $_POST["desktop_text_$language"];
        $mobileTexts[$language] = $_POST["mobile_text_$language"];
    }

    // Atualizar ou inserir dados para cada idioma e dispositivo
    foreach ($languages as $language) {
        // Lógica para o dispositivo desktop
        $sql_check = "SELECT COUNT(*) as count FROM granna80_bdlinks.plata_texts WHERE uindex=? AND language=? AND device='desktop'";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("is", $uindex, $language);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $exists = $result_check->fetch_assoc()['count'] > 0;

        if ($exists) {
            // Atualizar registro existente para desktop
            $sql_update = "UPDATE granna80_bdlinks.plata_texts SET name=?, text=? WHERE uindex=? AND language=? AND device='desktop'";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("ssis", $name, $desktopTexts[$language], $uindex, $language);
        } else {
            // Inserir novo registro para desktop
            $sql_insert = "INSERT INTO granna80_bdlinks.plata_texts (name, text, language, device, uindex) VALUES (?, ?, ?, 'desktop', ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("sssi", $name, $desktopTexts[$language], $language, $uindex);
        }
        $stmt->execute();

        // Lógica para o dispositivo mobile
        $sql_check = "SELECT COUNT(*) as count FROM granna80_bdlinks.plata_texts WHERE uindex=? AND language=? AND device='mobile'";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("is", $uindex, $language);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        $exists = $result_check->fetch_assoc()['count'] > 0;

        if ($exists) {
            // Atualizar registro existente para mobile
            $sql_update = "UPDATE granna80_bdlinks.plata_texts SET name=?, text=? WHERE uindex=? AND language=? AND device='mobile'";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("ssis", $name, $mobileTexts[$language], $uindex, $language);
        } else {
            // Inserir novo registro para mobile
            $sql_insert = "INSERT INTO granna80_bdlinks.plata_texts (name, text, language, device, uindex) VALUES (?, ?, ?, 'mobile', ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("sssi", $name, $mobileTexts[$language], $language, $uindex);
        }
        $stmt->execute();
    }

    // Redirecionar após o update/insert
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

// Buscar dados atuais para cada idioma e dispositivo, se o uindex estiver presente
$data = [];
if ($uindex) {
    foreach ($languages as $language) {
        // Dados para desktop
        $sql_select = "SELECT * FROM granna80_bdlinks.plata_texts WHERE uindex = ? AND language = ? AND device = 'desktop'";
        $stmt = $conn->prepare($sql_select);
        $stmt->bind_param("is", $uindex, $language);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['desktop'][$language] = $result->fetch_assoc();

        // Dados para mobile
        $sql_select = "SELECT * FROM granna80_bdlinks.plata_texts WHERE uindex = ? AND language = ? AND device = 'mobile'";
        $stmt = $conn->prepare($sql_select);
        $stmt->bind_param("is", $uindex, $language);
        $stmt->execute();
        $result = $stmt->get_result();
        $data['mobile'][$language] = $result->fetch_assoc();
    }
} else {
    // Valores vazios por padrão para novas entradas
    foreach ($languages as $language) {
        $data['desktop'][$language] = ['name' => '', 'text' => ''];
        $data['mobile'][$language] = ['name' => '', 'text' => ''];
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Edit Task</title>
</head>

<body>

    <h2>Edit Task</h2><br>

    <h3>Desktop Texts</h3>

    <!-- Formulário para editar dados -->
    <form method="POST" action="">

        <!-- Campo para editar o nome da task -->
        <label>TEXT NAME:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($data['desktop'][$languages[0]]['name'] ?? ''); ?> " readonly><br><br><br>

        <?php foreach ($languages as $language): ?>
         
            <label>TEXT <?php echo strtoupper($language); ?> (Desktop):</label>
            <input type="text" name="desktop_text_<?php echo $language; ?>" value="<?php echo htmlspecialchars($data['desktop'][$language]['text'] ?? ''); ?>"><br>

          
        <?php endforeach; ?>

<br>
<br>

<h3>Mobile Texts</h3>

        <?php foreach ($languages as $language): ?>
            <label>TEXT <?php echo strtoupper($language); ?> (Mobile):</label>
            <input type="text" name="mobile_text_<?php echo $language; ?>" value="<?php echo htmlspecialchars($data['mobile'][$language]['text'] ?? ''); ?>"><br>

          
        <?php endforeach; ?>

        <!-- Botões -->
        <a href="index.php">Back</a>
        <button type="submit">Save Changes</button>
    </form>

</body>

</html>
