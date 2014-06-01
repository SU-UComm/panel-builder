<?php

/**
 * The default view for rendering a panel of modular content.
 * Override this by creating modular-content/default.php in
 * your theme directory.
 */

$title = get_panel_var('title');
?>
<div class="panel">
	<?php if ( $title ): ?>
		<h3><?php echo $title; ?></h3>
	<?php endif; ?>

	<dl>
		<?php foreach ( get_panel_vars() as $key => $value ): ?>
			<?php if ( $key == 'title' ) { continue; } ?>
			<dt><?php esc_html_e($key); ?></dt>
			<dd><pre><?php esc_html_e(print_r($value, TRUE)); ?></pre></dd>
		<?php endforeach; ?>
	</dl>
</div>