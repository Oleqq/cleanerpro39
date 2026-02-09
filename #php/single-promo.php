<?php
/**
 * Template: Single Promo (CPT: promo)
 */

get_header();

if (have_posts()) :
  while (have_posts()) :
    the_post();

    $back_url = get_post_type_archive_link('promo');

    // ACF поля (по твоему JSON)
    $intro = function_exists('get_field') ? (array) get_field('blog_intro', get_the_ID()) : [];
    $p1 = (string) ($intro['p1'] ?? '');
    $h2 = (string) ($intro['h2'] ?? '');
    $p2 = (string) ($intro['p2'] ?? '');

    $content = function_exists('get_field') ? (string) get_field('blog_content', get_the_ID()) : '';

    $show_next = function_exists('get_field') ? (bool) get_field('blog_show_next', get_the_ID()) : true;

    // Следующая акция (без привязки к category, потому что это CPT)
    $next = get_next_post(false, '', ''); // или просто get_next_post(false);

    ?>
    <main class="blog-article promo-article">
      <div class="container blog-article__container">
        <?php get_template_part('template-parts/components/breadcrumbs'); ?>

        <article class="blog-article__post">
          <h1 class="blog-article__title"><?php the_title(); ?></h1>

          <div class="blog-article__intro promo-article__intro">
            <div class="blog-article__media">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', [
                  'class' => 'blog-article__img',
                  'loading' => 'lazy',
                  'alt' => esc_attr(get_the_title()),
                ]); ?>
              <?php else : ?>
                <img class="blog-article__img" src="https://placehold.co/700x400" alt="" loading="lazy">
              <?php endif; ?>
            </div>

            <div class="blog-article__intro-text">
              <?php if (trim(wp_strip_all_tags($p1)) !== '') : ?>
                <div class="wysiwyg"><?php echo wp_kses_post($p1); ?></div>
              <?php endif; ?>

              <?php if (trim($h2) !== '') : ?>
                <h2><?php echo esc_html($h2); ?></h2>
              <?php endif; ?>

              <?php if (trim(wp_strip_all_tags($p2)) !== '') : ?>
                <div class="wysiwyg"><?php echo wp_kses_post($p2); ?></div>
              <?php endif; ?>
            </div>
          </div>

          <div class="blog-article__content wysiwyg">
            <?php
              // приоритет — ACF blog_content, иначе fallback на обычный контент
              if (trim(wp_strip_all_tags($content)) !== '') {
                echo wp_kses_post($content);
              } else {
                the_content();
              }
            ?>
          </div>

          <div class="blog-article__footer">
            <a class="ui__button-arrow blue" href="<?php echo esc_url($back_url ?: home_url('/')); ?>" style="padding: 7px 28px 7px 10px">
              <!-- твой SVG назад оставил как есть -->
              <svg width="34" height="33" viewBox="0 0 34 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="16.6946" cy="16.5" rx="16.6946" ry="16.5" transform="matrix(-1 0 0 1 33.3906 0)" fill="white"/>
                <path d="M18.8217 12.1999C18.3747 11.8687 17.7411 11.9583 17.4058 12.4001L14.3704 16.4001C14.1008 16.7556 14.1008 17.2438 14.3704 17.5993L17.4058 21.5993C17.7411 22.0411 18.3747 22.1308 18.8217 21.7995C19.2687 21.4682 19.3594 20.8419 19.0243 20.4001L16.6626 17.2873L16.4444 16.9997L16.6626 16.7121L19.0243 13.5993C19.3594 13.1575 19.2687 12.5312 18.8217 12.1999Z" fill="#26357E"/>
              </svg>
              Вернуться к просмотру акции
            </a>

            <?php if ($show_next && $next) : ?>
              <a class="ui__button-arrow blue" href="<?php echo esc_url(get_permalink($next)); ?>">
                Следующая акция
                <svg width="33" height="33" viewBox="0 0 33 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <circle cx="16.5" cy="16.5" r="16.5" fill="white" />
                  <path d="M14.4001 12.1999C14.8419 11.8687 15.4682 11.9583 15.7995 12.4001L18.7995 16.4001C19.066 16.7556 19.066 17.2438 18.7995 17.5993L15.7995 21.5993C15.4682 22.0411 14.8419 22.1308 14.4001 21.7995C13.9583 21.4682 13.8687 20.8419 14.1999 20.4001L16.5341 17.2873L16.7497 16.9997L16.5341 16.7121L14.1999 13.5993C13.8687 13.1575 13.9583 12.5312 14.4001 12.1999Z" fill="#26357E"/>
                </svg>
              </a>
            <?php endif; ?>
          </div>
        </article>
      </div>
    </main>

<style>

	@media (min-width:1025px) {
		.promo-article .blog-article__media img {
			aspect-ratio: 4 / 1.4;
		}
		
		.promo-article__intro {
			grid-template-columns: 1fr !important;
		}
	}
	
</style>

    <?php get_template_part('template-parts/components/callback-form'); ?>
    <?php get_template_part('template-parts/components/ticker'); ?>

    <?php
  endwhile;
endif;

get_footer();
