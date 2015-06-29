<?php
$author['fullname'] = ucfirst($author['fullname']);
?>
<article class="latest-quote">
	<header class="sticky">
		<h1 class="ui-title topline" style="background:white">Quotes by <?= $author['fullname'] ?> <?php
if ($user['role']==='admin'){
?>
 			<a href="/author/edit/<?php echo $author['slug'] ?>">Edit author info</a> 
<?php
}
?></h1>
		<figure class="author">
			<div id="photo" class="photo <?php echo ($author['gender']=='f') ? 'woman':'' ?>" data-author="<?php echo $author['fullname'];?>" <?php echo (!empty($author['photo'])) ? 'style="background-image: url('.$upload_folder.'/'. $author['photo'] .');"':'data-photo="none"'; ?> ></div>
			<figcaption><?= $author['fullname'] ?> <br><small class="meta contribution"><?= $author['total'] ?> quote<?php echo ((int)$author['total']>1) ? 's':''; ?></small></figcaption>
			
			<?php if ($user['role']==='admin'){ ?>
			<p class="quote-meta"><a href="/author/delete/<?php echo $author['slug'] ?>">[ X ] kill author</a></p><?php }?>
		</figure>
	</header>
<?php
$total = count($his_quotes);
if ($total>0){
	foreach($his_quotes as $k=>$quote){
		?>
	<div class="content">
		<?php include 'single-quote.view.php'; ?>
	</div>
<?php if ($k <($total-1)){
	?>
	<span class="sep">⤫</span>
<?php
	}

	}
	?>

<?
}
else{
?>
	<p class="message">This author is quite silent. Would you like to <a href="/quote/add">suggest a quote</a> by him?</p>
<?php
}
?>
</article>

