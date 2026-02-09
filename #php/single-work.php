<?php
get_header();

$id = (int) get_queried_object_id();

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

$single_title = function_exists('get_field') ? trim((string) get_field('work_single_title', $id)) : '';
$single_sub   = function_exists('get_field') ? (string) get_field('work_single_subtitle', $id) : '';
$single_text  = function_exists('get_field') ? (string) get_field('work_single_text', $id) : '';

$page_title = $single_title !== '' ? $single_title : get_the_title($id);
?>

<section class="portfolio">
	<div class="container portfolio__container">
		<h1 class="section__title"><?php echo esc_html($page_title); ?></h1>

		<?php if (trim(wp_strip_all_tags($single_sub)) !== '') : ?>
			<p class="section__subtitle"><?php echo wp_kses_post($single_sub); ?></p>
		<?php endif; ?>

		<div class="portfolio__box">
			<div class="portfolio__list">
				<article class="portfolio-card">
					<div class="portfolio-card__ba">
						<div class="ba" data-start="<?php echo esc_attr($start); ?>">
							<div class="ba__frame">
								<?php
								if ($before_id) {
									echo wp_get_attachment_image($before_id, 'large', false, [
										'class' => 'ba__img ba__img--before',
										'alt' => 'До',
										'loading' => 'lazy',
									]);
								}
								?>
								<div class="ba__after">
									<?php
									if ($after_id) {
										echo wp_get_attachment_image($after_id, 'large', false, [
											'class' => 'ba__img ba__img--after',
											'alt' => 'После',
											'loading' => 'lazy',
										]);
									}
									?>
								</div>

								<div class="ba__label ba__label--before">До</div>
								<div class="ba__label ba__label--after">После</div>
								<div class="ba__handle" aria-hidden="true"></div>
								<input class="ba__range" type="range" min="0" max="100" value="<?php echo esc_attr($start); ?>" aria-label="Сравнение до и после" />
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
				</article>
			</div>
		</div>
	</div>
</section>

<?php if (trim(wp_strip_all_tags($single_text)) !== '') : ?>
<section class="text-section">
	<div class="text-section__container container">
		<div class="text-section__wrapper">
			<div class="text-section__text" style="margin-top: 36px">
				<?php echo wp_kses_post($single_text); ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<?php get_footer(); ?>
