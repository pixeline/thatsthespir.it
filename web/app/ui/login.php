<article class="login">
	<h1 class="topline" style="background:white;">Log in or Register</h1>
	<div class="content">
<?php
$errors = (isset($_SESSION['errors'])) ? $_SESSION['errors'] : array();
?>
	<p>To register or Log in, use your favourite social network:</p>
	<ul class="single-signon-providers">


		<li><a href="/auth/facebook<?php echo (ENV !== 'live') ? '-test' : ''; ?>"><img src="/assets/img/facebook.svg"><br>Facebook</a></li>
		<li><a href="/auth/google"><img src="/assets/img/googleplus.svg"><br>Google</a></li>
		<li><a href="/auth/github"><img src="/assets/img/github.svg"><br>Github</a></li>
	</ul>

<?php
unset($_SESSION['errors']);
unset($_SESSION['post']);

?>
	</div>
</article>