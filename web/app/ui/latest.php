<section class="latest-quotes">
	<h1 class="ui-title topline" style="background:white;">Newcomers.</h1>
	
	<ol class="latest-quotes-list">
	<?php
	$total = count((array)$quotes);

	foreach ($quotes as $k=>$q){
		?>
		<li>
		<?php
		

		$quote = (object)$q;
		$author = (object)$q;
		//$quote['id'] = $quote->quote_id;
		$showLargeAvatar = false;
		include 'single-quote.view.php';
		if ($k<($total-1)){
		?>
			<span class="sep">⤫</span>
		<?php
		}
		?>
		</li>
		<?php
	}	
	?>
	</ol>
</section>
	