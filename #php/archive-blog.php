<?php
/**
 * Archive template for CPT: blog
 * File: archive-blog.php
 */

get_header();

$title = post_type_archive_title('', false);
if (!$title) {
	$title = 'Полезные статьи';
}
?>

<main>
	<section class="blog-overview">
		<div class="container blog-overview__container">
			<!--ХЛЕБНЫЕ КРОШКИ-->
			<?php get_template_part('template-parts/components/breadcrumbs'); ?>
			
			<h2 class="section__title-big">полезные статьи</h2>

			<div class="blog-overview__grid">
				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>
						<?php
						$intro       = get_field('blog_intro');
						$intro_p1    = isset($intro['p1']) ? $intro['p1'] : '';
						$intro_p2    = isset($intro['p2']) ? $intro['p2'] : '';
						$excerpt_raw = has_excerpt() ? get_the_excerpt() : '';

						// Превью-текст: ACF p1/p2 -> excerpt -> обрезка контента.
						$preview_html = '';
						if (!empty($intro_p1)) {
							$preview_html = $intro_p1;
						} elseif (!empty($intro_p2)) {
							$preview_html = $intro_p2;
						} elseif (!empty($excerpt_raw)) {
							$preview_html = wpautop($excerpt_raw);
						} else {
							$preview_html = wpautop(wp_trim_words(wp_strip_all_tags(get_the_content()), 26, '…'));
						}

						// Заголовок в карточке (можешь оставить только the_title если не надо резать).
						$card_title = get_the_title();
						?>
						<article class="blog-card">
							<a class="blog-card__link" href="<?php the_permalink(); ?>">
								<div class="blog-card__media">
									<?php if (has_post_thumbnail()) : ?>
										<?php the_post_thumbnail('medium_large', [
											'class'   => 'blog-card__img',
											'loading' => 'lazy',
											'alt'     => esc_attr(get_the_title()),
										]); ?>
									<?php else : ?>
										<img class="blog-card__img" src="https://placehold.co/600x400" alt="" loading="lazy" />
									<?php endif; ?>
								</div>

								<div class="blog-card__body">
									<h3 class="blog-card__title"><?php echo esc_html($card_title); ?></h3>





									<span class="blog-card__more"
                                        ><svg
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
                                    ></span>
								</div>
							</a>
						</article>
					<?php endwhile; ?>
				<?php else : ?>
					<p>Пока нет статей.</p>
				<?php endif; ?>
			</div>

			<?php
			// Пагинация: генерим список ссылок, но верстаем под твои классы.
			$links = paginate_links([
				'type'      => 'array',
				'prev_next' => false,
			]);

			$prev_url = get_previous_posts_page_link();
			$next_url = get_next_posts_page_link();
			?>

			<?php if (!empty($links)) : ?>
				<nav class="blog-pager" aria-label="Пагинация статей">
					<?php if ($prev_url) : ?>
						<a class="blog-pager__nav blog-pager__nav--prev" href="<?php echo esc_url($prev_url); ?>" aria-label="Предыдущая">
							   <svg
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
                            </svg>
						</a>
					<?php else : ?>
						<button class="blog-pager__nav blog-pager__nav--prev" type="button" aria-label="Предыдущая" disabled>
							<svg
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
                            </svg>
						</button>
					<?php endif; ?>

					<ul class="blog-pager__list">
						<?php
						foreach ($links as $link_html) {
							// paginate_links отдаёт span.current или a.page-numbers
							$is_current = (strpos($link_html, 'current') !== false);

							// вытащим номер страницы
							$page_num = '';
							if (preg_match('~>(\d+)<~', $link_html, $m)) {
								$page_num = $m[1];
							}

							// если это "…" (dots)
							if (strpos($link_html, 'dots') !== false) {
								echo '<li class="blog-pager__dots">…</li>';
								continue;
							}

							// если ссылка
							if (preg_match('~href="([^"]+)"~', $link_html, $m)) {
								$url = $m[1];
								echo '<li><a class="blog-pager__btn' . ($is_current ? ' is-active' : '') . '" href="' . esc_url($url) . '">' . esc_html($page_num) . '</a></li>';
								continue;
							}

							// текущая (span)
							if ($is_current) {
								echo '<li><span class="blog-pager__btn is-active">' . esc_html($page_num) . '</span></li>';
							}
						}
						?>
					</ul>

					<?php if ($next_url) : ?>
						<a class="blog-pager__nav blog-pager__nav--next" href="<?php echo esc_url($next_url); ?>" aria-label="Следующая">
							<svg
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
                            </svg>
						</a>
					<?php else : ?>
						<button class="blog-pager__nav blog-pager__nav--next" type="button" aria-label="Следующая" disabled>
							<svg
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
                            </svg>
						</button>
					<?php endif; ?>
				</nav>
			<?php endif; ?>

		</div>
	</section>

	
	<section class="text-section">
		<div class="text-section__container container">
			<div class="text-section__wrapper">
				<h2 class="section__title"></h2>
				<p class="section__subtitle"></p>
				<div class="text-section__text">
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
						<li>
							Как ухаживать за разными поверхностями? Рекомендации по чистке ковров, мебели, кухни и
							сантехники.
						</li>
						<li>
							Что важно для здоровья? Статьи об устранении аллергенов, дезинфекции и выборе безопасных
							средств.
						</li>
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

