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
	var pool = false;
	if (type == "pool") {
		type = "radio";
		pool = true;
	}
	if (type != "text") {
		document.getElementById('textAns').style.display = "none";
		document.getElementById('ans').style.display = "block";
		document.getElementById('newAns').style.display = "block";
		var q = document.getElementsByName('q[]');
		for (var i=0; i<q.length; i++) {
			q[i].type = type;
			q[i].value = i;
			if (pool == true) {
				q[i].disabled = true;
				q[i].checked = false;
			} else
				q[i].disabled = false;
		}
	} else {
		document.getElementById('textAns').style.display = "block";
		document.getElementById('ans').style.display = "none";
		document.getElementById('newAns').style.display = "none";
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
    
    <label id="duplicate">
        <input type="radio" name="q[]">
        <div class="option">
            <div class='input-data'>
            <input type='text' required name="answer[]" />
            <div class='underline'></div>
            <label>Answer</label></div>
        </div>
    </label>
    
    <form action="index.php" method="post" onSubmit="atSubmit()">
    	<input type="hidden" name="quiz" value="<?php echo $_GET['id']; ?>">
    	<input type="hidden" name="nr" value="<?php echo $_GET['nr']; ?>">
        <h3>
            <table width="100%">
                <tr>
                    <td width="80%"><div class='input-data'>
                    <input type='text' required name="question" />
                    <div class='underline'></div>
                    <label>Question</label></div></td>
                    
                    <td style="text-align:right;"><select name="type" id="type" onChange="changeType()" required>
                        <option value="" selected disabled hidden>Type</option>
                        <option value="radio">Single choice</option>
                        <option value="checkbox">Multiple choice</option>
                        <option value="pool">Pool</option>
                        <option value="text">Text Answer</option>
                    </select></td>
                </tr>
            </table>
        </h3>
        
        <br />
        
        <div id="ans">
            <label>
                <input type="radio" name="q[]">
                <div class="option">
                    <div class='input-data'>
                    <input type='text' required name="answer[]" />
                    <div class='underline'></div>
                    <label>Answer</label></div>
                </div>
            </label>
        </div>
        <a href="javascript:clone()" id="newAns"><i class="fas fa-plus"></i></a>
		<div id="textAns" style="display: none;">
			<div class='input-data'>
            <input type='text' name="answer" />
            <div class='underline'></div>
            <label>Answer</label></div>
		</div>
        <div style="text-align:center; width:100%">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

</body>
</html>