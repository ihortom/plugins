<div class="pw_boxes">
<?php foreach ($plans as $plan) : ?>
	<div class="pw_box">
		<div class="box-cell box-header"><?php echo $plan['name']; ?></div>
		<div class="box-cell box-feature">
		<?php
			switch ($plan['rp_product_id']) {
				case 58395: echo 'EXCELLENT FOR CLIENTS WHO ONLY WANT A NO-FRILLS WEBSITE OR A BLOG'; break;
				case 58393: echo 'THE IDEAL CHOICE FOR HOSTING SEVERAL WEBSITES IN ONLY ONE SHARED HOSTING ACCOUNT'; break;
				case 58394: echo 'BEST SUITED FOR CUSTOMERS WHO WANT TO CREATE A POPULAR ONLINE STORE'; break;
			}
		?>
		</div>
		<div class="box-cell"><?php echo $plan['services']['mailbox'] . 'email accounts';?> </div>
		<div class="box-cell"><?php echo $plan['services']['domain'] . 'domain'; ?></div>
	</div>
<?php endforeach; ?>
</div>