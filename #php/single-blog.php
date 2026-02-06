<?php
/**
 * Template: Single Blog (CPT: blog)
 */

get_header();

if (have_posts()) :
	while (have_posts()) :
		the_post();

		$back_url = get_post_type_archive_link('blog');
		$next     = get_next_post(true, '', 'category'); // если используешь category
		// если НЕ хочешь привязку к рубрикам — замени строку выше на:
		// $next = get_next_post(false);

		?>
		<main class="blog-article">
			<div class="container blog-article__container">
				<article class="blog-article__post">

					<h1 class="blog-article__title"><?php the_title(); ?></h1>

					<!-- first screen строго как на макете -->
					<div class="blog-article__intro">
						<div class="blog-article__media">
							<?php if (has_post_thumbnail()) : ?>
								<?php
								the_post_thumbnail('large', [
									'class'   => 'blog-article__img',
									'loading' => 'lazy',
									'alt'     => esc_attr(get_the_title()),
								]);
								?>
							<?php else : ?>
								<img
									class="blog-article__img"
									src="https://placehold.co/700x400"
									alt=""
									loading="lazy"
								/>
							<?php endif; ?>
						</div>

						<div class="blog-article__intro-text">
							<?php if (has_excerpt()) : ?>
								<p><?php echo wp_kses_post(get_the_excerpt()); ?></p>
							<?php endif; ?>

							<?php
							// если надо — можно вывести кусок контента в интро (например первый h2+p),
							// но это уже парсинг, лучше держать интро через excerpt.
							?>
						</div>
					</div>

					<!-- основной WYSIWYG — 1 колонка -->
					<div class="blog-article__content wysiwyg">
						<?php the_content(); ?>
					</div>

					<div class="blog-article__footer">
						<a class="ui__button-arrow blue" href="<?php echo esc_url($back_url ?: home_url('/')); ?>" style="padding: 7px 28px 7px 10px">
							<!-- svg here -->
							Вернуться к просмотру статьи
						</a>

						<?php if ($next) : ?>
							<a class="ui__button-arrow blue" href="<?php echo esc_url(get_permalink($next)); ?>">
								Следующая статья
								<!-- svg here -->
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
								<input
									class="callback-form__input"
									id="cb-name"
									type="text"
									name="name"
									autocomplete="name"
									required
									placeholder=""
								/>
							</div>

							<div class="callback-form__field">
								<label class="callback-form__label" for="cb-phone">
									Телефон:<span class="callback-form__req">*</span>
								</label>
								<input
									class="callback-form__input"
									id="cb-phone"
									type="tel"
									name="phone"
									autocomplete="tel"
									inputmode="tel"
									required
									placeholder=""
								/>
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
		<?php
	endwhile;
endif;

get_footer();
