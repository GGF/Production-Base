<?
echo "<form action='treb.php' target=_blank method='post'>
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
<option value='������ �.�.' style='color:green;'>������ �.�.</option>
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
</select>
<br>��������:
<select name=razresh>
<option value='��������� �.�.' style='color:black;'>��������� �.�.</option>
<option value='���������� �.�.' style='color:black;'>���������� �.�.</option>
<option value='' style='color:black;'></option>
</select>
<br>����������:
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
<input type=hidden name=nomer value='".$rs["numd"]."'>
<input type=hidden name=date value='".date2datepicker($rs["ddate"],8,2)."'>
<input type=hidden name=otp value='".abs($rs["quant"])."'>
<input type=hidden name=nazv value='".$rs["nazv"]."'>
<input type=hidden name=edizm value='".$rs["edizm"]."'>
<input type=submit value='����������'>
</form>";
?>