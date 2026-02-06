<?php
/**
 * Archive template for CPT "stati"
 */
get_header();
?>

<main>
	<section class="blog-overview">
		<div class="container blog-overview__container">
			<h2 class="section__title"><?php echo esc_html( post_type_archive_title('', false) ); ?></h2>

			<?php if ( have_posts() ) : ?>
				<div class="blog-overview__grid">
					<?php while ( have_posts() ) : the_post(); ?>
						<article class="blog-card">
							<a class="blog-card__link" href="<?php the_permalink(); ?>">
								<div class="blog-card__media">
									<?php
									if ( has_post_thumbnail() ) {
										the_post_thumbnail('large', [
											'class' => 'blog-card__img',
											'alt'   => esc_attr( get_the_title() ),
											'loading' => 'lazy',
										]);
									} else {
										// можно заменить на свою заглушку из темы
										echo '<img class="blog-card__img" src="https://placehold.co/600x400" alt="" loading="lazy">';
									}
									?>
								</div>

								<div class="blog-card__body">
									<h3 class="blog-card__title"><?php echo nl2br( esc_html( get_the_title() ) ); ?></h3>

									<span class="blog-card__more" aria-hidden="true">
										<!-- SVG HERE: arrow circle -->
									</span>
								</div>
							</a>
						</article>
					<?php endwhile; ?>
				</div>

				<?php
				// Пагинация (семантика + классы под твой UI)
				$pagination = paginate_links([
					'total'     => $wp_query->max_num_pages,
					'current'   => max(1, get_query_var('paged')),
					'type'      => 'array',
					'prev_text' => '<span class="sr-only">Предыдущая</span><!-- SVG HERE: pager prev -->',
					'next_text' => '<span class="sr-only">Следующая</span><!-- SVG HERE: pager next -->',
				]);

				if ( ! empty($pagination) ) :
					$prev = get_previous_posts_link('<!-- SVG HERE: pager prev -->');
					$next = get_next_posts_link('<!-- SVG HERE: pager next -->');
				?>
					<nav class="blog-pager" aria-label="Пагинация статей">
						<?php if ( $prev ) : ?>
							<div class="blog-pager__nav blog-pager__nav--prev"><?php echo $prev; ?></div>
						<?php else : ?>
							<div class="blog-pager__nav blog-pager__nav--prev is-disabled" aria-hidden="true">
								<!-- SVG HERE: pager prev disabled -->
							</div>
						<?php endif; ?>

						<ul class="blog-pager__list">
							<?php foreach ( $pagination as $item ) : ?>
								<li>
									<?php
									// paginate_links возвращает <span class="page-numbers current">, <a class="page-numbers">
									// приводим к твоим классам
									$item = str_replace('page-numbers', 'blog-pager__btn', $item);
									$item = str_replace('current', 'is-active', $item);
									echo $item; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									?>
								</li>
							<?php endforeach; ?>
						</ul>

						<?php if ( $next ) : ?>
							<div class="blog-pager__nav blog-pager__nav--next"><?php echo $next; ?></div>
						<?php else : ?>
							<div class="blog-pager__nav blog-pager__nav--next is-disabled" aria-hidden="true">
								<!-- SVG HERE: pager next disabled -->
							</div>
						<?php endif; ?>
					</nav>
				<?php endif; ?>

			<?php else : ?>
				<p>Пока статей нет.</p>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>
		</div>
	</section>

	<section class="text-section">
		<div class="text-section__container container">
			<div class="text-section__wrapper">
				<h2 class="section__title"></h2>
				<p class="section__subtitle"></p>

				<div class="text-section__text">
					<?php
					// если хочешь — потом заменим на ACF/опции/страницу-источник, сейчас оставляю статикой как у тебя
					?>
					<p>
						Чистота — это не только эстетика, но и залог здоровья, комфорта и сохранности вашего дома. Наш
						блог создан для того, чтобы стать вашим надежным помощником в вопросах профессионального
						клининга и эффективной домашней уборки. Здесь мы делимся экспертными знаниями, проверенными
						методиками и полезными советами, основанными на многолетнем опыте работы в Калининграде.
					</p>
					<p>Мы собрали для вас коллекцию статей, которые ответят на самые важные вопросы:</p>
					<ul>
						<li>Как поддерживать порядок? Секреты регулярной уборки для занятых людей.</li>
						<li>Когда нужны профессионалы? Подробные гайды по генеральной и послеремонтной уборке.</li>
						<li>Как ухаживать за разными поверхностями? Рекомендации по чистке ковров, мебели, кухни и сантехники.</li>
						<li>Что важно для здоровья? Статьи об устранении аллергенов, дезинфекции и выборе безопасных средств.</li>
					</ul>
					<p>
						Изучайте материалы, находите решения для своих задач и узнавайте, как мы можем помочь вам в
						создании идеальной чистоты. Применяйте советы на практике или доверьте заботу о чистоте нам —
						команде «Клинер ПРО39». Ваш дом заслуживает профессионализма.
					</p>
				</div>
			</div>
		</div>
	</section>

	<section class="ticker">
		<div class="ticker__track">
			<div class="ticker__group">
				<span class="ticker__item">Менеджер будет на связи с вами 24/7</span><span class="ticker__separator"></span>
				<span class="ticker__item">Заключаем договор</span><span class="ticker__separator"></span>
				<span class="ticker__item">Дарим сертификат на последующие уборки</span><span class="ticker__separator"></span>
				<span class="ticker__item">Используем профессиональное оборудование</span><span class="ticker__separator"></span>
				<span class="ticker__item">Качественная химия</span>
			</div>
			<div class="ticker__group" aria-hidden="true">
				<span class="ticker__separator"></span><span class="ticker__item">Менеджер будет на связи с вами 24/7</span>
				<span class="ticker__separator"></span><span class="ticker__item">Заключаем договор</span>
				<span class="ticker__separator"></span><span class="ticker__item">Дарим сертификат на последующие уборки</span>
				<span class="ticker__separator"></span><span class="ticker__item">Используем профессиональное оборудование</span>
				<span class="ticker__separator"></span><span class="ticker__item">Качественная химия</span>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
