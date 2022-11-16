<?php
require_once 'db.php';

$titel=isset($_POST['titel']) ? $_POST['titel'] : '';
if(empty($titel) || empty($titel)) {
  header('Location:index.php');
  exit;
}

$datum=isset($_POST['datum']) ? $_POST['datum'] : '';
if(empty($datum) || empty($datum)) {
  header('Location:index.php');
  exit;
}

$zeit=isset($_POST['zeit']) ? $_POST['zeit'] : '';
if(empty($zeit) || empty($zeit)) {
  header('Location:index.php');
  exit;
}


$stmt=$db->prepare("insert into termine(titel,datum,zeit) values(?,?,?)");
$stmt->bind_param('sss',$titel,$datum,$zeit);
$stmt->execute();

header('Location:index.php');
exit;
?>