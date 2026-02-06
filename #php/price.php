<?php
/**
 * Template Name: Price
 */

get_header();

$title = (string) get_field('price_title');
$accordions = get_field('price_accordions');
$featured_ids = get_field('price_featured_services');
$text_title = (string) get_field('price_text_title');
$text_content = get_field('price_text_content');

$nl2br = static function (string $s): string {
	$s = trim($s);
	return $s ? nl2br(esc_html($s)) : '';
};
?>

<main>

	<section class="services-accordion">
		<div class="container services-accordion__container">

			<h2 class="section__title-big">
				<?php echo $nl2br($title ?: "цены на услуги клининга\nв калининграде"); ?>
			</h2>

			<?php if (!empty($accordions) && is_array($accordions)) : ?>
				<div class="services-accordion__list">
					<?php foreach ($accordions as $acc_i => $acc) :
						$acc_title = trim((string) ($acc['acc_title'] ?? ''));
						$is_open = !empty($acc['acc_is_open']);
						$rows = $acc['rows'] ?? [];
						if ($acc_title === '') continue;

						$article_class = 'sa-item' . ($is_open ? ' is-open' : '');
						$aria_expanded = $is_open ? 'true' : 'false';
					?>
						<article class="<?php echo esc_attr($article_class); ?>">
							<button class="sa-item__head" type="button" aria-expanded="<?php echo esc_attr($aria_expanded); ?>">
								<span class="sa-item__title"><?php echo esc_html($acc_title); ?></span>
								<span class="sa-item__ico" aria-hidden="true"></span>
							</button>

							<div class="sa-item__body">
								<div class="price-accordion__content">
									<?php if (!empty($rows) && is_array($rows)) : ?>
										<ul class="price-accordion__rows">
											<?php foreach ($rows as $row) :
												$name = trim((string) ($row['name'] ?? ''));
												$price = trim((string) ($row['price'] ?? ''));
												$badge = trim((string) ($row['badge'] ?? ''));
												if ($name === '' || $price === '') continue;
											?>
												<li class="price-row">
													<span class="price-row__name">
														<?php echo esc_html($name); ?>
														<?php if ($badge !== '') : ?>
															<span class="price-row__badge"><?php echo esc_html($badge); ?></span>
														<?php endif; ?>
													</span>
													<span class="price-row__price"><?php echo esc_html($price); ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

		</div>
	</section>

	<section class="services">
		<div class="container services__container">
			<h2 class="section__title-big">популярные услуги по уборке</h2>

			<div class="services__head"></div>

			<div class="services__slider services__slider-secondary services-swiper swiper">
				<button class="services__nav services__nav--prev" type="button" aria-label="Назад">
					<span class="services__nav-ico"></span>
				</button>

				<div class="services__list services__list-secondary swiper-wrapper">
					<?php
					if (!empty($featured_ids) && is_array($featured_ids)) :
						foreach ($featured_ids as $service_id) :
							$service_id = (int) $service_id;
							if (!$service_id) continue;

							$permalink = get_permalink($service_id);
							$title_s = get_the_title($service_id);

							// мини-поля (если у тебя уже есть в сервисах)
							// если нет — будет просто пусто, и верстка не развалится
							$meta = function_exists('get_field') ? (array) get_field('service_card_meta', $service_id) : [];
							$price_s = function_exists('get_field') ? (string) get_field('service_card_price', $service_id) : '';

							$thumb = get_the_post_thumbnail($service_id, 'medium', [
								'class' => 'service-card__img',
								'loading' => 'lazy',
								'alt' => esc_attr($title_s),
							]);
					?>
							<div class="service-card swiper-slide">
								<div class="service-card__media">
									<?php if ($thumb) : ?>
										<?php echo $thumb; ?>
									<?php else : ?>
										<img class="service-card__img" src="https://placehold.co/320x220" alt="" loading="lazy" />
									<?php endif; ?>
								</div>

								<div class="service-card__body">
									<h3 class="service-card__title"><?php echo esc_html($title_s); ?></h3>

									<?php if (!empty($meta)) : ?>
										<ul class="service-card__meta">
											<?php foreach ($meta as $m) :
												$t = is_array($m) ? trim((string) ($m['text'] ?? '')) : trim((string) $m);
												if ($t === '') continue;
											?>
												<li class="service-card__meta-item"><?php echo esc_html($t); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>

									<?php if (trim($price_s) !== '') : ?>
										<p class="service-card__price"><?php echo esc_html($price_s); ?></p>
									<?php endif; ?>

									<div class="service-card__actions">
										<a class="ui__button-noarrow blue" href="#">Telegram</a>
										<a class="service-card__link" href="<?php echo esc_url($permalink); ?>">Что входит?</a>
									</div>
								</div>
							</div>
					<?php
						endforeach;
					endif;
					?>
				</div>

				<button class="services__nav services__nav--next" type="button" aria-label="Вперёд">
					<span class="services__nav-ico"></span>
				</button>
			</div>

			<a class="ui__button-arrow mobile ui__button-arrow pink services__all" href="<?php echo esc_url(get_post_type_archive_link('service')); ?>">
				Все услуги по уборке
				<!-- svg here -->
			</a>
		</div>
	</section>

	<section class="text-section">
		<div class="text-section__container container">
			<div class="text-section__wrapper">
				<h2 class="section__title"><?php echo $nl2br($text_title); ?></h2>
				<p class="section__subtitle"></p>

				<?php if (!empty($text_content)) : ?>
					<div class="text-section__text" style="margin-top: 36px">
						<?php echo wp_kses_post($text_content); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<?php
	    get_template_part('template-parts/components/ticker');
	?>

</main>

<?php get_footer(); ?>
