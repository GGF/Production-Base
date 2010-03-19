<?
	
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/contrib/mce/templates/_blank.php";
	
	$title	= "Стандартная таблица";
	$desc		= "Стандартная таблица с предустановленными стилями шапки, тела и подвала.";
	
?>

	<table class="defTable" border='1'>
		<thead>
			<tr>
				<td>№</td>
				<td>Название</td>
				<td class="fullWidth">Значение</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1</td>
				<td class='nowrap'>Первый пункт</td>
				<td>Значение</td>
			</tr>
			<tr>
				<td>2</td>
				<td class='nowrap'>Второй пункт</td>
				<td>Значение</td>
			</tr>
			<tr>
				<td>3</td>
				<td class='nowrap'>Третий пункт</td>
				<td>Значение</td>
			</tr>
		</tbody>
	</table>
</div>
