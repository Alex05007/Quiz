<!DOCTYPE html><head>
	<title>Alex Quiz</title>
    <?php
		if(!isset($_COOKIE['login'])) {
			header("Location: https://gnets.myds.me/account/login/?redirect=https://gnets.myds.me/quiz");
			die();
		}
		if (!isset($_GET['id'])) {
			header("Location: ../myquizes");
			die();
		}
		include "../var.php";
		include "../db.php";	
	?>
</head>

<body>

<script>

function linkCopy(id) {
  var copyText = document.getElementById(id);
  copyText.select();
  copyText.setSelectionRange(0, 99999)
  document.execCommand("copy");
  alert("The Link was copyed!");
}
</script>

<div class="topBar" onclick="location.assign('https://gnets.myds.me/quiz/)">
    <table>
        <tr>
            <td><img src="https://gnets.myds.me/alex/logo.png" width="40px"></td>
            <td><h4>ALEX SOFONEA <font style="color:#666;">Quiz</font></h4></td>
        </tr>
    </table>
</div>

<br /><br />
<div class="wrapper">
	<?php
		$sql = "SELECT * FROM quizes WHERE id = '" . $_GET['id'] . "'";
		$stmt = $conn->query($sql);
		if ($row = $stmt->fetch()) { } else { echo "<script>location.assign('error');</script>"; }
	?>
	<a href="../?id=<?php echo $row['id']; ?>" class="normal"><h2><?php echo $row['title']; ?></h2></a>
	<p><?php echo $row['description']; ?>
    <a href="javascript:linkCopy('<?php echo $row['id']; ?>')" class="but copy"><i class="far fa-copy"></i></a> <font style="color:#666; float:right; font-size:16px;"><?php echo $qType; ?> <a href="details/?id=<?php echo $_GET['id']; ?>"><i class="fas fa-pen"></i></a></font></p>
    <input class="linkinput" type="text" id="<?php echo $row['id']; ?>" value="https://alxs.gq/q/<?php echo $row['id']; ?>" readonly style="opacity:0;">
    
</div>

<?php
	$sql = "SELECT * FROM questions WHERE quiz = '" . $_GET['id'] . "' ORDER BY id";
	$stmt = $conn->query($sql);
	$j=0;
	while ($row = $stmt->fetch()) {
		$j++;
		$question = $row['question'];
		$answers = explode("|", $row['answers']);
		$type = $row['type'];
		if ($type == "radio")
			$qType = "Single choice";
		else if ($type == "checkbox")
			$qType = "Multiple choice";
		else if ($type == "pool")
			$qType = "Pool";
		$correct[] = $row['correct'];
?>
<div class="wrapper">
	<p>Question <?php echo $j; ?></p>
	<h3><?php echo $question; ?> <font style="color:#666; float:right; font-size:16px;"><?php echo $qType; ?> <a href="question/?id=<?php echo $_GET['id']; ?>&nr=<?php echo $j; ?>"><i class="fas fa-pen"></i></a></font></h3>
	<?php
        for ($i=0; $i<count($answers); $i++) {
			if ($type == "pool") {
				$type = "radio";
				$sql = "SELECT * FROM responses WHERE quiz = '" . $_GET['id'] . "' and question = '" . ($i+1) . "'";
				$stmt = $conn->query($sql);
				$pool = array();
				while ($row = $stmt->fetch()) { 
					$pool[] = $row['answer'];
				}
				$aNr = count($pool);
				$pool = array_count_values($pool);
				if ($aNr != 0)
					$nr = $pool[$i +1] / $aNr * 100;
				else
					$nr = 0;
			}
            echo '
            <label>
                <input type="' . $type . '" name="question[]" ' . $c . ' value="' . ($i+1) . '">
                <div class="option" style="background-image: -webkit-linear-gradient(left, #CCC, #CCC ' . $nr . '%, transparent ' . $nr . '%, transparent 100%)"><h4>' . $answers[$i] . '</h4></div>
            </label>';
        }
    ?>
</div>

<?php
	}
?>
        
<div style="text-align:center; width:100%">
    <button onClick="location.assign('../new/question/?id=<?php echo $_GET['id']; ?>&nr=<?php echo ($j + 1); ?>')">New question</button>
</div><br />


<?php
	$sql = "SELECT * FROM responses WHERE quiz = '" . $_GET['id'] . "'";
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

<h2>Responses to this quiz</h2>
<?php if ($g == 0 and $c == 0) { echo "<h3>There are no responses yet.</h3>"; } else {?>

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
<?php } ?>
</div>

<script>
function deleteQuiz() {
	var confirmation = confirm("Are you sure you want to delete this quiz?");
	if (confirmation == true) {
		location.assign('delete/?id=<?php echo $_GET['id']; ?>');
	}
}
</script>
<div style="text-align:center; width:100%">
    <button onClick="deleteQuiz()" style="background-color:#ef476f;">Delete quiz</button>
</div><br />

</body>
</html>