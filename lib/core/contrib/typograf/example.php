<html>
<head>
	<title>ArtLebedevStudio.RemoteTypograf example</title>
	<style type="text/css">
		nobr
		{
			background-color: #EEF1E5;
		}
	</style>
</head>
<body>
	<?
		$text = stripslashes ($_POST[text]);
		if (!$text) $text = '"�� ��� ��� ���-��� ��������� � "�����"? - ����� �� ���� � ���!"';
	?>

	<form method="post">
		<textarea style="width: 600px; height: 300px" name="text"><? echo $text; ?></textarea>
		<p>
			<input type="submit" value="ProcessText" />
		</p>		
		<div>
		<?
			if ($_POST[text])
			{
				include "remotetypograf.php";
				
				$remoteTypograf = new RemoteTypograf ('Windows-1251');

				$remoteTypograf->htmlEntities();
				$remoteTypograf->br (false);
				$remoteTypograf->p (true);
				$remoteTypograf->nobr (3);

				print $remoteTypograf->processText ($text);				
			}
		?>
		</div>
	</form>
</body>
</html>
