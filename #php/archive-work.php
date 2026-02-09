<?php
get_header();

$cfg = function_exists('theme_get_works_archive_cfg') ? theme_get_works_archive_cfg() : [];
$text_title   = trim((string) ($cfg['text_title'] ?? ''));
$text_subtitle= (string) ($cfg['text_subtitle'] ?? '');
$text_content = (string) ($cfg['text_content'] ?? '');

$q = new WP_Query([
	'post_type'      => 'work',
	'posts_per_page' => -1,
	'orderby'        => ['menu_order' => 'ASC', 'date' => 'DESC'],
	'no_found_rows'  => true,
]);

?>
<section class="portfolio">
	<div class="container portfolio__container">
		<!--ХЛЕБНЫЕ КРОШКИ-->
		<?php get_template_part('template-parts/components/breadcrumbs'); ?>
		
		<h2 class="section__title">НАШИ РАБОТЫ</h2>

		<div class="portfolio__box">
			<div class="portfolio__list">
				<?php if ($q->have_posts()) : ?>
					<?php $i = 0; ?>
					<?php while ($q->have_posts()) : $q->the_post(); $i++; $id = get_the_ID();

						$before_id = function_exists('get_field') ? (int) get_field('work_ba_before', $id) : 0;
						$after_id  = function_exists('get_field') ? (int) get_field('work_ba_after', $id) : 0;
						$start     = function_exists('get_field') ? (int) get_field('work_ba_start', $id) : 50;
						if ($start < 0) $start = 0;
						if ($start > 100) $start = 100;

						$area  = function_exists('get_field') ? trim((string) get_field('work_area', $id)) : '';
						$time  = function_exists('get_field') ? trim((string) get_field('work_time', $id)) : '';
						$price = function_exists('get_field') ? trim((string) get_field('work_price', $id)) : '';

						$tasks_title = function_exists('get_field') ? trim((string) get_field('work_tasks_title', $id)) : 'Виды выполненных работ:';
						if ($tasks_title === '') $tasks_title = 'Виды выполненных работ:';

						$tasks = function_exists('get_field') ? get_field('work_tasks', $id) : [];
						if (!is_array($tasks)) $tasks = [];

						$hidden_class = ($i > 3) ? ' is-hidden' : '';
					?>
						<article class="portfolio-card<?php echo esc_attr($hidden_class); ?>">
							<div class="portfolio-card__ba">
								<div class="ba" data-start="<?php echo esc_attr($start); ?>">
									<div class="ba__frame">
										
										<?php
											if ($after_id) {
												echo wp_get_attachment_image(
													$after_id,
													'large',
													false,
													[
														'class' => 'ba__img ba__img--after',
														'alt'   => 'После',
														'loading' => 'lazy',
													]
												);
											}
											?>
										<div class="ba__after">
											<?php
										if ($before_id) {
											echo wp_get_attachment_image(
												$before_id,
												'large',
												false,
												[
													'class' => 'ba__img ba__img--before',
													'alt'   => 'До',
													'loading' => 'lazy',
												]
											);
										}
										?>
										</div>

										<div class="ba__label ba__label--before">До</div>
										<div class="ba__label ba__label--after">После</div>
										<div class="ba__handle" aria-hidden="true"></div>
										<input
											class="ba__range"
											type="range"
											min="0"
											max="100"
											value="<?php echo esc_attr($start); ?>"
											aria-label="Сравнение до и после"
										/>
									</div>
								</div>
							</div>

							<div class="portfolio-card__content">
								<div class="portfolio-card__meta">
									<?php if ($area !== '') : ?><span><?php echo esc_html('Площадь: ' . $area); ?></span><?php endif; ?>
									<?php if ($time !== '') : ?><span><?php echo esc_html('Сроки: ' . $time); ?></span><?php endif; ?>
									<?php if ($price !== '') : ?><span><?php echo esc_html('Цена: ' . $price); ?></span><?php endif; ?>
								</div>

								<h3 class="portfolio-card__subtitle"><?php echo esc_html($tasks_title); ?></h3>

								<?php if (!empty($tasks)) : ?>
									<ul class="portfolio-card__list">
										<?php foreach ($tasks as $row) :
											$t = is_array($row) ? trim((string) ($row['text'] ?? '')) : '';
											if ($t === '') continue;
										?>
											<li><?php echo esc_html($t); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>

							<a
								class="portfolio-card__link"
								href="<?php the_permalink(); ?>"
								aria-label="<?php echo esc_attr( wp_strip_all_tags( get_the_title() ) ); ?>"
							></a>

						</article>
					<?php endwhile; wp_reset_postdata(); ?>
				<?php endif; ?>
			</div>

			<?php if ($q->post_count > 3) : ?>
				<div class="portfolio__more">
					<button class="ui__button-noarrow blue" type="button" data-portfolio-more>Показать все</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<style>
.portfolio-card {
	position: relative;
}

.portfolio-card__link {
	position: absolute;
	inset: 0;
	z-index: 2;
	
}


.portfolio-card .ba__range,
.portfolio-card .ba__handle {
	position: relative;
	z-index: 3;
}

</style>

            <?php get_template_part('template-parts/components/steps-4'); ?>
            
			<?php get_template_part('template-parts/components/cta-block'); ?>

<section class="text-section">
	<div class="text-section__container container">
		<div class="text-section__wrapper">
			<h2 class="section__title">
				<?php echo wp_kses_post($text_title !== '' ? $text_title : 'Убедитесь в качестве на практике:<br />ваш дом — наш следующий кейс'); ?>
			</h2>

			<?php if (trim(wp_strip_all_tags($text_subtitle)) !== '') : ?>
				<p class="section__subtitle"><?php echo wp_kses_post($text_subtitle); ?></p>
			<?php endif; ?>

			<?php if (trim(wp_strip_all_tags($text_content)) !== '') : ?>
				<div class="text-section__text" style="margin-top: 36px">
					<?php echo wp_kses_post($text_content); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>



<?php get_footer(); ?>
