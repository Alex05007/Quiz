<!DOCTYPE html><head>
	<title>Alex Quiz</title>
    <link href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css" rel="stylesheet">
    <script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
    <?php
		include "../../account/accountId.php";
		if(!isset($myId)) {
			header("Location: https://gnets.myds.me/account/login/?redirect=https://gnets.myds.me/quiz");
			die();
		}
		include "../var.php";
		include "../db.php";
		
		if (isset($_GET['id'])) {
			$sql = "SELECT * FROM quizes";
			$stmt = $conn->query($sql);
			while ($row = $stmt->fetch()) { 
				if ($_GET['id'] == $row['id']) {
					header("Location: ?error=id&title=" . $_GET['title'] . "&desc=" . $_GET['desc'] . "");
					die();
				}
			}
			$sql = "INSERT INTO `quizes`(`id`, `creator`, `title`, `description`) VALUES ('" . $_GET['id'] . "', '" . $myId . "', '" . $_GET['title'] . "', '" . $_GET['desc'] . "')";
			$stmt = $conn->query($sql);
			header("Location: ../edit/?id=" . $_GET['id']);
			die();
		}
		
		if (isset($_POST['title'])) {
			if ($_POST['id'] != "") {
				$sql = "SELECT * FROM quizes";
				$stmt = $conn->query($sql);
				while ($row = $stmt->fetch()) { 
					if ($_POST['id'] == $row['id']) {
						header("Location: ?error=id&title=" . $_POST['title'] . "&desc=" . $_POST['description'] . "");
						die();
					}
				}
				$id = $_POST['id'];
			} else {
				$id = uniqid();
			}
			$sql = "INSERT INTO `quizes`(`id`, `creator`, `title`, `description`) VALUES ('" . $id . "', '" . $myId . "', '" . $_POST['title'] . "', '" . $_POST['description'] . "')";
			$stmt = $conn->query($sql);
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
<?php if (isset($_GET['error'])) { ?>

var id = prompt("The id is taken. Please choose anotherone.");
location.assign("?id=" + id + "&title=<?php echo $_GET['title']; ?>&desc=<?php echo $_GET['desc']; ?>");

<?php } ?>


function change1() {
	var check = document.getElementById('customm').checked;
	if (check == true) {
		document.getElementById('custom').disabled=false;
	}
	else {
		document.getElementById('custom').disabled=true;
	}
}
function change2() {
	var check = document.getElementById('desc').checked;
	if (check == true) {
		document.getElementById('description').disabled=false;
	}
	else {
		document.getElementById('description').disabled=true;
	}
}
function startLod() {
	change1();
	change2();
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
            <input type='text' required name="title" />
            <div class='underline'></div>
            <label>Title</label></div><br />
          
        <div class="mdc-form-field">
          <div class="mdc-checkbox">
            <input type="checkbox"
                   class="mdc-checkbox__native-control"
                   id="desc" onchange="change2()"/>
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
          <input type="text" id="description" name="description" required/>
          <div class="underline"></div>
          <label>Desription</label></div><br />
            
            
        <div class="mdc-form-field">
          <div class="mdc-checkbox">
            <input type="checkbox"
                   class="mdc-checkbox__native-control"
                   id="customm" onchange="change1()"/>
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
          <label for="customm"></label>
          <div class="input-data">
          <input type="text" id="custom" name="id" required maxlength="100"/>
          <div class="underline"></div>
          <label>Custom id</label></div>
          
        </h3>
        
        <br /><br /><br />
        
        <div style="text-align:center; width:100%">
            <button type="submit">Submit</button>
        </div>
    </form>
</div>

</body>
</html>