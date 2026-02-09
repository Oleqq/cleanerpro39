<?php
/**
 * Компонент: Team (сотрудники)
 *
 * Использование:
 * get_template_part('template-parts/components/team');
 *
 * С кастомом:
 * get_template_part('template-parts/components/team', null, [
 *   'title' => '...',
 *   'subtitle' => '...',
 *   'limit' => 8,
 * ]);
 */

$title = (string) ($args['title'] ?? 'СОТРУДНИКИ КОМПАНИИ<br>«КЛИНЕР ПРО39»');
$subtitle = (string) ($args['subtitle'] ?? 'Команда “Марины Клиннера” — это высококвалифицированные<br>профессионалы, которые заботятся о
вашем здоровье и комфорте.<br>Все наши профессионалы проходят тщательный отбор и стажировку<br>под
руководством опытного наставника.');

$limit = isset($args['limit']) ? (int) $args['limit'] : 12;

$q = new WP_Query([
	'post_type'      => 'team',
	'posts_per_page' => $limit,
	'orderby'        => ['menu_order' => 'ASC', 'title' => 'ASC'],
	'order'          => 'ASC',
	'no_found_rows'  => true,
]);

if (!$q->have_posts()) {
	return;
}
?>

<section class="team" aria-label="Сотрудники">
	<div class="container team__container">
		<h2 class="section__title"><?php echo wp_kses_post($title); ?></h2>

		<?php if (trim(wp_strip_all_tags($subtitle)) !== '') : ?>
			<p class="section__subtitle"><?php echo wp_kses_post($subtitle); ?></p>
		<?php endif; ?>

		<div class="team__swiper">
			<div class="team__list swiper-wrapper">
				<?php while ($q->have_posts()) : $q->the_post();
					$id = get_the_ID();

					$name = get_the_title($id);

					$quote = function_exists('get_field') ? (string) get_field('team_quote', $id) : '';
					$cleanings = function_exists('get_field') ? (int) get_field('team_cleanings', $id) : 0;
					$rating = function_exists('get_field') ? trim((string) get_field('team_rating', $id)) : '';

					$img = get_the_post_thumbnail($id, 'large', [
						'class' => 'team-card__img',
						'loading' => 'lazy',
						'alt' => esc_attr($name),
					]);
				?>
					<div class="team-card swiper-slide">
						<div class="team-card__content">
							<h3 class="team-card__name"><?php echo esc_html($name); ?></h3>

							<?php if (trim(wp_strip_all_tags($quote)) !== '') : ?>
								<p class="team-card__quote"><?php echo wp_kses_post('“' . $quote . '”'); ?></p>
							<?php endif; ?>

							<?php if ($cleanings > 0) : ?>
								<p class="team-card__count"><?php echo esc_html($cleanings); ?> уборок</p>
							<?php endif; ?>
						</div>

						<div class="team-card__photo">
							<?php if ($img) : ?>
								<?php echo $img; ?>
							<?php else : ?>
								<img class="team-card__img" src="https://placehold.co/420x520" alt="<?php echo esc_attr($name); ?>" loading="lazy">
							<?php endif; ?>
						</div>

						<?php if ($rating !== '') : ?>
							<div class="team-card__rating" aria-label="<?php echo esc_attr('Рейтинг ' . $rating . ' из 5'); ?>">
								<span class="team-card__rating-value"><?php echo esc_html($rating); ?></span>
								<span class="team-card__rating-sep">/</span>
								<span class="team-card__rating-max">5</span>
							</div>
						<?php endif; ?>
					</div>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</div>
</section>
