<?php class_exists('Core') or die('Access denied');

if(!isset($_SESSION['tOffset'])){
	echo'<script language="javascript"> $(document).ready(function(){ setTimeOffset(); }); </script>';
}
?>