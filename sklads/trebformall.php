<?
print "
����� ����:
<select name=cherezkogo>
<option value=''></option>
<optgroup label='������� ������'>
<option value='������� �.�.' style='color:red;'>������� �.�.</option>
<option value='�������� �.�.' style='color:red;'>�������� �.�.</option>
<option value='�������� �.�.' style='color:red;'>�������� �.�.</option>
</optgroup>
<optgroup label='����� ������'>
<option value='������������ �.�.' style='color:blue;'>������������ �.�.</option>
<option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
<option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
<option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
<option value='������ �.�.' style='color:blue;'>������ �.�.</option>
<option value='���������� �.�.' style='color:blue;'>���������� �.�.</option>
<option value='������ �.�.' style='color:blue;'>������ �.�.</option>
<option value='��������� �.�.' style='color:blue;'>��������� �.�.</option>
<option value='�������� �.�.' style='color:blue;'>�������� �.�.</option>
</optgroup>
<optgroup label='������� ������'>
<option value='������� �.�.' style='color:green;'>������� �.�.</option>
<option value='�������� �.�.' style='color:green;'>�������� �.�.</option>
<option value='Ը����� �.�.' style='color:green;'>Ը����� �.�.</option>
</optgroup>
<optgroup label='������ ������'>
<option value='���������� �.�.' style='color:black;'>���������� �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
<option value='����������� �.�.' style='color:black;'>����������� �.�.</option>
<option value='������� �.�.' style='color:black;'>������� �.�.</option>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='������ �.�.' style='color:black;'>������ �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
</optgroup>
<optgroup label='������������� ������'>
<option value='������ �.�.' style='color:lightgreen;'>������ �.�.</option>
</optgroup>
</select>
��������:
<select name=razresh>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='���������� �.�.' style='color:black;'>���������� �.�.</option>
<option value='' style='color:black;'></option>
</select>
����������:
<select name=zatreb>
<option value=''></option>
<optgroup label='������� ������'>
<option value='��������� �.�.' style='color:red;'>��������� �.�.</option>
<option value='�������� �.�.' style='color:red;'>�������� �.�.</option>
</optgroup>
<optgroup label='����� ������'>
<option value='�������� �.�.' style='color:blue;'>�������� �.�.</option>
<option value='���������� �.�.' style='color:blue;'>���������� �.�.</option>
</optgroup>
<optgroup label='������� ������'>
<option value='������� �.�.' style='color:green;'>������� �.�.</option>
<option value='Ը����� �.�.' style='color:green;'>Ը����� �.�.</option>
</optgroup>
<optgroup label='������ ������'>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
<option value='�������� �.�.' style='color:black;'>�������� �.�.</option>
</optgroup>
</select>
����:<input type=text size=10 name=ddate id=datepicker value=''>
<input type=hidden name=sklad value='".$sklad."'>
<input type=submit value='����������'>
";
?>