<section class="quote">
<ol class="latest-quotes-list">
<li>
<?php

if ($quote['status']==='pending'){
?>
	<p class="message">This quote has yet to be accepted into the Spirit.
		<?php
	if($user['role']==='admin'){
?>
				<a href="/quote/validate/<?php echo $quote['id'];?>/">Accept it now!</a>
				<?php
	}
?>
	</p>
<?php
}
if (!empty($_SESSION['message'])){
?>
	<p class="message"><?php echo $_SESSION['message']; ?></p>
<?php
}
?>
<?php
$showLargeAvatar = true;
include 'single-quote.view.php'; ?>
</li>
</ol>
</section>
<?php
unset($_SESSION['message']);
