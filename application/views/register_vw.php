<?php
	if($_POST) {
		$registersavereply = Register::Save($_POST);	
	}
?>
<script language="javascript" type="text/javascript">
	$(function(){
		$('#submit').click(function() {
			if($('#password').val() !== $('#verifypassword').val()) {
				alert("Passwords do not match. Please re-type them.");
				return false;
			}
		});
	});
</script>
<link href="http://fonts.googleapis.com/css?family=Cantarell:regular,italic,bold,bolditalic" rel="stylesheet" />
<link href="http://fonts.googleapis.com/css?family=Droid+Serif:regular,italic,bold,bolditalic" rel="stylesheet" />
<link rel="stylesheet" href="/skin/css/green.css" />

<div id="register" style="width:100%; height:100%;">
<br />
<?php if($_POST && $registersavereply == 'SUCCESS'): ?>
<div>Thank you!</div>
<?php else: ?>
<form action="/register" method="post">
	<fieldset>
		<legend>Login Information</legend>
		<p><label for="email">Email:</label><input type="text" name="email" id="email"/><span style="position:absolute;left:465px;">(This will be your username)</span></p>
		<p><label for="password">Password:</label><input type="password" name="password" id="password"/></p>
		<p><label for="verifypassword">Retype Password:</label><input type="password" name="verifypassword" id="verifypassword"/><span id="verifyspan" style="position:absolute;left:465px;"></span></p>
	</fieldset>
	<br /><br />
	<fieldset>
		<legend>Personal Information</legend>
		<p><label for="firstname">First Name:</label><input type="text" name="firstname" id="firstname"/></p>
		<p><label for="middlename">Middle Name:</label><input type="text" name="middlename" id="middlename"/></p>
		<p><label for="lastname">Last Name:</label><input type="text" name="lastname" id="lastname"/></p>
		<span style="position:absolute;left:110px;">Gender:</span><br />
		<div style="position:absolute;left:150px;"><p><input type="radio" name="gender" value="male" id="female" /><label for="female">Female</label></p></div>
		<p><input type="radio" name="gender" value="male" id="male" /><label for="male">Male</label></p>
		<p><label for="housephone">House Phone:</label><input type="text" name="housephone" id="housephone"/></p>
		<p><label for="cellphone">Cell Phone:</label><input type="text" name="cellphone" id="cellphone"/></p>
	</fieldset>
	<br /><br />
	<fieldset>
		<legend>Address Information</legend>
		<p><label for="streetaddress">Address:</label><input type="text" name="streetaddress" id="streetaddress" value="<?=$googleaddress['streetnumber'] . ' ' . $googleaddress['route']?>"/>
			<span style="position:absolute;left:495px;">Please verify all of this information!</span></p>
		<p><label for="city">City:</label><input type="text" name="city" id="city" value="<?=$googleaddress['city']?>"/></p>
		<p><label for="state">State:</label><input type="text" name="state" id="state" value="<?=$googleaddress['state']?>"/></p>
		<p><label for="country">Country:</label><input type="text" name="country" id="country" value="<?=$googleaddress['country']?>"/></p>
		<p><label for="zip">Zip:</label><input type="text" name="zip" id="zip" value="<?=$googleaddress['zip']?>"/></p>
	</fieldset>			
	<p><label style="width:350px;">I am interested in being a block captain</label><span style="position:absolute;left:200px;"><input type="checkbox" name="blockcaptain" id="blockcaptain" value="1" /></span></p>
	<p><label for="chairman">Chairman:</label>
		<select style="position:absolute;left:160px;" name="chairman" id="chairman">
			<option value="Don't Know">Don't Know</option>
			<option value="Clay Reiche">Clay Reiche</option>
			<option value="Tony Petralia">Tony Petralia</option>
			<option value="Robbie Robinson">Robbie Robinson</option>
			<option value="John Dato">John Data</option>
			<option value="Carol Palin">Carol Palin</option>
		</select>
	</p>
	<p class="nolabel"><button id="submit">save</button></p>
	<input type="hidden" name="latlng" id="latlng" value="<?=$googleaddress['latlng']?>" />
	<input type="hidden" name="formattedaddress" id="formattedaddress" value="<?=$googleaddress['formattedaddress']?>" />
	<input type="hidden" name="county" id="county" value="<?=$googleaddress['county']?>" />
	<input type="hidden" name="neighborhood" id="neighborhood" value="<?=$googleaddress['neighborhood']?>" />
</form>
</div>

<script src="/skin/js/modernizr-1.5.min.js"></script>
<script src="/skin/js/main.js" type="text/javascript"></script>
<script src="/skin/js/jquery.maskedinput-1.2.2.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
	$(function(){
		$('#housephone').mask("(999) 999-9999");
		$('#cellphone').mask("(999) 999-9999");
	});
</script>
<?php endif; ?>