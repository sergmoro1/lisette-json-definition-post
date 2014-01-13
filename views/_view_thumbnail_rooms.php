<div class='thumbnail'>[image]</div>
<div class='row'>
	<span class='head'>[address]</span></br>
	<?php if ( $model->what != 'lot' ): ?>
	<span class='head'>Комнат</span>
		<span class='item'>[rooms]</span>
	<?php endif; ?>
	<span class='head'>Площадь</span>
		<span class='item'>[total] [priceUnit]</span></br>
	<span class='head'>Цена</span>
		<span class='item price'>[priceTotal] руб.</span>
</div>
<div class='clear'></div>

