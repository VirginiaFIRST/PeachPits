<?php
  include dirname(__DIR__) . "/includes/config.php"; 

  $currentEvent = protect($_GET['event']);
  $filename = $currentEvent. "_inspections";
  $inspectionTable = $currentEvent . "_inspections";

  //header info for browser
  header('Content-Transfer-Encoding: none');
  header("Content-Type: application/vnd.ms-excel");
  header("Content-Type: application/x-msexcel");
  header("Content-Type: application/download");
  header("Content-Disposition: attachment; filename=$filename.xls");

  ini_set('zlib.output_compression','Off');
  header('Pragma: public');
  header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
  //header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
  header('Cache-Control: no-store, no-cache, must-revalidate');
  header('Cache-Control: pre-check=0, post-check=0, max-age=0');
  header("Pragma: no-cache"); 
  header("Expires: 0");
  $sep = "\t"; 
  
  echo "Team Number".$sep;
  echo "Inspection Status".$sep;
  echo "Robot Weight (lbs)".$sep;
  echo "Red Bumper Weight (lbs)".$sep;
  echo "Blue Bumper Weight (lbs)".$sep;
  echo "Inspection Notes".$sep;
  echo "Modified By".$sep;
  echo "Modified Time\n";
  $sql = $mysqli->query("SELECT * FROM `".$inspectionTable."` ORDER BY `teamid` ASC");
  while ($row = mysqli_fetch_array($sql, MYSQLI_BOTH)) {
    echo $row['teamid'].$sep;
    echo $row['inspectionstatus'].$sep;
    echo $row['robotweight'].$sep;
    echo $row['redbumperweight'].$sep;
    echo $row['bluebumperweight'].$sep;
    $inspectionNotes = $row['inspectionnotes'];
    $inspectionNotes = str_replace(array("\r", "\n"), '|', $inspectionNotes);
    echo $inspectionNotes.$sep;
    echo $row['modified_by'].$sep;
    echo $row['modified_time'].$sep;
    echo "\n";
  }
?>