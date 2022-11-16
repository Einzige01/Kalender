<?php
require_once 'db.php';
//strtotime() date() 
//Anfang des Kalenders rechnen (1 Monat in der Vergangenheit, den 1. Tag dieses Monats)
$anfang=strtotime('first day of last month');
$anfangsdatum=date('Y-m-d',$anfang);
//TODO Ende rechnen: 6 Monate ab dem Anfang
$ende= strtotime('+6 months',$anfang);
$enddatum=date('Y-m-d',$ende);
//Termine aus DB laden
$termine=array();
$stmt=$db->prepare("SELECT * FROM `termine` where datum>=? and datum<?");
$stmt->bind_param('ss',$anfangsdatum,$enddatum);
$stmt->execute();
$result=$stmt->get_result();
while($t=$result->fetch_object()){
	if(!isset($termine[$t->datum])) {
		$termine[$t->datum]=array();
	}
	$termine[$t->datum][]=$t;
}
$result->free();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Kalendar</title>
</head>
<body>
<h1>Veranstaltungen</h1>
</br>
Anfang:&nbsp;<?= date('d.m.Y',$anfang) ?>
</br>
Ende:&nbsp;<?= date('d.m.Y', $ende) ?>
<br />
<table border="1" style="border-collapse:collapse;">
<!-- Kopfzeile mit einer leeren Zelle und 31 Zellen für die Tagesnummern -->
	<tr>
		<th>&nbsp;</th>
<?php 
	for($d=1;$d<=31;++$d) {
		echo '<th>';
		if($d<10) echo '0';
		echo $d;
		echo '.</th>';
	}
?>
	</tr>
<?php
//Eine Zeile pro Monat
for($m=0;$m<6;++$m) {
	$monatsanfang=strtotime('+'.$m.' months',$anfang);
	$anzahlTage=(int)date('t',$monatsanfang);
?>
	<tr>
		<th><?= date('m.Y',$monatsanfang) ?></th>
<?php
	for($t=0;$t<$anzahlTage;++$t) {
		$datum=date('Y-m-d',strtotime('+'.$t.' days',$monatsanfang))
?>	
		<td>
<?php
		echo date('d.m',strtotime('+'.$t.' days',$monatsanfang));
		echo '<br />';
		if(isset($termine[$datum])) {
			foreach($termine[$datum] as $termin) {
				echo substr($termin->zeit,0,5).' '.$termin->titel.'<br />';
			}
		}
?>
		</td>
<?php
	}
?>
	</tr>
<?php
}
?>
</table>
<br />
<table border="1" style="border-collapse:collapse;">
<?php
foreach($termine as $datum=>$liste) {
	foreach($liste as $termin) {
?>
	<tr>
		<td><?= $termin->id ?></td>
		<td><?= date('d.m.Y',strtotime($termin->datum)) ?></td>
		<td><?= substr($termin->zeit,0,5) ?></td>
		<td><?= $termin->titel ?></td>
	</tr>
<?php
	}
}
?>
</table>
<br />
<!-- TODO Formular, um neue Veranstaltungen hinzuzufügen -->
 <form action="veranstaltunghinzufuegen.php" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
  Titel:<input type="text" name="titel" id="titel" value="" /><br />
  Datum:<input type="date" id="datum" name="datum" value="" /><br />
  Zeit:<input type="time" name="zeit" id="zeit" value="" /><br />
  <input type="submit" value="Veranstaltung hinzufügen"/>
</form>

</body>
</html>