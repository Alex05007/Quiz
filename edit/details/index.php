<!DOCTYPE html><head>
	<title>Alex Quiz</title>
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <?php
		include "../../../account/accountId.php";
		if(!isset($myId)) {
			header("Location: https://gnets.myds.me/account/login/?redirect=https://gnets.myds.me/quiz");
			die();
		}
		include "../../var.php";
		include "../../db.php";
		
		if (isset($_POST['title'])) {
			$sql = "DELETE FROM `quizes` WHERE id='" . $_POST['id'] . "'and creator='" . $myId . "'";
			$stmt = $conn->query($sql);
			$id = $_POST['id'];
			$sql = "INSERT INTO `quizes`(`id`, `creator`, `title`, `description`) VALUES ('" . $id . "', '" . $myId . "', '" . $_POST['title'] . "', '" . $_POST['description'] . "')";
			$stmt = $conn->query($sql);
			header("Location: ../../edit/?id=" . $id);
			die();
		}
	?>
	<?php
		$sql = "SELECT * FROM quizes WHERE id='" . $_GET['id'] . "' and creator = '" . $myId . "'";
		$stmt = $conn->query($sql);
		if ($row = $stmt->fetch()) { } else  {
			header("Location: ../edit/?id=" . $id);
			die();
		}
    ?>
</head>

<style>
input[type="checkbox"] {
	display:inline-block !important;
}
</style>

<script>


function change1() {
	var check = document.getElementById('customm').checked;
	if (check == true) {
		document.getElementById('custom').disabled=false;
	}
	else {
		document.getElementById('custom').disabled=true;
	}
}
function startLod() {
	change1();
}
</script>

<body onLoad="startLod()">

<div class="topBar" onclick="location.assign('https://gnets.myds.me/quiz/)">
    <table>
        <tr>
            <td><img src="https://gnets.myds.me/alex/logo.png" width="40px"></td>
            <td><h4>ALEX SOFONEA <font style="color:#666;">Quiz</font></h4></td>
        </tr>
    </table>
</div>
	
<div class="wrapper">
    
    <form action="index.php" method="post">
        <h3>
            <div class='input-data'>
            <input type='text' required name="title" value="<?php echo $row['title']; ?>"/>
            <div class='underline'></div>
            <label>Title</label></div><br />
          
        <div class="mdc-form-field">
          <div class="mdc-checkbox">
            <input type="checkbox"
                   class="mdc-checkbox__native-control"
                   id="desc" onchange="change2()" <?php if ($row['description'] != "") echo "checked"; ?>/>
            <div class="mdc-checkbox__background">
              <svg class="mdc-checkbox__checkmark"
                   viewBox="0 0 24 24">
                <path class="mdc-checkbox__checkmark-path"
                      fill="none"
                      d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
              </svg>
              <div class="mdc-checkbox__mixedmark"></div>
            </div>
            <div class="mdc-checkbox__ripple"></div>
          </div>
          <label for="desc"></label>
          <div class="input-data">
          <input type="text" id="description" name="description" required value="<?php echo $row['description']; ?>"/>
          <div class="underline"></div>
          <label>Desription</label></div><br />
          
          <input type="hidden" value="<?php echo $_GET['id']; ?>" name="id">
          
        </h3>
        
        <br /><br /><br />
        
        <div style="text-align:center; width:100%">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

</body>
</html>