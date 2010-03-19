<?
	
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/contrib/mce/templates/_blank.php";
	
	$title	= "Текст в три колонки";
	$desc		= "Стандартная трехколоночная верстка с зазором между колонками.";
	
?>

	<table class="borderless">
		<tr>
			<td width="33.3%">Колонка</td>
			<td class='spacer'></td>
			<td width="33.3%">Колонка</td>
			<td class='spacer'></td>
			<td width="33.3%">Колонка</td>
		</tr>
	</table>
