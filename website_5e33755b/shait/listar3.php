<?php
include 'conexao.php';

$sqlScore = "SELECT * FROM granna80_bdlinks.links ORDER BY `Access` DESC LIMIT 1 OFFSET 5";
$resultScore = $conn->query($sqlScore);

if ($resultScore->num_rows > 0) {

    while ($rowScore = $resultScore->fetch_assoc()) {

        $avgScore = $rowScore["Access"];
        echo "A+ >" . $avgScore . "<br>";
        echo "A >" . $avgScore * 0.75 . "<br>";
        echo "B >" . $avgScore * 0.50 . "<br>";
        echo "C >" . $avgScore * 0.25 . "<br>";
        echo "D <" . $avgScore * 0.25 . "<br>";
    }

} else {
    echo "NULL";
}

?>

<body>

<label for="fname">Score:</label>
<input type="text" id="score" name="score">

</body>

<?php $conn->close(); ?>