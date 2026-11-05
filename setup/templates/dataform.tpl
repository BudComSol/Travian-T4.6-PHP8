<?php
if(isset($_GET['c']) && $_GET['c'] == 1) { ?>
<div class="headline"><span class="f10 c5"><?=INS126?></span></div><br>
<?php } elseif(isset($_GET['c']) && $_GET['c'] == 2) { ?>
<div class="headline"><span class="f10 c5"><?=INS131?></span></div><br>
<?php
}
?>
<form action="process.php" method="post" id="dataform">
<input type="hidden" name="substruc" value="1">

	<p>
	<span class="f10 c"><?=INS127?></span>
		<table>
			<tr><td><?=INS128?>!</td></tr>
			<tr><td><strong><?=INS130?></strong></td></tr>
			<tr><td><label><input type="checkbox" name="confirm_replace" value="1" required> <?=INS132?></label></td></tr>
			<tr><td><center><input type="submit" name="Submit" id="Submit" value="Create.."></center></td></tr>
		</table>
	</p>
</form>
