<?php
/**
 * Single template for CPT: blog
 * File: single-blog.php
 */

get_header();

if (have_posts()) :
	while (have_posts()) : the_post();

		// ACF: интро (2 абзаца + заголовок H2 и абзац)
		$intro    = get_field('blog_intro');
		$intro_p1 = isset($intro['p1']) ? $intro['p1'] : '';
		$intro_h2 = isset($intro['h2']) ? $intro['h2'] : '';
		$intro_p2 = isset($intro['p2']) ? $intro['p2'] : '';

		// Назад: если пришли с архива — вернёмся туда, иначе на архив CPT.
		$archive_url = get_post_type_archive_link('blog');
		$back_url    = wp_get_referer();
		if (!$back_url || ($archive_url && strpos($back_url, $archive_url) === false)) {
			$back_url = $archive_url ?: home_url('/');
		}

		// Следующая статья (в рамках CPT blog)
		$next = get_next_post(true, '', ''); // по умолчанию same taxonomy, но без таксы будет просто следующий пост
		if ($next && get_post_type($next) !== 'blog') {
			$next = null;
		}

		// Если нужен "следующий" строго в CPT blog — делаем аккуратно через WP_Query
		if (!$next) {
			$q_next = new WP_Query([
				'post_type'      => 'blog',
				'posts_per_page' => 1,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'date_query'     => [
					[
						'before'    => get_the_date('Y-m-d H:i:s'),
						'inclusive' => false,
					],
				],
				'no_found_rows'  => true,
			]);
			if ($q_next->have_posts()) {
				$q_next->the_post();
				$next = get_post();
				wp_reset_postdata();
				// вернём текущий пост обратно
				setup_postdata(get_post(get_the_ID()));
			}
		}
		?>

		<main>
			<main class="blog-article">
				
				
				<div class="container blog-article__container">
					
					<!--ХЛЕБНЫЕ КРОШКИ-->
					<?php get_template_part('template-parts/components/breadcrumbs'); ?>	
					
					<article class="blog-article__post">

						<h1 class="blog-article__title"><?php the_title(); ?></h1>

						<!-- first screen строго как на макете -->
						<div class="blog-article__intro">
							<div class="blog-article__media">
								<?php if (has_post_thumbnail()) : ?>
									<?php the_post_thumbnail('large', [
										'class'   => 'blog-article__img',
										'loading' => 'lazy',
										'alt'     => esc_attr(get_the_title()),
									]); ?>
								<?php else : ?>
									<img class="blog-article__img" src="https://placehold.co/700x400" alt="" loading="lazy" />
								<?php endif; ?>
							</div>

							<div class="blog-article__intro-text">
								<?php
								// Интро как на статике:
								// p (обяз), h2 (опц), p (опц)
								if (!empty($intro_p1)) {
									echo wp_kses_post(wpautop($intro_p1));
								} elseif (has_excerpt()) {
									echo wp_kses_post(wpautop(get_the_excerpt()));
								}

								if (!empty($intro_h2)) {
									echo '<h2>' . esc_html($intro_h2) . '</h2>';
								}

								if (!empty($intro_p2)) {
									echo wp_kses_post(wpautop($intro_p2));
								}
								?>
							</div>
						</div>
						<div class="blog-article__content wysiwyg">
							<?php
							$blog_content = get_field('blog_content');

							if (!empty($blog_content)) {
								// основной вариант — ACF WYSIWYG
								echo wp_kses_post($blog_content);
							} else {
								// фоллбэк на стандартный контент (если вдруг поле не заполнено)
								the_content();
							}
							?>
						</div>



						<div class="blog-article__footer">
							<a class="ui__button-arrow blue" href="<?php echo esc_url($back_url); ?>" style="padding: 7px 28px 7px 10px">
								<svg
                                    width="34"
                                    height="33"
                                    viewBox="0 0 34 33"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <ellipse
                                        cx="16.6946"
                                        cy="16.5"
                                        rx="16.6946"
                                        ry="16.5"
                                        transform="matrix(-1 0 0 1 33.3906 0)"
                                        fill="white"
                                    />
                                    <path
                                        d="M18.8217 12.1999C18.3747 11.8687 17.7411 11.9583 17.4058 12.4001L14.3704 16.4001C14.1008 16.7556 14.1008 17.2438 14.3704 17.5993L17.4058 21.5993C17.7411 22.0411 18.3747 22.1308 18.8217 21.7995C19.2687 21.4682 19.3594 20.8419 19.0243 20.4001L16.6626 17.2873L16.4444 16.9997L16.6626 16.7121L19.0243 13.5993C19.3594 13.1575 19.2687 12.5312 18.8217 12.1999Z"
                                        fill="#26357E"
                                    /></svg
                                >
								Вернуться к просмотру статьи
							</a>

							<?php if ($next) : ?>
								<a class="ui__button-arrow blue" href="<?php echo esc_url(get_permalink($next)); ?>">
									Следующая статья
									<svg
											width="33"
											height="33"
											viewBox="0 0 33 33"
											fill="none"
											xmlns="http://www.w3.org/2000/svg"
										>
											<circle cx="16.5" cy="16.5" r="16.5" fill="white" />
											<path
												d="M14.4001 12.1999C14.8419 11.8687 15.4682 11.9583 15.7995 12.4001L18.7995 16.4001C19.066 16.7556 19.066 17.2438 18.7995 17.5993L15.7995 21.5993C15.4682 22.0411 14.8419 22.1308 14.4001 21.7995C13.9583 21.4682 13.8687 20.8419 14.1999 20.4001L16.5341 17.2873L16.7497 16.9997L16.5341 16.7121L14.1999 13.5993C13.8687 13.1575 13.9583 12.5312 14.4001 12.1999Z"
												fill="#26357E"
											/></svg
									>
								</a>
							<?php endif; ?>
						</div>

					</article>
				</div>
			</main>

			<section class="callback-form">
				<div class="container callback-form__container">
					<div class="callback-form__grid">
						<div class="callback-form__intro">
							<h2 class="callback-form__title">НЕ НАШЛИ<br />НЕОБХОДИМЫЙ<br />ВИД УБОРКИ?</h2>
							<p class="callback-form__subtitle">
								Оставьте свои контактные данные,<br />мы с Вами свяжемся и обязательно Вам поможем!
							</p>
						</div>
						<div class="callback-form__card">
							<form class="callback-form__form" action="#" method="post" novalidate>
								<div class="callback-form__field">
									<label class="callback-form__label" for="cb-name">
										Ваше имя:<span class="callback-form__req">*</span>
									</label>
									<input class="callback-form__input" id="cb-name" type="text" name="name" autocomplete="name" required placeholder="" />
								</div>

								<div class="callback-form__field">
									<label class="callback-form__label" for="cb-phone">
										Телефон:<span class="callback-form__req">*</span>
									</label>
									<input class="callback-form__input" id="cb-phone" type="tel" name="phone" autocomplete="tel" inputmode="tel" required placeholder="" />
								</div>

								<div class="callback-form__agree">
									<label class="callback-form__check">
										<input class="callback-form__check-input" type="checkbox" name="agree" required />
										<span class="callback-form__check-box" aria-hidden="true"></span>
										<span class="callback-form__check-text">
											Отправляя форму, вы даете согласие на обработку<br />
											своих<strong class="callback-form__check-strong"> персональных данных</strong>
										</span>
									</label>
								</div>

								<button class="ui__button-arrow ui__button-arrow pink callback-form__submit" type="submit">
									Отправить заявку
								</button>
							</form>
						</div>

						<div class="callback-form__media">
							<img class="callback-form__img" src="./assets/img/Group 215.png" alt="Фото" loading="lazy" />
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

	<?php
	endwhile;
endif;

get_footer();

