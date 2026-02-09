<?php
/**
 * Компонент: steps-4
 *
 * Использование:
 * get_template_part('template-parts/components/steps-4');
 */

$data = function_exists('theme_get_steps4_data') ? theme_get_steps4_data() : [];

$title = trim((string) ($data['title'] ?? ''));
$steps = (array) ($data['steps'] ?? []);

$steps = array_values(array_filter($steps, fn($row) => is_array($row)));

if ($title === '' || !$steps) {
	return;
}

// SVG allowlist (минимально безопасно)
$svg_allowed = [
	'svg' => [
		'xmlns' => true, 'width' => true, 'height' => true, 'viewBox' => true, 'fill' => true, 'aria-hidden' => true, 'role' => true,
	],
	'g' => ['clip-path' => true, 'fill' => true],
	'path' => ['d' => true, 'fill' => true],
	'circle' => ['cx' => true, 'cy' => true, 'r' => true, 'fill' => true],
	'rect' => ['width' => true, 'height' => true, 'fill' => true, 'transform' => true],
	'defs' => [],
	'clipPath' => ['id' => true],
	'stop' => ['offset' => true, 'stop-color' => true, 'stop-opacity' => true],
	'linearGradient' => ['id' => true, 'x1' => true, 'x2' => true, 'y1' => true, 'y2' => true, 'gradientUnits' => true],
];
?>

<section class="steps-4">
	<div class="container steps-4__container">
		<h2 class="section__title"><?php echo nl2br(esc_html($title)); ?></h2>

		<div class="steps-4__box">
			<div class="steps-4__grid">
				<?php foreach ($steps as $i => $row) :
					$step_title = trim((string)($row['title'] ?? ''));
					$step_text  = trim((string)($row['text'] ?? ''));
					$icon_svg   = (string)($row['icon_svg'] ?? '');
					$actions    = isset($row['actions']) && is_array($row['actions']) ? $row['actions'] : [];

					if ($step_title === '' && $step_text === '' && trim($icon_svg) === '') continue;

					$is_lead = ($i === 0); // первый — lead как в макете
					$num = $i + 1;
				?>
					<article class="step-card<?php echo $is_lead ? ' step-card--lead' : ''; ?>">
						<div class="step-card__inner">

							<?php if (trim($icon_svg) !== '') : ?>
								<div class="step-card__icon">
									<?php echo wp_kses($icon_svg, $svg_allowed); ?>
								</div>
							<?php endif; ?>

							<?php if ($step_title !== '') : ?>
								<h3 class="step-card__title"><?php echo esc_html($step_title); ?></h3>
							<?php endif; ?>

							<?php if ($step_text !== '') : ?>
								<p class="step-card__text"><?php echo nl2br(esc_html($step_text)); ?></p>
							<?php endif; ?>

							<div class="step-card__num"><?php echo (int) $num; ?></div>

							<?php if ($is_lead && $actions) : ?>
								<div class="step-card__actions">
									<?php
									foreach ($actions as $a) {
										if (!is_array($a)) continue;

										$label = trim((string)($a['label'] ?? ''));
										$url   = trim((string)($a['url'] ?? ''));
										$style = (string)($a['style'] ?? 'blue'); // blue | blue_outline

										if ($label === '' || $url === '') continue;

										$classes = 'ui__button-noarrow blue';
										if ($style === 'blue_outline') $classes .= ' outline';
										?>
										<a class="<?php echo esc_attr($classes); ?>" href="<?php echo esc_url($url); ?>">
											<?php echo esc_html($label); ?>
										</a>
										<?php
									}
									?>
								</div>
							<?php endif; ?>

						</div>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
