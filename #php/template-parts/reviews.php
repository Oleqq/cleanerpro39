<?php
/**
 * Компонент: Reviews
 *
 * get_template_part('template-parts/components/reviews');
 *
 * или:
 * get_template_part('template-parts/components/reviews', null, [
 *   'title' => '...',
 *   'yandex_url' => 'https://...',
 *   'gis_url' => 'https://...',
 *   'badge_title' => '1 000+<br>счастливых<br>клиентов',
 *   'badge_text' => '...',
 *   'limit' => 8,
 * ]);
 */

$title = (string) ($args['title'] ?? 'ЧИСТОТА, ВДОХНОВЛЕННАЯ<br>ОТЗЫВАМИ КЛИЕНТОВ');
$yandex_url = (string) ($args['yandex_url'] ?? '#');
$gis_url = (string) ($args['gis_url'] ?? '#');

$badge_title = (string) ($args['badge_title'] ?? '1 000+<br>счастливых<br>клиентов');
$badge_text = (string) ($args['badge_text'] ?? 'Узнайте, как мы можем помочь вам создать комфортное пространство для жизни и работы!');

$limit = isset($args['limit']) ? (int) $args['limit'] : 8;

$is_service = is_singular('service');
$service_id = $is_service ? (int) get_queried_object_id() : 0;

$query_args = [
	'post_type'      => 'review',
	'posts_per_page' => $limit,
	'orderby'        => ['date' => 'DESC'],
	'order'          => 'DESC',
	'no_found_rows'  => true,
];

if ($service_id) {
	// relationship хранит сериализованный массив ID → ищем "123" внутри.
	$query_args['meta_query'] = [
		[
			'key'     => 'review_services',
			'value'   => '"' . $service_id . '"',
			'compare' => 'LIKE',
		],
	];
}

$q = new WP_Query($query_args);

if (!$q->have_posts()) return;
?>

<section class="reviews" aria-label="Отзывы">
	<div class="container reviews__container">
		<div class="reviews__head">
			<h2 class="section__title"><?php echo wp_kses_post($title); ?></h2>

			<div class="reviews__actions desktop">
				<a class="ui__button-arrow ui__button-arrow pink" href="<?php echo esc_url($yandex_url); ?>">
					Мы в Яндекс Картах
					<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
						<circle cx="16.5" cy="16.5" r="16.5" fill="white"></circle>
						<path d="M14.4001 12.1999C14.8419 11.8687 15.4682 11.9583 15.7995 12.4001L18.7995 16.4001C19.066 16.7556 19.066 17.2438 18.7995 17.5993L15.7995 21.5993C15.4682 22.0411 14.8419 22.1308 14.4001 21.7995C13.9583 21.4682 13.8687 20.8419 14.1999 20.4001L16.5341 17.2873L16.7497 16.9997L16.5341 16.7121L14.1999 13.5993C13.8687 13.1575 13.9583 12.5312 14.4001 12.1999Z" fill="#B71375"></path>
					</svg>
				</a>
				<a class="ui__button-arrow ui__button-noarrow blue outline" href="<?php echo esc_url($gis_url); ?>">Мы в 2GIS</a>
			</div>
		</div>

		<div class="reviews__grid">
			<div class="reviews__swiper swiper">
				<div class="reviews__list swiper-wrapper">
					<?php while ($q->have_posts()) : $q->the_post();
						$id = get_the_ID();

						$name = function_exists('get_field') ? trim((string) get_field('review_name', $id)) : '';
						if ($name === '') $name = get_the_title($id);

						$rate = function_exists('get_field') ? trim((string) get_field('review_rate', $id)) : '';
						$text = function_exists('get_field') ? (string) get_field('review_text', $id) : '';

						$source = function_exists('get_field') ? (string) get_field('review_source', $id) : 'yandex';
						$source = in_array($source, ['yandex', 'gis'], true) ? $source : 'yandex';

						$source_class = $source === 'gis'
							? 'review-card__source--gis'
							: 'review-card__source--yandex';
					?>
						<article class="review-card swiper-slide">
							<h3 class="review-card__name"><?php echo esc_html($name); ?></h3>

							<?php if ($rate !== '') : ?>
								<p class="review-card__rate">
									<span class="review-card__rate-value"><?php echo esc_html($rate); ?></span>
									<span class="review-card__rate-star"></span>
								</p>
							<?php endif; ?>

							<?php if (trim(wp_strip_all_tags($text)) !== '') : ?>
								<p class="review-card__text"><?php echo wp_kses_post($text); ?></p>
							<?php endif; ?>

							<div class="review-card__source <?php echo esc_attr($source_class); ?>"></div>
						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				</div>
			</div>

			<aside class="reviews-badge">
				<div class="reviews-badge__title"><?php echo wp_kses_post($badge_title); ?></div>
				<p class="reviews-badge__text"><?php echo wp_kses_post($badge_text); ?></p>
			</aside>
		</div>

		<div class="reviews__actions mobile">
			<a class="ui__button-arrow ui__button-arrow pink" href="<?php echo esc_url($yandex_url); ?>">
				Мы в Яндекс Картах
				<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="16.5" cy="16.5" r="16.5" fill="white"></circle>
					<path d="M14.4001 12.1999C14.8419 11.8687 15.4682 11.9583 15.7995 12.4001L18.7995 16.4001C19.066 16.7556 19.066 17.2438 18.7995 17.5993L15.7995 21.5993C15.4682 22.0411 14.8419 22.1308 14.4001 21.7995C13.9583 21.4682 13.8687 20.8419 14.1999 20.4001L16.5341 17.2873L16.7497 16.9997L16.5341 16.7121L14.1999 13.5993C13.8687 13.1575 13.9583 12.5312 14.4001 12.1999Z" fill="#B71375"></path>
				</svg>
			</a>
			<a class="ui__button-arrow ui__button-noarrow blue outline" href="<?php echo esc_url($gis_url); ?>">Мы в 2GIS</a>
		</div>
	</div>
</section>
