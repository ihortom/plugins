<div class="pw_boxes free_boxes">
	<div class="pw_box personal-plan">
		<div class="box-cell box-header">FREE</div>
		<div class="box-cell box-feature">
			<p>PERFECT FOR TRYING OUT YOUR IDEAS AT NO COST</p>
		</div>
		<div class="box-cell"><b>1G</b></div>
		<div class="box-cell"><b>50G</b> Data Traffic</div>
		<div class="box-cell"><b>Unlimited</b> websites hosted</div>
		<div class="box-cell"><b>2</b> email accounts</div>
		<div class="box-cell"><b>Limited</b> support</div>
		<div class="box-cell">Up to <b>99.5%</b> Uptime</div>
		<div class="box-cell">Hey, it's <b>free</b></div>
		<div class="box-cell fee"><b>FREE</b></div>
		<div class="box-cell sign-up">
			<form class="pr_rp_sing_up_form" action="/hosting/compare-personal-hosting-plans" method="get">
				<button type="submit" class="rpwp-button colorize"><span class="gloss"></span>More Info</button>
			</form>
		</div>
	</div>
	<div class="pw_box personal-plan">
		<div class="box-cell box-header">Personal</div>
		<div class="box-cell box-feature">
			<p>GREAT FOR PERSONAL ONLINE NEEDS WITH 5 EMAIL ADDRESSES</p>
		</div>
		<div class="box-cell"><b>5G</b></div>
		<div class="box-cell"><b>100G</b> Data Traffic</div>
		<div class="box-cell"><b>Unlimited</b> websites hosted</div>
		<div class="box-cell"><b>5</b> email accounts</div>
		<div class="box-cell"><b>Limited</b> support</div>
		<div class="box-cell">Up to <b>99.5%</b> Uptime</div>
		<div class="box-cell"><b>30</b> Days Money Back Guarantee</div>
		<div class="box-cell fee"><b>C$2.00</b>/month</div>
		<div class="box-cell sign-up">
			<form class="pr_rp_sing_up_form" action="/hosting/compare-personal-hosting-plans/" method="get">
				<button type="submit" class="rpwp-button colorize"><span class="gloss"></span>More Info</button>
			</form>
		</div>
	</div>
<?php foreach ($plans as $plan) : ?>
	<div class="pw_box business-plan<?php if ($plan['rp_product_id'] == $best) echo ' best-pan'; ?>>">
		<div class="box-cell box-header<?php echo ' plan-' . strtolower($plan['name']); ?>"><?php echo $plan['name']; ?></div>
		<div class="box-cell box-feature"><p>
		<?php
			switch ($plan['rp_product_id']) {
				case BRONZE: echo 'EXCELLENT FOR CLIENTS WHO ONLY WANT A NO-FRILLS WEBSITE OR A BLOG'; break;
				case SILVER: echo 'THE IDEAL CHOICE FOR HOSTING SEVERAL WEBSITES IN ONLY ONE SHARED HOSTING ACCOUNT'; break;
				case GOLD: echo 'BEST SUITED FOR CUSTOMERS WHO WANT TO CREATE A POPULAR ONLINE STORE'; break;
			}
		?>
		</p></div>
		<div class="box-cell"><b>Unlimited</b> Storage</div>
		<div class="box-cell"><b>Unlimited</b> Bandwidth</div>
		<div class="box-cell"><?php echo $plan['services']['domain'] . ' Domain'; if ((int)$plan['services']['domain'] > 1) echo 's'; ?></div>
		<div class="box-cell"><?php if ((int)$plan['services']['mailbox'] < 9999) echo $plan['services']['mailbox']; else echo '<b>Unlimited</b>'; ?> Email Accounts</div>
		<div class="box-cell"><b>24/7/365</b> Support</div>
		<div class="box-cell"><b>99.9%</b> Uptime Guarantee</div>
		<div class="box-cell"><b>30</b> Days Money Back Guarantee</div>
		<div class="box-cell fee"><b><?php printf(CURRENCY, $plan[prices][period_12]/12); ?></b>/month</div>
		<div class="box-cell sign-up">
			<form class="pr_rp_sing_up_form" action="/hosting/compare-business-hosting-plans/" method="get">
				<button type="submit" class="rpwp-button colorize"><span class="gloss"></span>More Info</button>
			</form>
		</div>
	</div>
<?php endforeach; ?>
</div>