<?php
/**
 * cleanerpro39 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package cleanerpro39
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function cleanerpro39_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on cleanerpro39, use a find and replace
		* to change 'cleanerpro39' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'cleanerpro39', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'cleanerpro39' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'cleanerpro39_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'cleanerpro39_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function cleanerpro39_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'cleanerpro39_content_width', 640 );
}
add_action( 'after_setup_theme', 'cleanerpro39_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function cleanerpro39_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'cleanerpro39' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'cleanerpro39' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'cleanerpro39_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function cleanerpro39_scripts() {
	wp_enqueue_style( 'cleanerpro39-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'cleanerpro39-style', 'rtl', 'replace' );

	wp_enqueue_script( 'cleanerpro39-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'cleanerpro39_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}







/**
 * Allow SVG uploads in WordPress (admin only) + fix MIME checks.
 */

add_filter('upload_mimes', function ($mimes) {
	// только админы
	if (!current_user_can('manage_options')) {
		return $mimes;
	}

	$mimes['svg']  = 'image/svg+xml';
	$mimes['svgz'] = 'image/svg+xml';

	return $mimes;
});

add_filter('wp_check_filetype_and_ext', function ($data, $file, $filename, $mimes) {
	// только админы
	if (!current_user_can('manage_options')) {
		return $data;
	}

	$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

	if ($ext === 'svg' || $ext === 'svgz') {
		$data['ext']  = $ext;
		$data['type'] = 'image/svg+xml';
	}

	return $data;
}, 10, 4);

/**
 * (Опционально) Показывать SVG превьюшки в медиатеке.
 */
add_filter('wp_prepare_attachment_for_js', function ($response) {
	if (!empty($response['mime']) && $response['mime'] === 'image/svg+xml') {
		$response['image'] = [
			'src' => $response['url'],
		];
	}
	return $response;
});











add_action('wp_enqueue_scripts', function () {
	$theme_uri = get_template_directory_uri();
	$ver = wp_get_theme()->get('Version');

	// Styles
	wp_enqueue_style(
		'cleanerpro-swiper',
		'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css',
		[],
		null
	);

	wp_enqueue_style(
		'cleanerpro-style',
		$theme_uri . '/css/style.css',
		['cleanerpro-swiper'],
		$ver
	);

	// Scripts
	wp_enqueue_script(
		'cleanerpro-swiper',
		'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js',
		[],
		null,
		true
	);

	
	
	wp_enqueue_script(
		'cleanerpro-app',
		$theme_uri . '/js/main.js',
		['cleanerpro-swiper'],
		$ver,
		true
	);
});







// === ACF Options: Global Components ===
add_action('acf/init', function () {
	if (!function_exists('acf_add_options_page')) return;

	$parent = acf_add_options_page([
		'page_title'      => 'Шапка и Подвал сайта',
		'menu_title'      => 'Шапка и Подвал сайта',
		'menu_slug'       => 'theme-global-components',
		'capability'      => 'edit_theme_options',
		'redirect'        => true,
		'position'        => 58,
		'icon_url'        => 'dashicons-admin-generic',
		'update_button'   => 'Сохранить',
		'updated_message' => 'Настройки сохранены',
	]);

	acf_add_options_sub_page([
		'page_title'  => 'Шапка + Футер',
		'menu_title'  => 'Шапка + Футер',
		'parent_slug' => $parent['menu_slug'],
		'menu_slug'   => 'theme-header-settings',
		'capability'  => 'edit_theme_options',
	]);
});


// === Helpers ===

/**
 * Нормализуем tel: оставляем + и цифры
 */
function theme_normalize_tel(string $raw): string {
	$raw = trim($raw);
	if ($raw === '') return '';
	$tel = preg_replace('~[^\d\+]~', '', $raw);
	return (string) $tel;
}

/**
 * Достаём шапку из options.
 */
function theme_get_header_config(): array
{
	if (!function_exists('get_field')) {
		return [
			'phone_label' => '',
			'phone_tel'   => '',
			'cta'         => ['label' => '', 'url' => ''],
			'nav'         => [],
			'nav_alt'     => [],
		];
	}

	$cfg = (array) get_field('header_cfg', 'option');

	$phone_label = trim((string) ($cfg['phone_label'] ?? ''));
	$phone_tel   = theme_normalize_tel((string) ($cfg['phone_tel'] ?? ''));

	if ($phone_tel === '') {
		$phone_tel = theme_normalize_tel($phone_label);
	}

	$cta = (array) ($cfg['cta'] ?? []);
	$cta_label = trim((string) ($cta['label'] ?? ''));
	$cta_url   = trim((string) ($cta['url'] ?? '#'));

	$nav     = is_array($cfg['nav'] ?? null) ? $cfg['nav'] : [];
	$nav_alt = is_array($cfg['nav_alt'] ?? null) ? $cfg['nav_alt'] : [];

	$nav = array_values(array_filter($nav, function ($row) {
		$label = is_array($row) ? trim((string)($row['label'] ?? '')) : '';
		$link  = is_array($row) ? ($row['link'] ?? null) : null;
		return $label !== '' && is_array($link) && !empty($link['url']);
	}));

	$nav_alt = array_values(array_filter($nav_alt, function ($row) {
		$label = is_array($row) ? trim((string)($row['label'] ?? '')) : '';
		$link  = is_array($row) ? ($row['link'] ?? null) : null;
		return $label !== '' && is_array($link) && !empty($link['url']);
	}));

	return [
		'phone_label' => $phone_label,
		'phone_tel'   => $phone_tel,
		'cta'         => ['label' => $cta_label, 'url' => $cta_url],
		'nav'         => $nav,
		'nav_alt'     => $nav_alt,
	];
}

/**
 * Достаём футер из options.
 */
function theme_get_footer_config(): array
{
	if (!function_exists('get_field')) {
		return [
			'cols' => [],
			'contacts' => [
				'phone_label' => '',
				'phone_tel'   => '',
				'email'       => '',
				'address'     => '',
			],
			'region' => [
				'title' => '',
				'text'  => '',
			],
			'pay_title' => '',
		];
	}

	$cfg = (array) get_field('footer_cfg', 'option');

	$cols = is_array($cfg['cols'] ?? null) ? $cfg['cols'] : [];
	$cols = array_values(array_filter($cols, function ($col) {
		$title = is_array($col) ? trim((string)($col['title'] ?? '')) : '';
		return $title !== '';
	}));

	// нормализуем ссылки внутри колонок
	foreach ($cols as &$col) {
		$links = is_array($col['links'] ?? null) ? $col['links'] : [];
		$links = array_values(array_filter($links, function ($row) {
			$link = is_array($row) ? ($row['link'] ?? null) : null;
			return is_array($link) && !empty($link['url']);
		}));
		$col['links'] = $links;
	}
	unset($col);

	$contacts = (array) ($cfg['contacts'] ?? []);
	$phone_label = trim((string) ($contacts['phone_label'] ?? ''));
	$phone_tel   = theme_normalize_tel((string) ($contacts['phone_tel'] ?? ''));

	if ($phone_tel === '') {
		$phone_tel = theme_normalize_tel($phone_label);
	}

	$email   = trim((string) ($contacts['email'] ?? ''));
	$address = trim((string) ($contacts['address'] ?? ''));

	$region = (array) ($cfg['region'] ?? []);
	$region_title = trim((string) ($region['title'] ?? ''));
	$region_text  = trim((string) ($region['text'] ?? ''));

	$pay_title = trim((string) ($cfg['pay_title'] ?? ''));

	return [
		'cols' => $cols,
		'contacts' => [
			'phone_label' => $phone_label,
			'phone_tel'   => $phone_tel,
			'email'       => $email,
			'address'     => $address,
		],
		'region' => [
			'title' => $region_title,
			'text'  => $region_text,
		],
		'pay_title' => $pay_title,
	];
}

/**
 * Универсальный рендер ссылки из ACF Link.
 * $link: ['url','title','target']
 */
function theme_render_acf_link($link, array $attrs = []): void
{
	if (!is_array($link) || empty($link['url'])) return;

	$url    = (string) $link['url'];
	$title  = (string) ($link['title'] ?? '');
	$target = (string) ($link['target'] ?? '');

	$attr_str = '';
	foreach ($attrs as $k => $v) {
		if ($v === null || $v === '') continue;
		$attr_str .= ' ' . esc_attr($k) . '="' . esc_attr($v) . '"';
	}

	echo '<a href="' . esc_url($url) . '"';
	if ($target) echo ' target="' . esc_attr($target) . '" rel="noopener"';
	echo $attr_str . '>';
	echo ($title !== '') ? esc_html($title) : esc_html($url);
	echo '</a>';
}



/**
 * Admin Bar: быстрый доступ к ACF "Шапка + Футер"
 * - отдельным пунктом в админ-баре
 * - и пунктом внутри "Настроить"
 */
add_action('admin_bar_menu', function (WP_Admin_Bar $bar) {
	if (is_admin()) return; // только на фронте
	if (!current_user_can('edit_theme_options')) return;

	$url = admin_url('admin.php?page=theme-header-settings');

	// 1) Отдельный пункт в админбаре
	$bar->add_node([
		'id'    => 'theme-header-footer-settings-top',
		'title' => 'Шапка + Футер',
		'href'  => $url,
		'meta'  => ['title' => 'Открыть настройки шапки и футера'],
	]);

	// 2) Тот же пункт внутри "Настроить"
	$bar->add_node([
		'id'     => 'theme-header-footer-settings-customize',
		'parent' => 'customize',
		'title'  => 'Шапка + Футер',
		'href'   => $url,
		'meta'   => ['title' => 'Открыть настройки шапки и футера'],
	]);
}, 80);









add_action('init', function () {
	$labels = [
		'name'                  => 'Статьи',
		'singular_name'         => 'Статья',
		'menu_name'             => 'Статьи',
		'name_admin_bar'        => 'Статья',
		'add_new'               => 'Добавить',
		'add_new_item'          => 'Добавить статью',
		'new_item'              => 'Новая статья',
		'edit_item'             => 'Редактировать статью',
		'view_item'             => 'Посмотреть статью',
		'all_items'             => 'Все статьи',
		'search_items'          => 'Найти статьи',
		'not_found'             => 'Статей не найдено',
		'not_found_in_trash'    => 'В корзине статей нет',
	];

	register_post_type('blog', [
		'labels'             => $labels,
		'public'             => true,
		'show_in_rest'       => true, // Gutenberg + REST
		'menu_position'      => 5,
		'menu_icon'          => 'dashicons-welcome-write-blog',

		'supports'           => [
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'revisions',
		],


		'taxonomies'         => ['category', 'post_tag'],


		'has_archive'        => true, // /blog/
		'rewrite'            => [
			'slug'       => 'blog',
			'with_front' => false,
		],


		'publicly_queryable' => true,
		'query_var'          => true,
	]);
}, 0);





add_action('init', function () {
	$labels = [
		'name'                  => 'Акции',
		'singular_name'         => 'Акция',
		'menu_name'             => 'Акции',
		'name_admin_bar'        => 'Акция',
		'add_new'               => 'Добавить',
		'add_new_item'          => 'Добавить акцию',
		'new_item'              => 'Новая акция',
		'edit_item'             => 'Редактировать акцию',
		'view_item'             => 'Посмотреть акцию',
		'all_items'             => 'Все акции',
		'search_items'          => 'Найти акцию',
		'not_found'             => 'Акций не найдено',
		'not_found_in_trash'    => 'В корзине акций нет',
	];

	register_post_type('promo', [
		'labels'             => $labels,
		'public'             => true,
		'show_in_rest'       => true,
		'menu_position'      => 6,
		'menu_icon'          => 'dashicons-megaphone',

		'supports'           => [
			'title',
			'editor',
			'thumbnail',
			'excerpt',
			'revisions',
		],

		'taxonomies'         => ['category', 'post_tag'],

		'has_archive'        => true, // /promo/
		'rewrite'            => [
			'slug'       => 'promo',
			'with_front' => false,
		],

		'publicly_queryable' => true,
		'query_var'          => true,
	]);
}, 0);






/**
 * CPT: Услуги
 * - service (post type)
 * - service_category (taxonomy) — разделы для аккордеона
 */
add_action('init', function () {
	// === CPT: service ===
	$labels = [
		'name'                  => 'Услуги',
		'singular_name'         => 'Услуга',
		'menu_name'             => 'Услуги',
		'name_admin_bar'        => 'Услуга',
		'add_new'               => 'Добавить',
		'add_new_item'          => 'Добавить услугу',
		'new_item'              => 'Новая услуга',
		'edit_item'             => 'Редактировать услугу',
		'view_item'             => 'Посмотреть услугу',
		'all_items'             => 'Все услуги',
		'search_items'          => 'Найти услугу',
		'not_found'             => 'Услуги не найдены',
		'not_found_in_trash'    => 'В корзине услуг нет',
		'featured_image'        => 'Картинка карточки',
		'set_featured_image'    => 'Установить картинку карточки',
		'remove_featured_image' => 'Удалить картинку карточки',
		'use_featured_image'    => 'Использовать как картинку карточки',
	];

	register_post_type('service', [
		'labels'             => $labels,
		'public'             => true,
		'show_in_rest'       => true,
		'has_archive'        => true, // archive-service.php
		'rewrite'            => ['slug' => 'services', 'with_front' => false],
		'menu_icon'          => 'dashicons-hammer', // можно поменять
		'supports'           => [
			'title',
			'thumbnail',     // картинка карточки
			'page-attributes'// menu_order для ручной сортировки
		],
		'hierarchical'       => false,
		'menu_position'      => 20,
	]);

	// === Taxonomy: service_category ===
	$tax_labels = [
		'name'              => 'Разделы услуг (аккордеон)',
		'singular_name'     => 'Раздел услуг',
		'search_items'      => 'Найти раздел',
		'all_items'         => 'Все разделы',
		'parent_item'       => 'Родительский раздел',
		'parent_item_colon' => 'Родительский раздел:',
		'edit_item'         => 'Редактировать раздел',
		'update_item'       => 'Обновить раздел',
		'add_new_item'      => 'Добавить раздел',
		'new_item_name'     => 'Название нового раздела',
		'menu_name'         => 'Разделы услуг',
	];

	register_taxonomy('service_category', ['service'], [
		'labels'            => $tax_labels,
		'public'            => true,
		'show_in_rest'      => true,
		'hierarchical'      => true, // важное: категории как дерево
		'show_admin_column' => true,
		'rewrite'           => ['slug' => 'service-category', 'with_front' => false],
	]);
}, 0);


/**
 * Админ-качество:
 * - сортировка услуг по menu_order + title (чтобы в каждом разделе красиво руками управлять)
 */
add_action('pre_get_posts', function ($q) {
	if (is_admin() || !$q->is_main_query()) return;

	if ($q->get('post_type') === 'service') {
		$q->set('orderby', ['menu_order' => 'ASC', 'title' => 'ASC']);
	}
});


/**
 * Колонки в админке (чтобы менеджеру было проще).
 */
add_filter('manage_service_posts_columns', function ($cols) {
	$cols['service_category'] = 'Раздел';
	$cols['service_price'] = 'Цена (от)';
	$cols['menu_order'] = 'Порядок';
	return $cols;
});

add_action('manage_service_posts_custom_column', function ($col, $post_id) {
	if ($col === 'service_category') {
		$terms = get_the_terms($post_id, 'service_category');
		if (!empty($terms) && !is_wp_error($terms)) {
			echo esc_html(implode(', ', wp_list_pluck($terms, 'name')));
		} else {
			echo '—';
		}
	}

	if ($col === 'service_price') {
		$val = get_field('service_price', $post_id);
		echo $val ? esc_html($val) : '—';
	}

	if ($col === 'menu_order') {
		echo (int) get_post_field('menu_order', $post_id);
	}
}, 10, 2);


/**
 * Чтобы редактор не путался: отключаем стандартный контент editor у "Услуг".
 * Всё ведём через ACF поля (WYSIWYG).
 */
add_action('init', function () {
	remove_post_type_support('service', 'editor');
}, 100);






/**
 * ACF: страница настроек "Бегущая строка"
 * 
 */
add_action('acf/init', function () {
	if (!function_exists('acf_add_options_page')) {
		return;
	}

	acf_add_options_page([
		'page_title'  => 'Бегущая строка',
		'menu_title'  => 'Бегущая строка',
		'menu_slug'   => 'ticker-settings',
		'capability'  => 'manage_options',
		'redirect'    => false,
		'position'    => 58, // рядом с "Внешний вид"
		'icon_url'    => 'dashicons-editor-kitchensink',
		'update_button' => 'Сохранить',
		'updated_message' => 'Настройки сохранены',
	]);
});

/**
 * Хелпер: достаём массив строк для тикера из options.
 * Возвращает: array<string>
 */
function theme_get_ticker_items(): array
{
	if (!function_exists('get_field')) {
		return [];
	}

	$rows = get_field('ticker_items', 'option');
	if (!$rows || !is_array($rows)) {
		return [];
	}

	$items = [];

	foreach ($rows as $row) {
		$text = '';
		if (is_array($row) && isset($row['ticker_item_text'])) {
			$text = trim(wp_strip_all_tags((string) $row['ticker_item_text']));
		}

		if ($text !== '') {
			$items[] = $text;
		}
	}

	$items = array_values(array_unique($items));

	return $items;
}




// === Callback form: ACF options page ===
add_action('acf/init', function () {
	if (!function_exists('acf_add_options_page')) return;

	acf_add_options_page([
		'page_title'  => 'Форма обратной связи',
		'menu_title'  => 'Форма обратной связи',
		'menu_slug'   => 'theme-callback-form',
		'capability'  => 'edit_theme_options',
		'redirect'    => false,
		'position'    => 59,
		'icon_url'    => 'dashicons-email-alt',
	]);
});

/**
 * Возвращает конфиг callback-form.
 * - Берёт глобальные значения из options
 * - Если на текущей странице включён override — заменяет
 */
function theme_get_callback_form_config(int $post_id = 0): array
{
	$post_id = $post_id ?: (is_singular() ? (int) get_queried_object_id() : 0);

	$cfg = [
		'anchor_id' => '',
		'title'     => '',
		'subtitle'  => '',
		'cf7'       => '',
		'image'     => null, // array or null
	];

	if (function_exists('get_field')) {
		$cfg['anchor_id'] = (string) get_field('cb_anchor_id', 'option');
		$cfg['title']     = (string) get_field('cb_title', 'option');
		$cfg['subtitle']  = (string) get_field('cb_subtitle', 'option');
		$cfg['cf7']       = (string) get_field('cb_cf7_shortcode', 'option');
		$cfg['image']     = get_field('cb_image', 'option');

		if ($post_id) {
			$override = (bool) get_field('cb_override_enable', $post_id);

			if ($override) {
				$t = (string) get_field('cb_override_title', $post_id);
				$s = (string) get_field('cb_override_subtitle', $post_id);
				$f = (string) get_field('cb_override_cf7_shortcode', $post_id);
				$i = get_field('cb_override_image', $post_id);

				if (trim($t) !== '') $cfg['title'] = $t;
				if (trim($s) !== '') $cfg['subtitle'] = $s;
				if (trim($f) !== '') $cfg['cf7'] = $f;
				if (!empty($i)) $cfg['image'] = $i;
			}
		}
	}

	// дефолты, если вообще ничего не заполнено
	if (trim($cfg['title']) === '') {
		$cfg['title'] = "НЕ НАШЛИ<br>НЕОБХОДИМЫЙ<br>ВИД УБОРКИ?";
	}
	if (trim($cfg['subtitle']) === '') {
		$cfg['subtitle'] = "Оставьте свои контактные данные,<br>мы с Вами свяжемся и обязательно Вам поможем!";
	}

	return $cfg;
}

add_filter('wpcf7_autop_or_not', '__return_false');





add_action('acf/init', function () {
	if (!function_exists('acf_add_options_page')) {
		return;
	}

	acf_add_options_page([
		'page_title'  => 'CTA блок',
		'menu_title'  => 'CTA блок',
		'menu_slug'   => 'CTA-block',
		'capability'  => 'edit_posts',
		'redirect'    => false,
		'position'    => 59,
		'icon_url'    => 'dashicons-format-image',
		'update_button' => 'Сохранить',
		'updated_message' => 'Сохранено',
	]);
});





/**
 * Options Page: 4 шага
 */
add_action('acf/init', function () {
	if (!function_exists('acf_add_options_page')) return;

	acf_add_options_page([
		'page_title'  => '4 шага',
		'menu_title'  => '4 шага',
		'menu_slug'   => 'steps-4',
		'capability'  => 'edit_posts',
		'redirect'    => false,
		'position'    => 58,
		'icon_url'    => 'dashicons-editor-ol',
	]);
});

/**
 * Helper: получить данные компонента из options
 */
function theme_get_steps4_data(): array
{
	if (!function_exists('get_field')) return [];

	$title = (string) get_field('steps4_title', 'option');
	$steps = get_field('steps4_steps', 'option');

	if (!is_array($steps)) $steps = [];

	return [
		'title' => $title,
		'steps' => $steps,
	];
}






/**
 * CPT: Сотрудники
 * - team (post type)
 * сейчас используем для секции на страницах
 * потом легко подключим archive-team.php и single-team.php
 */
add_action('init', function () {
	$labels = [
		'name'                  => 'Сотрудники',
		'singular_name'         => 'Сотрудник',
		'menu_name'             => 'Сотрудники',
		'name_admin_bar'        => 'Сотрудник',
		'add_new'               => 'Добавить',
		'add_new_item'          => 'Добавить сотрудника',
		'new_item'              => 'Новый сотрудник',
		'edit_item'             => 'Редактировать сотрудника',
		'view_item'             => 'Просмотреть',
		'all_items'             => 'Все сотрудники',
		'search_items'          => 'Найти сотрудника',
		'not_found'             => 'Сотрудники не найдены',
		'not_found_in_trash'    => 'В корзине сотрудников нет',
		'featured_image'        => 'Фото сотрудника',
		'set_featured_image'    => 'Установить фото',
		'remove_featured_image' => 'Удалить фото',
		'use_featured_image'    => 'Использовать как фото',
	];

	register_post_type('team', [
		'labels'             => $labels,
		'public'             => true,
		'show_in_rest'       => true,
		'has_archive'        => true, // потом сделаем archive-team.php
		'rewrite'            => ['slug' => 'team', 'with_front' => false],
		'menu_icon'          => 'dashicons-groups',
		'menu_position'      => 21,
		'supports'           => [
			'title',          // имя
			'thumbnail',      // фото сотрудника (лучше чем отдельное поле)
			'page-attributes' // menu_order для ручной сортировки
		],
	]);
}, 0);

/**
 * Сортировка на фронте по menu_order, потом по title — удобно менеджеру.
 */
add_action('pre_get_posts', function ($q) {
	if (is_admin() || !$q->is_main_query()) return;

	if ($q->get('post_type') === 'team') {
		$q->set('orderby', ['menu_order' => 'ASC', 'title' => 'ASC']);
		$q->set('order', 'ASC');
	}
});

/**
 * Админ колонки — чтобы быстро видеть главное.
 */
add_filter('manage_team_posts_columns', function ($cols) {
	$cols['team_rating'] = 'Рейтинг';
	$cols['team_cleanings'] = 'Уборок';
	$cols['menu_order'] = 'Порядок';
	return $cols;
});

add_action('manage_team_posts_custom_column', function ($col, $post_id) {
	if (!function_exists('get_field')) return;

	if ($col === 'team_rating') {
		$val = get_field('team_rating', $post_id);
		echo ($val !== '' && $val !== null) ? esc_html($val) : '—';
	}
	if ($col === 'team_cleanings') {
		$val = get_field('team_cleanings', $post_id);
		echo ($val !== '' && $val !== null) ? esc_html((int) $val) : '—';
	}
	if ($col === 'menu_order') {
		echo (int) get_post_field('menu_order', $post_id);
	}
}, 10, 2);

/**
 * Если ты реально всё ведёшь через ACF — можно отключить editor (он тут и так не нужен).
 */
add_action('init', function () {
	remove_post_type_support('team', 'editor');
}, 100);





/**
 * CPT: Отзывы
 * - review (post type)
 * - привязка к услугам делается через ACF поле (relationship/post_object)
 */
add_action('init', function () {
	$labels = [
		'name'               => 'Отзывы',
		'singular_name'      => 'Отзыв',
		'menu_name'          => 'Отзывы',
		'name_admin_bar'     => 'Отзыв',
		'add_new'            => 'Добавить',
		'add_new_item'       => 'Добавить отзыв',
		'new_item'           => 'Новый отзыв',
		'edit_item'          => 'Редактировать отзыв',
		'view_item'          => 'Просмотреть отзыв',
		'all_items'          => 'Все отзывы',
		'search_items'       => 'Найти отзыв',
		'not_found'          => 'Отзывы не найдены',
		'not_found_in_trash' => 'В корзине отзывов нет',
	];

	register_post_type('review', [
		'labels'        => $labels,
		'public'        => true,
		'show_in_rest'  => true,
		'has_archive'   => false, // пока не надо, потом можно включить и сделать archive-review.php
		'rewrite'       => ['slug' => 'reviews', 'with_front' => false],
		'menu_icon'     => 'dashicons-format-status',
		'menu_position' => 22,
		'supports'      => [
			'title', // можно хранить "Мария, домохозяйка" в title, либо через ACF — ниже будет ACF поле
			'page-attributes',
		],
	]);
}, 0);

/**
 * Админ: сортировка по дате (свежие сверху) + menu_order вторично.
 */
add_action('pre_get_posts', function ($q) {
	if (is_admin() || !$q->is_main_query()) return;
	if ($q->get('post_type') !== 'review') return;

	$q->set('orderby', ['date' => 'DESC', 'menu_order' => 'ASC']);
});

/**
 * Админ колонки: рейтинг / источник / услуги
 */
add_filter('manage_review_posts_columns', function ($cols) {
	$cols['review_rate'] = 'Рейтинг';
	$cols['review_source'] = 'Источник';
	$cols['review_services'] = 'Услуги';
	return $cols;
});

add_action('manage_review_posts_custom_column', function ($col, $post_id) {
	if (!function_exists('get_field')) return;

	if ($col === 'review_rate') {
		$val = (string) get_field('review_rate', $post_id);
		echo trim($val) !== '' ? esc_html($val) : '—';
	}

	if ($col === 'review_source') {
		$val = (string) get_field('review_source', $post_id);
		echo $val ? esc_html($val) : '—';
	}

	if ($col === 'review_services') {
		$services = get_field('review_services', $post_id);
		if (!$services) { echo '—'; return; }

		$names = [];
		if (is_array($services)) {
			foreach ($services as $s) {
				$id = is_object($s) ? (int) $s->ID : (int) $s;
				if ($id) $names[] = get_the_title($id);
			}
		}
		echo $names ? esc_html(implode(', ', $names)) : '—';
	}
}, 10, 2);

/**
 * (опционально) отключаем editor — если весь контент в ACF.
 */
add_action('init', function () {
	remove_post_type_support('review', 'editor');
}, 100);





/**
 * CPT: Наши работы
 * - work (post type)
 */
add_action('init', function () {
	$labels = [
		'name'               => 'Наши работы',
		'singular_name'      => 'Работа',
		'menu_name'          => 'Наши работы',
		'name_admin_bar'     => 'Работа',
		'add_new'            => 'Добавить',
		'add_new_item'       => 'Добавить работу',
		'new_item'           => 'Новая работа',
		'edit_item'          => 'Редактировать работу',
		'view_item'          => 'Посмотреть работу',
		'all_items'          => 'Все работы',
		'search_items'       => 'Найти работу',
		'not_found'          => 'Работы не найдены',
		'not_found_in_trash' => 'В корзине работ нет',
		'featured_image'     => 'Превью (необязательно)',
	];

	register_post_type('work', [
		'labels'        => $labels,
		'public'        => true,
		'show_in_rest'  => true,
		'has_archive'   => true, // archive-work.php
		'rewrite'       => ['slug' => 'works', 'with_front' => false],
		'menu_icon'     => 'dashicons-format-gallery',
		'menu_position' => 21,
		'hierarchical'  => false,
		'supports'      => ['title', 'thumbnail', 'page-attributes'],
	]);
}, 0);

/**
 * Архив: сортировка по menu_order + date
 */
add_action('pre_get_posts', function ($q) {
	if (is_admin() || !$q->is_main_query()) return;

	if ($q->is_post_type_archive('work')) {
		$q->set('orderby', ['menu_order' => 'ASC', 'date' => 'DESC']);
	}
});

/**
 * Если всё ведёте через ACF — убираем editor у work
 */
add_action('init', function () {
	remove_post_type_support('work', 'editor');
}, 100);
