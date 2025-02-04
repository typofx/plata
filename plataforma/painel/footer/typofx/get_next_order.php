<?php
include "conexao.php";

if (isset($_GET['column_id'])) {
    $column_id = (int)$_GET['column_id'];

    $query = "SELECT COALESCE(MAX(item_order), 0) + 1 AS next_order FROM granna80_bdlinks.typofx_footer WHERE column_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $column_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $next_order);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

 
    echo trim((int)$next_order);
}
?>
