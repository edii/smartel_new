---------------------------------------- fix params ---
<div class="content">
    <?= $content ?>
</div>

---------------------------------------- start 2 column2
<br />
<?php /* $this->beginContent(); ?>
<div class="container">
	<div id="content">
                <?php $this->getBoxes('center') ?>
		<?php // echo $content; ?>
	</div><!-- content -->
</div>
<?php $this->endContent(); */ ?>
---------------------------------------- end -------

<hr /> center
<?php 
    $this->getBoxes('center') 
?>

<?php
    $this->getBox('test/view');
?>
<hr />