<ul class="sidebar-section-1">
<?php foreach ($groups as $cat_name => $cat_array) { ?>
	<li>
		<h3 class="font-yellow"><?php echo $cat_name; ?></h3>
		<ul class="sidebar-section-2">
<?php foreach ($cat_array as $liste_groupes) {
	$rs_icon = $liste_groupes['rs_icon'];
	$rs_text = $rsFromIcon[$rs_icon];
?>
			<li>
				<p class="font-yellow"><?php if (isset($liste_groupes['nom'])) echo $liste_groupes['nom']; ?></p>
				<ul>
					<li class="<?php echo $rs_icon ; ?>">
						<p><?php echo $rs_text; ?></p>
						<ul>
							<li>
								<a href="<?php echo $liste_groupes['url'] ; ?>" target="_blank" rel="me"><?php echo $liste_groupes['nom_rs'] ; ?></a>
							</li>
						</ul>
					</li>
				</ul>
			</li>
<?php } ?>
		</ul>
	</li>
<?php } ?>
</ul>

