<?

// ���������� ������ �� ������������������
require $_SERVER["DOCUMENT_ROOT"] . "/lib/engine.php";
authorize(); // ����� �����������
$processing_type = basename(__FILE__, ".php");
// serialize form
if (isset(${'form_' . $processing_type}))
    extract(${'form_' . $processing_type});


if (isset($edit)) {
    $sql = "SELECT * FROM todo WHERE id='" . $edit . "'";
    $rs = sql::fetchOne($sql);

    $form = new Edit($processing_type);
    $form->init();
    $form->addFields(array(
        array(
            "type" => CMSFORM_TYPE_TEXTAREA,
            "name" => "what",
            "label" => '',
            "value" => $rs["what"],
            "options" => array("rows" => "10", "html" => " cols=50 onfocus='$(this).wysiwyg();' ",),
        ),
    ));
    $form->show();
} elseif (isset($delete)) {
    $sql = "SELECT what FROM todo WHERE id='" . $delete . "'";
    $rs = sql::fetchOne($sql);
    $sql = "UPDATE todo SET rts=NOW(), what='<del>" . $rs["what"] . "</del>' WHERE id='$delete'";
    sql::query($sql);
    sql::error(true);
    echo "ok";
} else {
    $sql = "SELECT *, todo.id FROM todo JOIN users ON users.id=u_id " . (isset($find) ? "WHERE (what LIKE '%$find%' ) " : "") . ((isset($all)) ? "" : (isset($find) ? " AND rtsrts='000000000000' " : " WHERE rts='000000000000' ")) . (!empty($order) ? "ORDER BY " . $order . " " : "ORDER BY cts ") . ((isset($all)) ? "" : "LIMIT 20");
    // echo $sql;

    $cols[id] = "ID";
    $cols[nik] = "���";
    $cols[cts] = "�����";
    $cols[rts] = "��������";
    $cols[what] = "��� �������";


    $table = new SqlTable("todo", "todo", $sql, $cols);
    $table->addbutton = true;
    $table->show();
}
?>