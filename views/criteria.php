<?php

$deal = isset( $_GET['deal'] ) ? $_GET['deal'] : '-'; 
$what = isset( $_GET['what'] ) ? $_GET['what'] : '-'; 
$p1 = isset( $_GET['p1'] ) ? $_GET['p1'] : '-'; 
$p2 = isset( $_GET['p2'] ) ? $_GET['p2'] : '-'; 

?>

<form action='<?php echo home_url(); ?>'>

<p>
	<select name='deal' class='criteria'>
		<option <?php echo $deal == '-' ? 'selected' : ''; ?> disabled>deal</option>
		<option <?php echo $deal == '0rent' ? 'selected' : ''; ?> value='0rent'>rent</option>
		<option <?php echo $deal == '0sell' ? 'selected' : ''; ?> value='0sell'>sell</option>
	</select>

	<select name='what' class='criteria'>
		<option <?php echo $what == '-' ? 'selected' : ''; ?> disabled>object</option>
		<option <?php echo $what == 'room' ? 'selected' : ''; ?> value='room'>room</option>
		<option <?php echo $what == 'flat' ? 'selected' : ''; ?> value='flat'>flat</option>
		<option <?php echo $what == 'house' ? 'selected' : ''; ?> value='house'>house</option>
		<option <?php echo $what == 'lot' ? 'selected' : ''; ?> value='lot'>lot</option>
	</select>
</p>

<p>
	<?= _e('price') ?> rub<br>
	<select name='p1' class='criteria'>
		<option <?php echo $p1 == '-' ? 'selected' : ''; ?> disabled><?= _e('from') ?></option>
		<option <?php echo $p1 == '0' ? 'selected' : ''; ?> value='0'>0</option>
		<option <?php echo $p1 == '1000000' ? 'selected' : ''; ?> value='1000000'>1 000 000</option>
		<option <?php echo $p1 == '2000000' ? 'selected' : ''; ?> value='2000000'>2 000 000</option>
		<option <?php echo $p1 == '3000000' ? 'selected' : ''; ?> value='3000000'>3 000 000</option>
		<option <?php echo $p1 == '4000000' ? 'selected' : ''; ?> value='4000000'>4 000 000</option>
		<option <?php echo $p1 == '5000000' ? 'selected' : ''; ?> value='5000000'>5 000 000</option>
		<option <?php echo $p1 == '6000000' ? 'selected' : ''; ?> value='6000000'>6 000 000</option>
		<option <?php echo $p1 == '7000000' ? 'selected' : ''; ?> value='7000000'>7 000 000</option>
	</select>

	<select name='p2' class='criteria'>
		<option <?php echo $p2 == '-' ? 'selected' : ''; ?> disabled><?= _e('to') ?></option>
		<option <?php echo $p2 == '1000000' ? 'selected' : ''; ?> value='1000000'>1 000 000</option>
		<option <?php echo $p2 == '2000000' ? 'selected' : ''; ?> value='2000000'>2 000 000</option>
		<option <?php echo $p2 == '3000000' ? 'selected' : ''; ?> value='3000000'>3 000 000</option>
		<option <?php echo $p2 == '4000000' ? 'selected' : ''; ?> value='4000000'>4 000 000</option>
		<option <?php echo $p2 == '5000000' ? 'selected' : ''; ?> value='5000000'>5 000 000</option>
		<option <?php echo $p2 == '6000000' ? 'selected' : ''; ?> value='6000000'>6 000 000</option>
		<option <?php echo $p2 == '7000000' ? 'selected' : ''; ?> value='7000000'>7 000 000</option>
		<option <?php echo $p2 == '999999999' ? 'selected' : ''; ?> value='999999999'><?= _e('any') ?></option>
	</select>
</p>

<div style='float:right;'>
	<input type='submit' value='<?= __('Find') ?>' />
</div>

<div class='clear'></div>

</form>
