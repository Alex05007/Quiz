<!DOCTYPE html><head>
	<title>Alex Quiz</title>
    <?php
		include "../../../account/accountId.php";
		if(!isset($myId)) {
			header("Location: https://gnets.myds.me/accouts/login/?redirect=https://gnets.myds.me/quiz");
			die();
		}
		include "../../db.php";
		$sql = "DELETE FROM `quizes` WHERE id='" . $_GET['id'] . "' and creator='" . $myId . "'";
		$stmt = $conn->query($sql);
		$sql = "DELETE FROM `questions` WHERE quiz='" . $_GET['id'] . "' and creator='" . $myId . "'";
		$stmt = $conn->query($sql);
		$sql = "DELETE FROM `responses` WHERE quiz='" . $_GET['id'] . "'";
		$stmt = $conn->query($sql);
		
		header("Location: ../../myquizes");
		die();
	?>
</head>

<body>
</body>
</html>