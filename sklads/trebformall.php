<?
print "
Через кого:
<select name=cherezkogo>
<option value=''></option>
<optgroup label='Красная группа'>
<option value='Балуков А.Н.' style='color:red;'>Балуков А.Н.</option>
<option value='Куренков Л.Е.' style='color:red;'>Куренков Л.Е.</option>
<option value='Тимофеев В.В.' style='color:red;'>Тимофеев В.В.</option>
</optgroup>
<optgroup label='Синяя группа'>
<option value='Грималовская Г.А.' style='color:blue;'>Грималовская Г.А.</option>
<option value='Егорычева Т.В.' style='color:blue;'>Егорычева Т.В.</option>
<option value='Курочкина М.А.' style='color:blue;'>Курочкина М.А.</option>
<option value='Левитская Н.П.' style='color:blue;'>Левитская Н.П.</option>
<option value='Разина Е.П.' style='color:blue;'>Разина Е.П.</option>
<option value='Угдыжекова И.В.' style='color:blue;'>Угдыжекова И.В.</option>
<option value='Ходина Е.А.' style='color:blue;'>Ходина Е.А.</option>
<option value='Чистякова И.Н.' style='color:blue;'>Чистякова И.Н.</option>
<option value='Шамарина В.В.' style='color:blue;'>Шамарина В.В.</option>
</optgroup>
<optgroup label='Зеленая группа'>
<option value='Власова Т.В.' style='color:green;'>Власова Т.В.</option>
<option value='Полушкин В.Ю.' style='color:green;'>Полушкин В.Ю.</option>
<option value='Фёдоров И.Ю.' style='color:green;'>Фёдоров И.Ю.</option>
</optgroup>
<optgroup label='Черная группа'>
<option value='Большакова А.В.' style='color:black;'>Большакова А.В.</option>
<option value='Васильев С.Б.' style='color:black;'>Васильев С.Б.</option>
<option value='Владимирова Н.В.' style='color:black;'>Владимирова Н.В.</option>
<option value='Власова И.Ф.' style='color:black;'>Власова И.Ф.</option>
<option value='Евдокимов Д.А.' style='color:black;'>Евдокимов Д.А.</option>
<option value='Игнатьев С.Н.' style='color:black;'>Игнатьев С.Н.</option>
<option value='Китуничев Д.С.' style='color:black;'>Китуничев Д.С.</option>
<option value='Легоньков В.А.' style='color:black;'>Легоньков В.А.</option>
<option value='Орлова Н.Н.' style='color:black;'>Орлова Н.Н.</option>
<option value='Потапова Л.В.' style='color:black;'>Потапова Л.В.</option>
<option value='Салангина И.Г.' style='color:black;'>Салангина И.Г.</option>
<option value='Соковнин С.А.' style='color:black;'>Соковнин С.А.</option>
</optgroup>
<optgroup label='Светлозеленая группа'>
<option value='Жинкин А.И.' style='color:lightgreen;'>Жинкин А.И.</option>
</optgroup>
</select>
Разрешил:
<select name=razresh>
<option value='Китуничев Д.С.' style='color:black;'>Китуничев Д.С.</option>
<option value='Николайчук И.И.' style='color:black;'>Николайчук И.И.</option>
<option value='' style='color:black;'></option>
</select>
Затребовал:
<select name=zatreb>
<option value=''></option>
<optgroup label='Красная группа'>
<option value='Мещанинов В.Ф.' style='color:red;'>Мещанинов В.Ф.</option>
<option value='Тимофеев В.В.' style='color:red;'>Тимофеев В.В.</option>
</optgroup>
<optgroup label='Синяя группа'>
<option value='Соколова В.М.' style='color:blue;'>Соколова В.М.</option>
<option value='Угдыжекова И.В.' style='color:blue;'>Угдыжекова И.В.</option>
</optgroup>
<optgroup label='Зеленая группа'>
<option value='Смирнов В.А.' style='color:green;'>Смирнов В.А.</option>
<option value='Фёдоров И.Ю.' style='color:green;'>Фёдоров И.Ю.</option>
</optgroup>
<optgroup label='Черная группа'>
<option value='Михайлов В.Н.' style='color:black;'>Михайлов В.Н.</option>
<option value='Макарова Т.Л.' style='color:black;'>Макарова Т.Л.</option>
</optgroup>
</select>
Дата:<input type=text size=10 name=ddate id=datepicker value=''>
<input type=hidden name=sklad value='".$sklad."'>
<input type=submit value='Требование'>
";
?>