<ul class="sidebar-section-1">
<?php foreach ($groups as $cat_name => $cat_array) { ?>
	<li>
		<h3 class="font-yellow"><?php echo $cat_name; ?></h3>
		<ul class="sidebar-section-2">
<?php foreach ($cat_array as $nom =>$liste_groupes) { ?>
			<li>
				<?php if ($nom != '') echo "<p class=\"font-yellow\">$nom</p>"; ?>
				<ul>
<?php foreach ($liste_groupes as $rs_icon => $liste_rs) { ?>
					<li class="<?php echo $rs_icon ; ?>">
						<p><?php echo $rsFromIcon[$rs_icon]; ?></p>
						<ul>
<?php foreach ($liste_rs as $link) { ?>
							<li>
								<a href="<?php echo $link['url'] ; ?>" target="_blank" rel="me"><?php echo $link['nom_rs'] ; ?></a>
							</li>
<?php } ?>
						</ul>
					</li>
<?php } ?>
				</ul>
			</li>
<?php } ?>
		</ul>
	</li>
<?php } ?>
</ul>

