<?php
$q=$_GET['q'];

$row = mysqli_fetch_assoc($results);
echo json_encode($row);
?>
<html>
<script>
function getUser(user_id){
	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){
			// Successful Execution
			var user = JSON.parse(this.responseText);
		}
	}
	xmlhttp.open("GET", "getUser.php?q="+user_id, true);
	xmlhttp.send();
}
</script>
</html>