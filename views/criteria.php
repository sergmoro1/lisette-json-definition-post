<?php
/**
 * Criteria widget form
 *
*/

$deal = isset( $_GET['deal'] ) ? $_GET['deal'] : '-'; 
$what = isset( $_GET['what'] ) ? $_GET['what'] : '-'; 
$p1 = isset( $_GET['p1'] ) ? $_GET['p1'] : '-'; 
$p2 = isset( $_GET['p2'] ) ? $_GET['p2'] : '-'; 

?>

<form action='<?php echo home_url(); ?>'>

<p>
	<select name='deal' class='criteria'>
		<option <?php echo $deal == '-' ? 'selected' : ''; ?> disabled>сделка</option>
		<option <?php echo $deal == '0rent' ? 'selected' : ''; ?> value='0rent'>аренда</option>
		<option <?php echo $deal == '0sell' ? 'selected' : ''; ?> value='0sell'>продажа</option>
	</select>

	<select name='what' class='criteria'>
		<option <?php echo $what == '-' ? 'selected' : ''; ?> disabled>что</option>
		<option <?php echo $what == 'room' ? 'selected' : ''; ?> value='room'>комната</option>
		<option <?php echo $what == 'flat' ? 'selected' : ''; ?> value='flat'>квартира</option>
		<option <?php echo $what == 'house' ? 'selected' : ''; ?> value='house'>дом</option>
		<option <?php echo $what == 'lot' ? 'selected' : ''; ?> value='lot'>участок</option>
	</select>
</p>

<p>
	цена, руб.<br>
	<select name='p1' class='criteria'>
		<option <?php echo $p1 == '-' ? 'selected' : ''; ?> disabled>от</option>
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
		<option <?php echo $p2 == '-' ? 'selected' : ''; ?> disabled>до</option>
		<option <?php echo $p2 == '1000000' ? 'selected' : ''; ?> value='1000000'>1 000 000</option>
		<option <?php echo $p2 == '2000000' ? 'selected' : ''; ?> value='2000000'>2 000 000</option>
		<option <?php echo $p2 == '3000000' ? 'selected' : ''; ?> value='3000000'>3 000 000</option>
		<option <?php echo $p2 == '4000000' ? 'selected' : ''; ?> value='4000000'>4 000 000</option>
		<option <?php echo $p2 == '5000000' ? 'selected' : ''; ?> value='5000000'>5 000 000</option>
		<option <?php echo $p2 == '6000000' ? 'selected' : ''; ?> value='6000000'>6 000 000</option>
		<option <?php echo $p2 == '7000000' ? 'selected' : ''; ?> value='7000000'>7 000 000</option>
		<option <?php echo $p2 == '999999999' ? 'selected' : ''; ?> value='999999999'>любая</option>
	</select>
</p>

<div style='float:right;'>
	<input type='submit'	value='Go!' />
</div>

<div class='clear'></div>

</form>
