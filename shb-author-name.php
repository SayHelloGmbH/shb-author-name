<?php

/**
 * Plugin Name:       Block: Author name
 * Description:       Provides a block which shows the name of the current author (on the author archive page or on the single post view).
 * Requires at least: 5.9
 * Requires PHP:      8.0
 * Version:           1.0.0
 * Author:            Say Hello GmbH
 * Author URI:        https.//sayhello.ch/
 * License:           GPL-3.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       shb-author-name
 */

function shb_author_name_block_init()
{
	register_block_type_from_metadata(__DIR__ . '/build', [
		'render_callback' => 'shb_author_name_render_block',
	]);
}
add_action('init', 'shb_author_name_block_init');

/**
 * Renders the `sht/query-no-results` block on the server.
 *
 * @param array  $attributes Block attributes.
 * @param string $content    Block default content.
 *
 * @return string Returns the wrapped block HTML.
 */
function shb_author_name_render_block($attributes, $content, $block)
{

	if (!is_author() && !is_single()) {
		return apply_filters('shb/author_name/notauthor', '');
	}

	ob_start();
	$classes = [];
	if (!empty($text_align = $attributes['textAlign'] ?? '')) {
		$classes[] = "has-text-align-{$text_align}";
	}

	if (!empty($classes)) {
		$blockWrapperAttributes = get_block_wrapper_attributes(['class' => implode(' ', $classes)]);
	} else {
		$blockWrapperAttributes = get_block_wrapper_attributes();
	}

	$author_id = null;

	if (is_author()) {
		$author_id = apply_filters('shb/author_name/queried_object', get_queried_object()->ID);
	} elseif (is_single()) {
		$author_id = apply_filters('shb/author_name/single', get_the_author_meta('ID'));
	}

	$author_id = apply_filters('shb/author_name/author_id', $author_id);

	if (!(int) $author_id) {
		return apply_filters('shb/author_name/no_author_id', '');
	}

?>
	<div class="<?php echo "{$attributes['classNameBase']}__name"; ?>"><?php echo get_the_author_meta('display_name', $author_id); ?></div>
<?php
	$content = ob_get_contents();
	ob_end_clean();

	$content = apply_filters('shb/author_name/block-content', $content);

	return sprintf(
		'<div %1$s>%2$s</div>',
		$blockWrapperAttributes,
		$content
	);
}
