<?php
	$latlng = str_replace(' ', '', $_REQUEST['latlng']);
	$latlng = str_replace('(', '', $latlng);
	$latlng = str_replace(')', '', $latlng);
?>
<script type="text/javascript">
	$(function() {
		$.ajax({
			type: 'POST',
				url: 'http://maps.googleapis.com/maps/api/geocode/json?latlng=<?php echo $latlng ?>&sensor=false',

				success: function(json) {
					alert(json);
				}
		});
	});
</script>


<link href="http://fonts.googleapis.com/css?family=Cantarell:regular,italic,bold,bolditalic" rel="stylesheet" />
<link href="http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic,bold,bolditalic" rel="stylesheet" />
<link rel="stylesheet" href="/skin/css/green.css" />

<div id="register" style="width:100%; height:100%;">

<?php
	echo $latlng;
?>
<form>
	<p><label for="email">Email:</label><input type="text" id="email"/></p>
	<p><label for="firstname">First Name:</label><input type="text" id="firstname"/></p>
	<p><label for="middlename">Middle Name:</label><input type="text" id="middlename"/></p>
	<p><label for="lastname">Last Name:</label><input type="text" id="lastname"/></p>
	<p><label for="">:</label><input type="text" id=""/></p>
		
				
				
			
			<fieldset>
				<legend>What is your sex?</legend>
				<p>
					<input type="radio" name="sex" value="male" id="male" />
					<label for="male">Male</label>
				</p>
				<p>
					<input type="radio" name="sex" value="male" id="female" />
					<label for="female">Female</label>
				</p>
			</fieldset>
			<p>
				<label for="check">Check This:</label>
				<input type="checkbox" id="check" />
			</p>
			<p>
				<label for="secondtext">Name Here and Message Below:</label>
				<input type="text" id="secondtext"/>
			</p>
			<p class="nolabel">
				<textarea></textarea>
			</p>
			<p>
				<label for="dropdown">Dropdown:</label>
				<select>
					<option>Option #1</option>
					<option>Option #2</option>
					<option>Option #3</option>
				</select>
			</p>
			<p class="nolabel">
				<button>button</button>
			</p>
		</form>

</div>
<script src="/skin/js/modernizr-1.5.min.js"></script>
<script src="/skin/js/main.js" type="text/javascript"></script>