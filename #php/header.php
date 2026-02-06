<?php
/**
 * The header for our theme
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="header">
	<div class="container header__container">
		<div class="header__wrapper">
			<a class="header__logo" href="<?php echo esc_url(home_url('/')); ?>">
				<!-- SVGs оставляем как есть (desktop + mobile) -->
				<!-- logo-desktop -->
				<!-- Вставь сюда весь твой SVG .logo-desktop -->
				<!-- logo-mobile -->
				<!-- Вставь сюда весь твой SVG .logo-mobile -->
			</a>

			<nav class="header__nav" aria-label="<?php esc_attr_e('Основная навигация', 'cleanerpro'); ?>">
				<ul>
					<li><a href="#">Наши работы</a></li>
					<li><a href="#">Прайс</a></li>
					<li><a href="#">Оборудование и моющее средства</a></li>
					<li>
						<a href="#">
							<svg width="10" height="13" viewBox="0 0 10 13" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M4.12096 0.418225C4.60604 -0.0516882 4.88366 -0.171891 5.28795 0.298108C5.69229 0.768174 6.03566 1.71991 6.49987 2.83424C6.96389 3.94821 7.00028 4.28638 7.79674 3.34596C8.59283 2.40596 8.36583 2.62484 9.25963 4.69752C10.1536 6.77064 10.2425 8.83166 9.50182 10.178C8.15434 12.627 5.12092 13.488 2.61315 12.178C-0.537578 10.5319 -0.560752 7.06699 1.07213 4.45631C1.88008 3.16462 3.6358 0.888259 4.12096 0.418225Z" fill="#B71375"/>
							</svg>
							Акции
						</a>
					</li>
					<li><a href="#">Полезные статьи</a></li>
					<li><a href="#">Полезные статьи</a></li>
					<li><a href="#">+7 (924) 019 69 62</a></li>
				</ul>
			</nav>

			<a class="header__tel-mobile" href="tel:+79240196962">+7 (924) 019 69 62</a>

			<a class="ui__button-arrow blue" href="#">
				Заказать звонок
				<svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
					<circle cx="16.5" cy="16.5" r="16.5" fill="white"/>
					<path d="M14.4001 12.1999C14.8419 11.8687 15.4682 11.9583 15.7995 12.4001L18.7995 16.4001C19.066 16.7556 19.066 17.2438 18.7995 17.5993L15.7995 21.5993C15.4682 22.0411 14.8419 22.1308 14.4001 21.7995C13.9583 21.4682 13.8687 20.8419 14.1999 20.4001L16.5341 17.2873L16.7497 16.9997L16.5341 16.7121L14.1999 13.5993C13.8687 13.1575 13.9583 12.5312 14.4001 12.1999Z" fill="#26357E"/>
				</svg>
			</a>

			<button class="burger-menu-button" type="button" aria-label="<?php esc_attr_e('Открыть меню', 'cleanerpro'); ?>" aria-controls="header-menu" aria-expanded="false">
				<svg width="18" height="12" viewBox="0 0 18 12" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1 12C0.716667 12 0.479333 11.904 0.288 11.712C0.0960001 11.5207 0 11.2833 0 11C0 10.7167 0.0960001 10.4793 0.288 10.288C0.479333 10.096 0.716667 10 1 10H17C17.2833 10 17.5207 10.096 17.712 10.288C17.904 10.4793 18 10.7167 18 11C18 11.2833 17.904 11.5207 17.712 11.712C17.5207 11.904 17.2833 12 17 12H1ZM1 7C0.716667 7 0.479333 6.904 0.288 6.712C0.0960001 6.52067 0 6.28333 0 6C0 5.71667 0.0960001 5.479 0.288 5.287C0.479333 5.09567 0.716667 5 1 5H9H17C17.2833 5 17.5207 5.09567 17.712 5.287C17.904 5.479 18 5.71667 18 6C18 6.28333 17.904 6.52067 17.712 6.712C17.5207 6.904 17.2833 7 17 7C10.7516 7 7.24839 7 1 7ZM1 2C0.716667 2 0.479333 1.90433 0.288 1.713C0.0960001 1.521 0 1.28333 0 1C0 0.716667 0.0960001 0.479 0.288 0.287C0.479333 0.0956668 0.716667 0 1 0C7.24839 0 10.7516 0 17 0C17.2833 0 17.5207 0.0956668 17.712 0.287C17.904 0.479 18 0.716667 18 1C18 1.28333 17.904 1.521 17.712 1.713C17.5207 1.90433 17.2833 2 17 2H1Z" fill="#26357E"/>
				</svg>
			</button>
		</div>

		<div class="header__additional">
			<nav class="header__nav-alt" aria-label="<?php esc_attr_e('Дополнительная навигация', 'cleanerpro'); ?>">
				<ul>
					<li><a href="#">Клининг квартир, домов</a></li>
					<li><a href="#">Услуги для бизнеса</a></li>
					<li><a href="#">Химчистка мебели, ковров</a></li>
					<li><a href="#">Другие клининговые услуги</a></li>
				</ul>
			</nav>
		</div>
	</div>
</header>

<div class="header-menu" id="header-menu" aria-hidden="true">
	<div class="header-menu__overlay" data-menu-close></div>

	<div class="header-menu__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e('Меню', 'cleanerpro'); ?>">
		<button class="header-menu__close" type="button" aria-label="<?php esc_attr_e('Закрыть меню', 'cleanerpro'); ?>" data-menu-close>
			<span class="header-menu__close-ico"></span>
		</button>

		<nav class="header-menu__nav" aria-label="<?php esc_attr_e('Основная навигация', 'cleanerpro'); ?>">
			<ul class="header-menu__list" data-menu-primary></ul>
		</nav>

		<nav class="header-menu__nav header-menu__nav--alt" aria-label="<?php esc_attr_e('Дополнительная навигация', 'cleanerpro'); ?>">
			<ul class="header-menu__list" data-menu-alt></ul>
		</nav>
	</div>
</div>
