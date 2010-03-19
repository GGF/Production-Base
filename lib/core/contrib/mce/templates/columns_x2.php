<?
	
	REQUIRE $_SERVER[DOCUMENT_ROOT] . "/core/contrib/mce/templates/_blank.php";
	
	$title	= "Текст в две колонки";
	$desc		= "Стандартная двухколоночная верстка с зазором между колонками.";
	
?>
	
	<table class="borderless">
		<tr>
			<td class="halfWidth">Колонка</td>
			<td class='spacer'></td>
			<td class="halfWidth">Колонка</td>
		</tr>
	</table>
