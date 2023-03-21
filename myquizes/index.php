<!DOCTYPE html><head>
	<title>Alex Quiz</title>
    <?php
		include "../../account/accountId.php";
		if(!isset($myId)) {
			header("Location: https://gnets.myds.me/account/login/?redirect=https://gnets.myds.me/quiz");
			die();
		}
		
		include "../var.php";
		include "../db.php";
	?>
</head>

<style>
a {
	text-decoration:none;
	color:#000;
}
</style>

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
<div class="wrapper">
	<?php
		$sql = "SELECT * FROM quizes WHERE creator = '" . $myId . "'";
		$stmt = $conn->query($sql);
		$j=0;
		while ($row = $stmt->fetch()) {
			$j++;
			echo "<a href='../edit/?id=" . $row['id'] . "' class='normal'><h2>" . $row['title'] . "</h2>
			<p>" . $row['description'] . "</p></a><hr />";
		}
	?>
        
    <div style="text-align:center; width:100%">
        <button onClick="location.assign('../new')">New Quiz</button>
    </div>
</div>

</body>
</html>