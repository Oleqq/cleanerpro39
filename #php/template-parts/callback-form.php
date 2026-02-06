<?php
/**
 * Компонент: callback-form
 *
 * Использование:
 * get_template_part('template-parts/components/callback-form'); // берёт ACF options + override текущей страницы
 * get_template_part('template-parts/components/callback-form', null, ['post_id' => 123]); // если надо руками
 * get_template_part('template-parts/components/callback-form', null, ['title' => '...', 'subtitle' => '...']); // форс
 */

$args = wp_parse_args($args ?? [], [
	'post_id'   => 0,
	'title'     => null,
	'subtitle'  => null,
	'cf7'       => null,
	'image'     => null, // array from ACF image
	'anchor_id' => null,
]);

$cfg = function_exists('theme_get_callback_form_config')
	? theme_get_callback_form_config((int) $args['post_id'])
	: [
		'anchor_id' => '',
		'title'     => "НЕ НАШЛИ<br>НЕОБХОДИМЫЙ<br>ВИД УБОРКИ?",
		'subtitle'  => "Оставьте свои контактные данные,<br>мы с Вами свяжемся и обязательно Вам поможем!",
		'cf7'       => '',
		'image'     => null,
	];

// локальные override через args (самый приоритетный)
if (is_string($args['title']) && trim($args['title']) !== '') $cfg['title'] = $args['title'];
if (is_string($args['subtitle']) && trim($args['subtitle']) !== '') $cfg['subtitle'] = $args['subtitle'];
if (is_string($args['cf7']) && trim($args['cf7']) !== '') $cfg['cf7'] = $args['cf7'];
if (!empty($args['image'])) $cfg['image'] = $args['image'];
if (is_string($args['anchor_id']) && trim($args['anchor_id']) !== '') $cfg['anchor_id'] = $args['anchor_id'];

$section_id = trim((string) $cfg['anchor_id']);
?>

<section class="callback-form"<?php echo $section_id !== '' ? ' id="' . esc_attr($section_id) . '"' : ''; ?>>
	<div class="container callback-form__container">
		<div class="callback-form__grid">

			<div class="callback-form__intro">
				<h2 class="callback-form__title"><?php echo wp_kses_post($cfg['title']); ?></h2>
				<p class="callback-form__subtitle"><?php echo wp_kses_post($cfg['subtitle']); ?></p>
			</div>

			<div class="callback-form__card">
				<?php
				// CF7
				if (trim((string) $cfg['cf7']) !== '') {
					echo do_shortcode($cfg['cf7']);
				} else {
					// Фоллбек чтобы верстка не ломалась, если CF7 не задан
					?>
					<form class="callback-form__form" action="#" method="post" novalidate>
						<!-- CF7 shortcode не задан в настройках -->
						<div class="callback-form__field">
							<label class="callback-form__label" for="cb-name">Ваше имя:<span class="callback-form__req">*</span></label>
							<input class="callback-form__input" id="cb-name" type="text" name="name" autocomplete="name" required>
						</div>
						<div class="callback-form__field">
							<label class="callback-form__label" for="cb-phone">Телефон:<span class="callback-form__req">*</span></label>
							<input class="callback-form__input" id="cb-phone" type="tel" name="phone" autocomplete="tel" inputmode="tel" required>
						</div>
						<button class="ui__button-arrow ui__button-arrow pink callback-form__submit" type="submit">Отправить заявку</button>
					</form>
					<?php
				}
				?>
			</div>

			<div class="callback-form__media">
				<?php
				// картинка
				$img = $cfg['image'];
				if (is_array($img) && !empty($img['url'])) :
					$alt = !empty($img['alt']) ? $img['alt'] : 'Фото';
					?>
					<img
						class="callback-form__img"
						src="<?php echo esc_url($img['url']); ?>"
						alt="<?php echo esc_attr($alt); ?>"
						loading="lazy"
					/>
				<?php else : ?>
					<!-- image here -->
				<?php endif; ?>
			</div>

		</div>
	</div>
</section>
