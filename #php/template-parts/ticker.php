<?php
/**
 * Компонент: бегущая строка (ticker)
 *
 * Использование:
 * get_template_part('template-parts/components/ticker'); // берёт из ACF options
 * get_template_part('template-parts/components/ticker', null, ['items' => [...]]); // переопределение
 */

$default_items = [
	'Менеджер будет на связи с вами 24/7',
	'Заключаем договор',
	'Дарим сертификат на последующие уборки',
	'Используем профессиональное оборудование',
	'Качественная химия',
];

$passed_items = $args['items'] ?? null;

if (is_array($passed_items)) {
	$items = $passed_items;
} else {
	// из ACF Options Page
	$items = function_exists('theme_get_ticker_items') ? theme_get_ticker_items() : [];
}

$items = array_values(array_filter(array_map('trim', (array) $items)));

if (!$items) {
	$items = $default_items;
}

if (!$items) {
	return;
}
?>

<section class="ticker" aria-label="Информационная строка">
	<div class="ticker__track">
		<div class="ticker__group">
			<?php foreach ($items as $i => $text) : ?>
				<span class="ticker__item"><?php echo esc_html($text); ?></span>
				<?php if ($i < count($items) - 1) : ?>
					<span class="ticker__separator" aria-hidden="true"></span>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<div class="ticker__group" aria-hidden="true">
			<span class="ticker__separator" aria-hidden="true"></span>
			<?php foreach ($items as $i => $text) : ?>
				<span class="ticker__item"><?php echo esc_html($text); ?></span>
				<?php if ($i < count($items) - 1) : ?>
					<span class="ticker__separator" aria-hidden="true"></span>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
