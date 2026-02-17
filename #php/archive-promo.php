<?php
/**
 * Archive template for CPT "stati"
 */
get_header();
?>

<main>
	<section class="blog-overview">
		<div class="container blog-overview__container">
			<?php get_template_part('template-parts/components/breadcrumbs'); ?>
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
										<svg
                                            width="45"
                                            height="45"
                                            viewBox="0 0 45 45"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <circle cx="22.5" cy="22.5" r="22.5" fill="#26357E" />
                                            <path
                                                d="M19.6872 15.3649C20.3641 14.8574 21.3236 14.9948 21.8313 15.6716L26.4276 21.7996C26.8359 22.3442 26.8359 23.0922 26.4276 23.6368L21.8313 29.7648C21.3236 30.4416 20.3641 30.5789 19.6872 30.0715C19.0104 29.5639 18.873 28.6044 19.3805 27.9276L22.9567 23.1587L23.2871 22.7182L22.9567 22.2776L19.3805 17.5088C18.873 16.8319 19.0104 15.8725 19.6872 15.3649Z"
                                                fill="white"
                                            /></svg
                                    >
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
					'prev_text' => '<span class="sr-only">Предыдущая</span><svg
                                width="45"
                                height="45"
                                viewBox="0 0 45 45"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M22.4121 0.5C10.3124 0.50007 0.5 10.3479 0.5 22.5C0.5 34.6521 10.3124 44.4999 22.4121 44.5C34.5119 44.5 44.3242 34.6521 44.3242 22.5C44.3242 10.3479 34.5119 0.5 22.4121 0.5Z"
                                    stroke="#26357E"
                                />
                                <path
                                    d="M25.2136 15.3649C24.5393 14.8574 23.5836 14.9948 23.078 15.6716L18.4997 21.7996C18.0929 22.3442 18.0929 23.0922 18.4997 23.6368L23.078 29.7648C23.5836 30.4416 24.5393 30.5789 25.2136 30.0715C25.8878 29.5639 26.0246 28.6044 25.5191 27.9276L21.957 23.1587L21.6279 22.7182L21.957 22.2776L25.5191 17.5088C26.0246 16.8319 25.8878 15.8725 25.2136 15.3649Z"
                                    fill="#26357E"
                                />
                            </svg>',
					'next_text' => '<span class="sr-only">Следующая</span><svg
                                width="45"
                                height="45"
                                viewBox="0 0 45 45"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M22.4121 0.5C34.5119 0.50007 44.3242 10.3479 44.3242 22.5C44.3242 34.6521 34.5119 44.4999 22.4121 44.5C10.3123 44.5 0.5 34.6521 0.5 22.5C0.5 10.3479 10.3123 0.5 22.4121 0.5Z"
                                    stroke="#26357E"
                                />
                                <path
                                    d="M19.6106 15.3649C20.2849 14.8574 21.2406 14.9948 21.7463 15.6716L26.3246 21.7996C26.7313 22.3442 26.7313 23.0922 26.3246 23.6368L21.7463 29.7648C21.2406 30.4416 20.2849 30.5789 19.6106 30.0715C18.9364 29.5639 18.7996 28.6044 19.3051 27.9276L22.8673 23.1587L23.1964 22.7182L22.8673 22.2776L19.3051 17.5088C18.7996 16.8319 18.9364 15.8725 19.6106 15.3649Z"
                                    fill="#26357E"
                                />
                            </svg>',
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

	<?php get_template_part('template-parts/components/ticker'); ?>
</main>

<?php get_footer(); ?>
