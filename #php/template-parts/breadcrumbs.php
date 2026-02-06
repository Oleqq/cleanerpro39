<?php
/**
 * Компонент: Хлебные крошки
 *
 * Использование:
 * get_template_part('template-parts/components/breadcrumbs');
 */

if (is_front_page()) {
	return;
}

$items = [];

// Главная
$items[] = [
	'label' => 'Главная',
	'url'   => home_url('/'),
];

// Страница
if (is_page()) {
	$parents = array_reverse(get_post_ancestors(get_the_ID()));

	foreach ($parents as $parent_id) {
		$items[] = [
			'label' => get_the_title($parent_id),
			'url'   => get_permalink($parent_id),
		];
	}

	$items[] = [
		'label'   => get_the_title(),
		'current' => true,
	];
}

// Одиночный пост / CPT
elseif (is_single()) {
	$post_type = get_post_type();
	$post_type_obj = get_post_type_object($post_type);

	if ($post_type !== 'post' && $post_type_obj && !empty($post_type_obj->has_archive)) {
		$items[] = [
			'label' => $post_type_obj->labels->name,
			'url'   => get_post_type_archive_link($post_type),
		];
	}

	$items[] = [
		'label'   => get_the_title(),
		'current' => true,
	];
}

// Архив CPT
elseif (is_post_type_archive()) {
	$obj = get_queried_object();
	if ($obj) {
		$items[] = [
			'label'   => $obj->labels->name,
			'current' => true,
		];
	}
}

// Категории / таксономии
elseif (is_tax() || is_category()) {
	$term = get_queried_object();
	if ($term) {
		$items[] = [
			'label'   => $term->name,
			'current' => true,
		];
	}
}

if (count($items) < 2) {
	return;
}
?>

<nav class="breadcrumbs" aria-label="Хлебные крошки">
	<ol class="breadcrumbs__list">
		<?php foreach ($items as $index => $item) : ?>
			<li class="breadcrumbs__item">
				<?php if (!empty($item['url']) && empty($item['current'])) : ?>
					<a class="breadcrumbs__link" href="<?php echo esc_url($item['url']); ?>">
						<?php echo esc_html($item['label']); ?>
					</a>
				<?php else : ?>
					<span class="breadcrumbs__current" aria-current="page">
						<?php echo esc_html($item['label']); ?>
					</span>
				<?php endif; ?>
			</li>

			<?php if ($index < count($items) - 1) : ?>
				<li class="breadcrumbs__sep" aria-hidden="true"></li>
			<?php endif; ?>
		<?php endforeach; ?>
	</ol>
</nav>
