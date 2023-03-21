<!DOCTYPE html><head>
	<title>Alex Quiz</title>
    <?php
		if (!isset($_GET['id']) and !isset($_POST['quizId'])) {
			header("Location: myquizes");
			die();
		}
		include "var.php";
		if ((!isset($_COOKIE['responseId']) or (isset($_GET['reset'])) and isset($_GET['id']))) {
			setcookie("responseId", $_GET['id'] . uniqid(), time() + 3600);
		}
		
		include "db.php";	
		if (isset($_POST['quizId'])) {
			$sql = "INSERT INTO `responses`(`quiz`, `id`, `question`, `answer`) VALUES ('" . $_POST['quizId'] . "', '" . $_COOKIE['responseId'] . "', '" . $_POST['questionId'] . "', '" . implode("|", $_POST['question']) . "')";
			$stmt = $conn->query($sql);
			if ($_POST['questionTotal'] == $_POST['questionId']) {
				header("Location: ../quiz/?id=" . $_POST['quizId'] . "&results=true");
				die();
			} else {
				header("Location: ../quiz/?id=" . $_POST['quizId'] . "&q=" . (intval($_POST['questionId'])+1));
				die();
			}
		}
		if (isset($_GET['q'])) {
			$resp = array();
			$sql = "SELECT * FROM responses WHERE id='" . $_COOKIE['responseId'] . "' and quiz='" . $_GET['id'] . "'";
			$stmt = $conn->query($sql);
			while ($row = $stmt->fetch()) {
				$resp[] = $row['question'];
			}
			if (in_array($_GET['q'], $resp)) {
				header("Location: ?id=" . $_GET['id'] . "&q=" . (intval($_GET['q'])+1));
				die();
			}
		}
		
	?>
	<?php
		if (isset($_GET['q'])) {
			$sql = "SELECT * FROM questions WHERE quiz = '" . $_GET['id'] . "' ORDER BY id";
			$stmt = $conn->query($sql);
			$j=0;
			while ($row = $stmt->fetch()) {
				$j++;
				if ($row['id'] == $_GET['q']) {
					$question = $row['question'];
					$answers = explode("|", $row['answers']);
					$type = $row['type'];
					if ($type == "radio")
						$qType = "Single choice";
					else if ($type == "checkbox")
						$qType = "Multiple choice";
					else if ($type == "pool")
						$qType = "Pool";
				}
			}
			if (!isset($answers)) {
				header("Location: ?id=" . $_GET['id'] . "&results=true");
				die();
			} 
		}
	?>
</head>

<body>

<div class="topBar" onclick="location.assign('https://gnets.myds.me/quiz/)">
    <table>
        <tr>
            <td><img src="https://gnets.myds.me/alex/logo.png" width="40px"></td>
            <td><h4>ALEX SOFONEA <font style="color:#666;">Quiz</font></h4></td>
        </tr>
    </table>
</div>

<br /><br />

<?php
	if (!isset($_GET["q"]) and !isset($_GET["results"])) {
?>
<div class="wrapper">
	<?php
		$sql = "SELECT * FROM quizes WHERE id = '" . $_GET['id'] . "'";
		$stmt = $conn->query($sql);
		if ($row = $stmt->fetch()) { } else { echo "<script>location.assign('error');</script>"; }
	?>
	<h2><?php echo $row['title']; ?></h2>
	<p><?php echo $row['description']; ?></p>
        
    <div style="text-align:center; width:100%">
        <button onClick="location.assign('?id=<?php echo $_GET['id']; ?>&q=1')">Start</button>
    </div>
</div>



<?php } else if (isset($_GET["q"]) and !isset($_GET["results"])) { ?>
<div class="wrapper">
	<p>Question <?php echo $_GET['q']; ?> of <?php echo $j; ?></p>
	<h3><?php echo $question; ?> <font style="color:#666; float:right; font-size:16px;"><?php echo $qType; ?></font></h3>
    <form action="index.php" method="post">
    	<?php
			for ($i=0; $i<count($answers); $i++) {
				if ($type == "pool")
					$type = "radio";
				echo '
				<label>
					<input type="' . $type . '" name="question[]" ' . $c . ' value="' . ($i+1) . '">
					<div class="option"><h4>' . $answers[$i] . '</h4></div>
				</label>';
			}
		?>
        <input type="text" name="quizId" value="<?php echo $_GET['id']; ?>" hidden><input type="text" name="questionId" value="<?php echo $_GET['q']; ?>" hidden><input type="text" name="questionTotal" value="<?php echo $j; ?>" hidden>
        
        <div style="text-align:center; width:100%">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

<?php } else if (isset($_GET["results"])) { ?>

<?php
	$sql = "SELECT * FROM questions WHERE quiz = '" . $_GET['id'] . "' ORDER BY id";
	$stmt = $conn->query($sql);
	$correct = array();
	while ($row = $stmt->fetch()) {
		$correct[] = $row['correct'];
	}
	$sql = "SELECT * FROM responses WHERE quiz = '" . $_GET['id'] . "' and id='" . $_COOKIE['responseId'] . "'";
	$stmt = $conn->query($sql);
	$response = array();
	while ($row = $stmt->fetch()) {
		$response[] = $row['answer'];
	}
?>

<div class="wrapper">



<?php
	$c = 0;
	$g = 0;
	$n = 0;
	$o = 0;
	/*echo implode("<strong>|</strong>", $response);
	echo "<br />";
	echo implode("<strong>|</strong>", $correct);*/
	for ($i=0; $i<count($response); $i++) {
		if ($correct[$i] == $response[$i] and $correct[$i] != "")
			$c++;
		else if ($response[$i] == "")
			$n++;
		else if ($response[$i] != "" and $correct[$i] == "")
			$o++;
		else
			$g++;
	}
?>

<div style="text-align:center; width:100%;">
  <h2>Well done! You've completed this quiz!</h2>
  <?php
  	setcookie('responseId', null, -1, '/');
	if ($g == 0) { echo "<h3>You have mastered this quiz!</h3>"; } else if($c < $g) { echo "<h3>Next time will be better!</h3>"; } else { echo "<h3>See how many questions you got right and wrong.</h3>"; } ?>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load("current", {packages:["corechart"]});
      google.charts.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Made', 'Number'],
          ['Right',     <?php echo $c; ?>],
          ['Wrong',      <?php echo $g; ?>],
          ['Not responded',      <?php echo $n; ?>]
        ]);

        var options = {
          is3D: false,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>

<div id="piechart_3d" style="width: 100%; height: 500px;"></div>

<?php if($o != 0) { ?><h4>Questions where the creator hasen't specified the correct answer (ex.: opinion questions) do not show up in the chart.</h4><br /><?php } ?>

<div style="text-align:center; width:100%">
    <button onClick="location.assign('?id=<?php echo $_GET['id']; ?>&reset=true')">Try again</button>
</div>

</div>

<?php } ?>

</body>
</html>