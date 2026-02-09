<?php
/**
 * single-service.php
 */

get_header();

the_post();

$hero_title = (string) get_field('service_hero_title');
$hero_title = $hero_title !== '' ? $hero_title : get_the_title();

$hero_main = get_field('service_hero_main_image');
$hero_main_alt = (string) get_field('service_hero_main_alt');

$hero_side = get_field('service_hero_side_image');
$hero_side_alt = (string) get_field('service_hero_side_alt');

$intro = get_field('service_intro_block') ?: [];
$includes = get_field('service_includes_tabs') ?: [];
$works = get_field('service_works_examples') ?: [];
$equip = get_field('service_equipment') ?: [];
$bottom = get_field('service_bottom_text_photo') ?: [];

$placeholder_hero_main = 'https://placehold.co/980x420';
$placeholder_hero_side = 'https://placehold.co/420x420';
$placeholder_intro_img = 'https://placehold.co/630x540';

$default_list_title = 'Виды выполненных работ:';
?>

<main>

	<section class="services-single-hero">
		<div class="container services-single-hero__container">
			<!--ХЛЕБНЫЕ КРОШКИ-->
			<?php get_template_part('template-parts/components/breadcrumbs'); ?>
			<h1 class="services-single-hero__title">
				<?php echo wp_kses_post(nl2br($hero_title)); ?>
			</h1>

			<div class="services-single-hero__media">

				<div class="services-single-hero__img-wrap services-single-hero__img-wrap--main">
					<?php if (!empty($hero_main['url'])) : ?>
						<img
							class="services-single-hero__img"
							src="<?php echo esc_url($hero_main['url']); ?>"
							width="980"
							height="420"
							loading="eager"
							alt="<?php echo esc_attr($hero_main_alt !== '' ? $hero_main_alt : get_the_title()); ?>"
						/>
					<?php else : ?>
						<img
							class="services-single-hero__img"
							src="<?php echo esc_url($placeholder_hero_main); ?>"
							width="980"
							height="420"
							loading="eager"
							alt=""
						/>
					<?php endif; ?>
				</div>

				<div class="services-single-hero__img-wrap services-single-hero__img-wrap--side">
					<?php if (!empty($hero_side['url'])) : ?>
						<img
							class="services-single-hero__img"
							src="<?php echo esc_url($hero_side['url']); ?>"
							width="420"
							height="420"
							loading="lazy"
							alt="<?php echo esc_attr($hero_side_alt !== '' ? $hero_side_alt : get_the_title()); ?>"
						/>
					<?php else : ?>
						<img
							class="services-single-hero__img"
							src="<?php echo esc_url($placeholder_hero_side); ?>"
							width="420"
							height="420"
							loading="lazy"
							alt=""
						/>
					<?php endif; ?>
				</div>

			</div>
		</div>
	</section>

	<?php
	$intro_title = isset($intro['title']) ? (string) $intro['title'] : '';
	$intro_text  = isset($intro['text']) ? (string) $intro['text'] : '';
	$intro_img   = isset($intro['image']) ? $intro['image'] : null;
	$intro_alt   = isset($intro['alt']) ? (string) $intro['alt'] : '';
	?>
	<?php if ($intro_title !== '' || $intro_text !== '' || (!empty($intro_img['url']))) : ?>
		<section class="services-single-text-photo">
			<div class="container services-single-text-photo__container">
				<div class="services-single-text-photo__wrapper">

					<div class="services-single-text-photo__content">
						<?php if ($intro_title !== '') : ?>
							<h2 class="section__title"><?php echo wp_kses_post(nl2br($intro_title)); ?></h2>
						<?php endif; ?>

						<?php if ($intro_text !== '') : ?>
							<div class="section__subtitle">
								<?php echo wp_kses_post($intro_text); ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="services-single-text-photo__media">
						<?php if (!empty($intro_img['url'])) : ?>
							<img
								class="services-single-text-photo__img"
								src="<?php echo esc_url($intro_img['url']); ?>"
								alt="<?php echo esc_attr($intro_alt !== '' ? $intro_alt : get_the_title()); ?>"
								loading="lazy"
							/>
						<?php else : ?>
							<img
								class="services-single-text-photo__img"
								src="<?php echo esc_url($placeholder_intro_img); ?>"
								alt=""
								loading="lazy"
							/>
						<?php endif; ?>
					</div>

				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$includes_title = isset($includes['title']) ? (string) $includes['title'] : '';
	$tabs = isset($includes['tabs']) && is_array($includes['tabs']) ? $includes['tabs'] : [];
	?>
	<?php if ($includes_title !== '' && !empty($tabs)) : ?>
		<section class="includes-tabs">
			<div class="container includes-tabs__container">

				<h2 class="section__title"><?php echo wp_kses_post(nl2br($includes_title)); ?></h2>

				<div class="includes-tabs__controls">
					<div class="includes-tabs__tabs-swiper swiper">
						<div
							class="includes-tabs__tabs includes-tabs__tabs--track swiper-wrapper"
							style="--i-left: 0px; --i-top: 0px; --i-width: 0px; --i-height: 0px"
							role="tablist"
							aria-label="<?php echo esc_attr(wp_strip_all_tags($includes_title)); ?>"
						>
							<?php foreach ($tabs as $i => $tab) :
								$label = isset($tab['label']) ? (string) $tab['label'] : '';
								if ($label === '') continue;

								$tab_id = 'service-tab-' . get_the_ID() . '-' . $i;
								$panel_id = 'service-panel-' . get_the_ID() . '-' . $i;
								$is_active = ($i === 0);
								?>
								<button
									class="swiper-slide includes-tabs__btn<?php echo $is_active ? ' is-active' : ''; ?>"
									type="button"
									data-tab="<?php echo esc_attr($tab_id); ?>"
									aria-controls="<?php echo esc_attr($panel_id); ?>"
									aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
									role="tab"
									id="<?php echo esc_attr($tab_id); ?>"
								>
									<?php echo esc_html($label); ?>
								</button>
							<?php endforeach; ?>

							<span class="includes-tabs__indicator" aria-hidden="true"></span>
						</div>
					</div>
				</div>

				<div class="includes-tabs__panels">
					<?php foreach ($tabs as $i => $tab) :
						$label = isset($tab['label']) ? (string) $tab['label'] : '';
						if ($label === '') continue;

						$columns = isset($tab['columns']) && is_array($tab['columns']) ? $tab['columns'] : [];

						$tab_id = 'service-tab-' . get_the_ID() . '-' . $i;
						$panel_id = 'service-panel-' . get_the_ID() . '-' . $i;
						$is_active = ($i === 0);
						?>
						<div
							class="includes-tabs__panel"
							id="<?php echo esc_attr($panel_id); ?>"
							data-tab-content="<?php echo esc_attr($tab_id); ?>"
							role="tabpanel"
							aria-labelledby="<?php echo esc_attr($tab_id); ?>"
							<?php echo $is_active ? '' : ' hidden'; ?>
						>
							<?php if (!empty($columns)) : ?>
								<div class="includes-tabs__grid">
									<?php foreach ($columns as $col) :
										$col_title = isset($col['title']) ? (string) $col['title'] : '';
										$items = isset($col['items']) && is_array($col['items']) ? $col['items'] : [];
										?>
										<div class="includes-tabs__col">
											<?php if ($col_title !== '') : ?>
												<h3 class="includes-tabs__subtitle"><?php echo esc_html($col_title); ?></h3>
											<?php endif; ?>

											<?php if (!empty($items)) : ?>
												<ul class="includes-tabs__list">
													<?php foreach ($items as $it) :
														$text = isset($it['text']) ? (string) $it['text'] : '';
														if ($text === '') continue;
														?>
														<li><?php echo esc_html($text); ?></li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>

			</div>
		</section>
	<?php endif; ?>

	<?php
	$works_title = isset($works['title']) ? (string) $works['title'] : '';
	$works_link  = isset($works['all_link']) ? (string) $works['all_link'] : '';
	$work_items  = isset($works['items']) && is_array($works['items']) ? $works['items'] : [];
	?>
	<?php if ($works_title !== '' && !empty($work_items)) : ?>
		<section class="works-examples">
			<div class="container">
				<div class="works-examples__head">
					<h2 class="section__title"><?php echo wp_kses_post(nl2br($works_title)); ?></h2>

					<?php if ($works_link !== '') : ?>
						<a class="ui__button-arrow pink" href="<?php echo esc_url($works_link); ?>">
							Все наши работы
							<!-- svg here -->
						</a>
					<?php endif; ?>
				</div>

				<div class="works-examples__slider swiper">
					<div class="works-examples__nav">
						<button class="works-examples__btn works-examples__btn--prev" type="button" aria-label="Назад">
							<!-- svg here -->
						</button>
						<button class="works-examples__btn works-examples__btn--next" type="button" aria-label="Вперёд">
							<!-- svg here -->
						</button>
					</div>

					<div class="works-examples__list swiper-wrapper">
						<?php foreach ($work_items as $idx => $work) :
							$gallery = isset($work['gallery']) && is_array($work['gallery']) ? $work['gallery'] : [];
							$title = isset($work['title']) ? (string) $work['title'] : '';
							$area  = isset($work['area']) ? (string) $work['area'] : '';
							$time  = isset($work['time']) ? (string) $work['time'] : '';
							$price = isset($work['price']) ? (string) $work['price'] : '';

							$list_title = isset($work['list_title']) ? (string) $work['list_title'] : '';
							$list_title = $list_title !== '' ? $list_title : $default_list_title;

							$list = isset($work['list']) && is_array($work['list']) ? $work['list'] : [];
							?>
							<article class="work-card swiper-slide">
								<div class="work-card__media swiper">
									<div class="work-card__media-list swiper-wrapper">
										<?php if (!empty($gallery)) : ?>
											<?php foreach ($gallery as $img) :
												if (empty($img['url'])) continue;
												$alt = !empty($img['alt']) ? $img['alt'] : '';
												?>
												<div class="work-card__media-item swiper-slide">
													<img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($alt); ?>" loading="lazy" />
												</div>
											<?php endforeach; ?>
										<?php else : ?>
											<div class="work-card__media-item swiper-slide">
												<img src="https://placehold.co/600x400" alt="" loading="lazy" />
											</div>
										<?php endif; ?>
									</div>
									<div class="work-card__media-pagination"></div>
								</div>

								<div class="work-card__body">
									<?php if ($title !== '') : ?>
										<h3 class="work-card__title"><?php echo esc_html($title); ?></h3>
									<?php endif; ?>

									<?php if ($area !== '' || $time !== '' || $price !== '') : ?>
										<div class="work-card__meta">
											<?php if ($area !== '') : ?><span><b>Площадь:</b> <?php echo esc_html($area); ?></span><?php endif; ?>
											<?php if ($time !== '') : ?><span><b>Сроки:</b> <?php echo esc_html($time); ?></span><?php endif; ?>
											<?php if ($price !== '') : ?><span><b>Цена:</b> <?php echo esc_html($price); ?></span><?php endif; ?>
										</div>
									<?php endif; ?>

									<?php if (!empty($list)) : ?>
										<h4 class="work-card__subtitle"><?php echo esc_html($list_title); ?></h4>
										<ul class="work-card__list">
											<?php foreach ($list as $it) :
												$text = isset($it['text']) ? (string) $it['text'] : '';
												if ($text === '') continue;
												?>
												<li><?php echo esc_html($text); ?></li>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>

				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php
	$equip_title = isset($equip['title']) ? (string) $equip['title'] : '';
	$equip_items = isset($equip['items']) && is_array($equip['items']) ? $equip['items'] : [];
	?>
	<?php if ($equip_title !== '' && !empty($equip_items)) : ?>
		<section class="equipment">
			<div class="container equipment__container">
				<h2 class="section__title"><?php echo esc_html($equip_title); ?></h2>

				<button class="equipment__nav equipment__nav--prev" type="button" aria-label="Назад">
					<!-- svg here -->
				</button>
				<button class="equipment__nav equipment__nav--next" type="button" aria-label="Вперёд">
					<!-- svg here -->
				</button>

				<div class="equipment__box">
					<div class="equipment__swiper swiper">
						<div class="equipment__list swiper-wrapper">
							<?php foreach ($equip_items as $card) :
								$img = isset($card['image']) ? $card['image'] : null;
								$alt = isset($card['alt']) ? (string) $card['alt'] : '';
								$title = isset($card['title']) ? (string) $card['title'] : '';
								?>
								<article class="equipment-card swiper-slide">
									<div class="equipment-card__media">
										<?php if (!empty($img['url'])) : ?>
											<img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($alt !== '' ? $alt : wp_strip_all_tags($title)); ?>" loading="lazy" />
										<?php else : ?>
											<img src="https://placehold.co/520x300" alt="" loading="lazy" />
										<?php endif; ?>
									</div>
									<?php if ($title !== '') : ?>
										<h3 class="equipment-card__title"><?php echo wp_kses_post(nl2br($title)); ?></h3>
									<?php endif; ?>
								</article>
							<?php endforeach; ?>
						</div>
					</div>
				</div>

			</div>
		</section>
	<?php endif; ?>
	
	<?php get_template_part('template-parts/components/cta-block'); ?>

	<?php
	$bottom_title = isset($bottom['title']) ? (string) $bottom['title'] : '';
	$bottom_text  = isset($bottom['text']) ? (string) $bottom['text'] : '';
	$bottom_img   = isset($bottom['image']) ? $bottom['image'] : null;
	$bottom_alt   = isset($bottom['alt']) ? (string) $bottom['alt'] : '';
	?>
	<?php if ($bottom_title !== '' || $bottom_text !== '' || (!empty($bottom_img['url']))) : ?>
		<section class="services-text-photo">
			<div class="container services-text-photo__container">
				<div class="services-text-photo__wrapper">

					<div class="services-text-photo__content">
						<?php if ($bottom_title !== '') : ?>
							<h2 class="section__title"><?php echo wp_kses_post(nl2br($bottom_title)); ?></h2>
						<?php endif; ?>

						<?php if ($bottom_text !== '') : ?>
							<div class="section__subtitle">
								<?php echo wp_kses_post($bottom_text); ?>
							</div>
						<?php endif; ?>
					</div>

					<div class="services-text-photo__media">
						<?php if (!empty($bottom_img['url'])) : ?>
							<img class="services-text-photo__img" src="<?php echo esc_url($bottom_img['url']); ?>" alt="<?php echo esc_attr($bottom_alt !== '' ? $bottom_alt : get_the_title()); ?>" loading="lazy" />
						<?php else : ?>
							<img class="services-text-photo__img" src="https://placehold.co/630x540" alt="" loading="lazy" />
						<?php endif; ?>
					</div>

				</div>
			</div>
		</section>
	<?php endif; ?>
	
	<?php get_template_part('template-parts/components/ticker'); ?>


</main>

<?php get_footer(); ?>
