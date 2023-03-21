<!DOCTYPE html><head>
	<title>Alex Quiz</title>
    <?php
		include "../../../account/accountId.php";
		if(!isset($myId)) {
			header("Location: https://gnets.myds.me/account/login/?redirect=https://gnets.myds.me/quiz");
			die();
		}
		include "../../var.php";
		include "../../db.php";
		
		if (isset($_POST['question'])) {
			$sql = "DELETE FROM `questions` WHERE quiz='" . $_POST['quiz'] . "' and id='" . $_POST['nr'] . "' and creator='" . $myId . "'";
			$stmt = $conn->query($sql);
			$sql = "INSERT INTO `questions`(`id`, `quiz`, `creator`, `type`, `question`, `answers`, `correct`) VALUES ('" . $_POST['nr'] . "', '" . $_POST['quiz'] . "', '" . $myId . "', '" . $_POST['type'] . "', '" . $_POST['question'] . "', '" . implode("|", $_POST['answer']) . "', '" . implode("|", $_POST['q']) . "')";
			$stmt = $conn->query($sql);
			header("Location: ../../edit/?id=" . $_POST['quiz']);
			die();
		}
	?>
</head>

<script>
function changeType() {
	var type = document.getElementById('type').value;
	var q = document.getElementsByName('q[]');
	for (var i=0; i<q.length; i++) {
		q[i].type = type;
		q[i].value = i;
	}
}
function clone() {
  const node = document.getElementById("duplicate");
  const clone = node.cloneNode(true);
  clone.id = "";
  clone.style.display = "block";
  document.getElementById("ans").appendChild(clone);
}
function atSubmit() {
	changeType();
}
</script>

<body>

<div class="topBar" onclick="location.assign('https://gnets.myds.me/quiz/)">
    <table>
        <tr>
            <td><img src="https://gnets.myds.me/alex/logo.png" width="40px"></td>
            <td><h4>ALEX SOFONEA <font style="color:#666;">Quiz</font></h4></td>
        </tr>
    </table>
</div>
	
<div class="wrapper">
<?php
		$sql = "SELECT * FROM questions WHERE id='" . $_GET['nr'] . "' and quiz='" . $_GET['id'] . "' and creator = '" . $myId . "'";
		$stmt = $conn->query($sql);
		if ($row = $stmt->fetch()) { }
?>
    
    <label id="duplicate">
        <input type="<?php echo $row['type']; ?>" name="q[]">
        <div class="option">
            <div class='input-data'>
            <input type='text' required name="answer[]" />
            <div class='underline'></div>
            <label>Answer</label></div>
        </div>
    </label>
    
    <form action="index.php" method="post" onSubmit="atSubmit()">
    	<input type="hidden" name="quiz" value="<?php echo $row['quiz']; ?>">
    	<input type="hidden" name="nr" value="<?php echo $row['id']; ?>">
        <h3>
            <table width="100%">
                <tr>
                    <td width="80%"><div class='input-data'>
                    <input type='text' required name="question" value="<?php echo $row['question']; ?>" />
                    <div class='underline'></div>
                    <label>Question</label></div></td>
                    
                    <td style="text-align:right;"><select name="type" id="type" onChange="changeType()" required>
                        <option value="" disabled hidden>Type</option>
                        <option value="radio" <?php if($row['type'] == "radio") echo "selected"; ?>>Single choice</option>
                        <option value="checkbox" <?php if($row['type'] == "checkbox") echo "selected"; ?>>Multiple choice</option>
                        <option value="pool" <?php if($row['type'] == "pool") echo "selected"; ?>>Pool</option>
                    </select></td>
                </tr>
            </table>
        </h3>
        
        <br />
        
        <div id="ans">
         	<?php 
				$a = explode("|", $row['answers']);
				$c = explode("|", $row['correct']);
				for ($i=0; $i<count($a); $i++) {
					$type = $row['type'];
					if ($type == "pool")
						$type = "radio";
			?>
            <label>
                <input type="<?php echo $type; ?>" name="q[]" <?php if (in_array(($i+1), $c)) echo "checked"; ?>>
                <div class="option">
                    <div class='input-data'>
                    <input type='text' required name="answer[]"  value="<?php echo $a[$i]; ?>"/>
                    <div class='underline'></div>
                    <label>Answer</label></div>
                </div>
            </label>
            <?php } ?>
        </div>
        <a href="javascript:clone()"><i class="fas fa-plus"></i></a>
        <div style="text-align:center; width:100%">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

</body>
</html>