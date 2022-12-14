<?php

//session_start();

include 'views/mysqliStart.php';

if (isset($_POST['saveGroup'])){
	$name = $_POST['gName'];
	$_SESSION['message'] = "Group added";
	$_SESSION['msg_type'] = "success activeNotif";

	$mysqli->query("INSERT INTO nebula_groups (group_name) VALUES ('$name')") or die($mysqli->error);
	$result = $mysqli->query("SELECT * FROM nebula_groups ORDER BY group_id DESC LIMIT 1");
	while($row = $result->fetch_assoc()){
		$header = "location: index.php?groupID=".$row['group_id'];
		header($header);
	}

	
}

if (isset($_POST['saveSection'])){
	$name = $_POST['sectionName'];
	$length = $_POST['length'];
	$offset = $_POST['offset'];
	$direction = $_POST['dir'];
	$color_order = $_POST['color_order'];
	$pattern = $_POST['pattern'];
	$id = $_POST['group_id'];

	$_SESSION['message'] = "Section added";
	$_SESSION['msg_type'] = "success activeNotif";

	$mysqli->query("INSERT INTO group_sections (section_name, length, offset, direction, group_id, color_order, pattern) VALUES ('$name', '$length', '$offset', '$direction', '$id', '$color_order', '$pattern') ") or die($mysqli->error);

	$header = "location: index.php?groupID=".$id;
	header($header);
}

if (isset($_GET['deleteGroup'])){
	$id = $_GET['deleteGroup'];

	$_SESSION['message'] = "Group removed";
	$_SESSION['msg_type'] = "caution activeNotif";

	$mysqli->query("DELETE FROM nebula_groups WHERE group_id=$id") or die($mysqli->error);
	$mysqli->query("DELETE FROM group_sections WHERE group_id=$id") or die($mysqli->error);

	header("location: index.php");
}


if (isset($_GET['deleteSection'])){
	$id = $_GET['deleteSection'];
	$gID = $_GET['gID'];
	$_SESSION['message'] = "Section removed";
	$_SESSION['msg_type'] = "caution activeNotif";

	$mysqli->query("DELETE FROM group_sections WHERE section_id=$id") or die($mysqli->error);
	echo 'yeet';

	$header = "location: index.php?groupID=".$gID;
	header($header);
}


if (isset($_POST['updateGroup'])){
	$id = $_POST['gID'];
	$name = $_POST['gName'];
	$_SESSION['message'] = "Group Updated";
	$_SESSION['msg_type'] = "caution activeNotif";
	$mysqli->query("UPDATE nebula_groups SET group_name='$name' WHERE group_id='$id'") or die($mysqli->error);

	$header = "location: index.php?groupID=".$id;
	header($header);
	}

if (isset($_POST['updateSection'])){
	$gID = $_POST['group_id'];
	$id = $_POST['section_id'];
	$name = $_POST['sectionName'];
	$length = $_POST['length'];
	$offset = $_POST['offset'];
	$direction = $_POST['dir'];
	$color_order = $_POST['color_order'];
	$pattern = $_POST['pattern'];
	$_SESSION['message'] = "Section Updated";
	$_SESSION['msg_type'] = "caution activeNotif";

	$mysqli->query("UPDATE group_sections SET section_name='$name', length='$length', offset='$offset', direction='$direction', color_order= '$color_order', pattern='$pattern' WHERE section_id='$id'") or die($mysqli->error);
	$header = "location: index.php?groupID=".$gID;
	header($header);
	}

?>