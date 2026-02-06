<?php
/**
 * Компонент: cta-block (глобально из ACF Options)
 *
 * Использование:
 * get_template_part('template-parts/components/cta-block');
 */

if (!function_exists('get_field')) {
	return;
}

$title       = trim((string) get_field('tp_title', 'option'));
$subtitle    = trim((string) get_field('tp_subtitle', 'option'));
$button_text = trim((string) get_field('tp_button_text', 'option'));
$button_url  = trim((string) get_field('tp_button_url', 'option'));
$image       = get_field('tp_image', 'option'); // array/id/url (у нас array)

$image_url = '';
$image_alt = '';

if (is_array($image)) {
	$image_url = (string) ($image['url'] ?? '');
	$image_alt = (string) ($image['alt'] ?? '');
} elseif (is_numeric($image)) {
	$image_url = wp_get_attachment_image_url((int) $image, 'large') ?: '';
	$image_alt = get_post_meta((int) $image, '_wp_attachment_image_alt', true) ?: '';
} elseif (is_string($image)) {
	$image_url = $image;
}

$has_button = ($button_text !== '' && $button_url !== '');
$has_any = ($title !== '' || $subtitle !== '' || $has_button || $image_url !== '');

if (!$has_any) {
	return;
}
?>

<section class="text-photo">
	<div class="container text-photo__container">
		<div class="text-photo__wrapper">
			<div class="text-photo__content">
				<?php if ($title !== '') : ?>
					<h2 class="section__title"><?php echo wp_kses_post(nl2br($title)); ?></h2>
				<?php endif; ?>

				<?php if ($subtitle !== '') : ?>
					<p class="section__subtitle"><?php echo wp_kses_post(nl2br($subtitle)); ?></p>
				<?php endif; ?>

				<?php if ($has_button) : ?>
					<a class="ui__button-arrow ui__button-arrow pink" href="<?php echo esc_url($button_url); ?>">
						<?php echo esc_html($button_text); ?>
						<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="16.5" cy="16.5" r="16.5" fill="white"></circle><path d="M14.4001 12.1999C14.8419 11.8687 15.4682 11.9583 15.7995 12.4001L18.7995 16.4001C19.066 16.7556 19.066 17.2438 18.7995 17.5993L15.7995 21.5993C15.4682 22.0411 14.8419 22.1308 14.4001 21.7995C13.9583 21.4682 13.8687 20.8419 14.1999 20.4001L16.5341 17.2873L16.7497 16.9997L16.5341 16.7121L14.1999 13.5993C13.8687 13.1575 13.9583 12.5312 14.4001 12.1999Z" fill="#B71375"></path></svg>
					</a>
				<?php endif; ?>
			</div>

			<?php if ($image_url !== '') : ?>
				<div class="text-photo__media">
					<img class="text-photo__img" src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" loading="lazy" />
				</div>
			<?php endif; ?>
		</div>

		<?php if ($has_button) : ?>
			<a class="ui__button-arrow mobile ui__button-arrow pink" href="<?php echo esc_url($button_url); ?>">
2 14.4001 12.1999Z" fill="#B71375"></path></svg>
			</a>				<?php echo esc_html($button_text); ?>
				<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="16.5" cy="16.5" r="16.5" fill="white"></circle><path d="M14.4001 12.1999C14.8419 11.8687 15.4682 11.9583 15.7995 12.4001L18.7995 16.4001C19.066 16.7556 19.066 17.2438 18.7995 17.5993L15.7995 21.5993C15.4682 22.0411 14.8419 22.1308 14.4001 21.7995C13.9583 21.4682 13.8687 20.8419 14.1999 20.4001L16.5341 17.2873L16.7497 16.9997L16.5341 16.7121L14.1999 13.5993C13.8687 13.1575 13.9583 12.531
		<?php endif; ?>
	</div>
</section>
