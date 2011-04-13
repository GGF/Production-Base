<?php

/*
 * ���������� ������� ����
 */

require $_SERVER["DOCUMENT_ROOT"] . "/lib/engine.php";
authorize(); // ����� �����������
$processing_type = basename(__FILE__, ".php");

if (isset($edit) || isset($add)) {
    // ������
} elseif (isset($delete)) {
    // ��������
} else {
    $sql = "SELECT *
            FROM boards
            JOIN (customers)
            ON (customers.id=boards.customer_id) " .
            (isset($find) ? "WHERE board_name LIKE '%$find%'" : "") .
            (!empty($order) ? "ORDER BY " . $order . " " : "ORDER BY board_name DESC ") .
            (isset($all) ? "LIMIT 50" : "LIMIT 20");

    $cols[customer] = "��������";
    $cols[board_name] = "�����";
    $cols[sizex] = "X";
    $cols[sizey] = "Y";

    $addbutton = false;

    $table = new SqlTable($processing_type, $processing_type, $sql, $cols);
    $table->show();
}
?>
