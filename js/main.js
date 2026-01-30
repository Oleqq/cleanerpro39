document.addEventListener('DOMContentLoaded', () => {
  const BP = 468;
  const el = document.querySelector('.hero-swiper');
  if (!el) return;

  let swiper;

  const check = () => {
    if (window.innerWidth < BP && !swiper) {
      swiper = new Swiper(el, {
        slidesPerView: 1.5, // 👈 видно 1.5 слайда
        spaceBetween: 12,
        pagination: {
          el: el.querySelector('.swiper-pagination'),
          clickable: true,
        },
      });
    }

    if (window.innerWidth >= BP && swiper) {
      swiper.destroy(true, true);
      swiper = null;
    }
  };

  check();
  window.addEventListener('resize', check);
});


document.addEventListener('DOMContentLoaded', () => {
    const BP = 468;
    const el = document.querySelector('.service-features__swiper');
    if (!el) return;

    let swiper;

    const check = () => {
        if (window.innerWidth < BP && !swiper) {
            swiper = new Swiper(el, {
                slidesPerView: 1.5,
                spaceBetween: 12,
                wrapperClass: 'service-features__list',
                slideClass: 'service-features__item',
            });
        }

        if (window.innerWidth >= BP && swiper) {
            swiper.destroy(true, true);
            swiper = null;
        }
    };

    check();
    window.addEventListener('resize', check);
});


document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelector('.services-tabs');
    if (!tabs) return;

    const btns = Array.from(tabs.querySelectorAll('.services-tabs__btn'));
    const indicator = tabs.querySelector('.services-tabs__indicator');
    const sliders = Array.from(document.querySelectorAll('.services__slider.services-swiper'));

    // --- helpers
    const getActiveBtn = () => tabs.querySelector('.services-tabs__btn.is-active') || btns[0];

    const setIndicator = (btn) => {
        if (!indicator || !btn) return;

        const tabsRect = tabs.getBoundingClientRect();
        const r = btn.getBoundingClientRect();

        tabs.style.setProperty('--i-left', `${r.left - tabsRect.left}px`);
        tabs.style.setProperty('--i-top', `${r.top - tabsRect.top}px`);
        tabs.style.setProperty('--i-width', `${r.width}px`);
        tabs.style.setProperty('--i-height', `${r.height}px`);
    };

    const showOnly = (tabId) => {
        sliders.forEach((s) => {
            const isMatch = s.getAttribute('data-tab-content') === tabId;
            s.hidden = !isMatch;
            s.style.display = isMatch ? '' : 'none';
        });
    };

    const getSliderByTab = (tabId) =>
        sliders.find((s) => s.getAttribute('data-tab-content') === tabId) || null;

    // --- Motion system (JS-only, no css edits required)
    const motionOK = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches === false;

    const withTransition = (el, fn) => {
        if (!el) return;
        fn();
        // force reflow so next style changes animate reliably
        void el.offsetHeight; // eslint-disable-line no-unused-expressions
    };

    const animateSwitch = (fromEl, toEl) => {
        if (!motionOK) {
            if (fromEl) { fromEl.style.opacity = ''; fromEl.style.transform = ''; }
            if (toEl) { toEl.style.opacity = ''; toEl.style.transform = ''; }
            return;
        }

        // guard: if same element, do nothing
        if (fromEl === toEl) return;

        // ensure both exist and are measurable
        if (toEl) {
            toEl.style.willChange = 'opacity, transform, filter';
            toEl.style.opacity = '0';
            toEl.style.transform = 'translateY(10px) scale(0.99)';
            toEl.style.filter = 'blur(4px)';
        }

        if (fromEl) {
            fromEl.style.willChange = 'opacity, transform, filter';
        }

        // fade-out current
        if (fromEl) {
            withTransition(fromEl, () => {
                fromEl.style.transition = 'opacity 180ms ease, transform 180ms ease, filter 180ms ease';
                fromEl.style.opacity = '0';
                fromEl.style.transform = 'translateY(-6px) scale(0.99)';
                fromEl.style.filter = 'blur(3px)';
            });
        }

        // fade-in next (slightly delayed for "premium" feel)
        window.setTimeout(() => {
            if (!toEl) return;

            withTransition(toEl, () => {
                toEl.style.transition = 'opacity 240ms cubic-bezier(.2,.8,.2,1), transform 240ms cubic-bezier(.2,.8,.2,1), filter 240ms ease';
                toEl.style.opacity = '1';
                toEl.style.transform = 'translateY(0) scale(1)';
                toEl.style.filter = 'blur(0)';
            });

            // cleanup after animation
            window.setTimeout(() => {
                [fromEl, toEl].forEach((el) => {
                    if (!el) return;
                    el.style.willChange = '';
                    el.style.transition = '';
                    el.style.opacity = '';
                    el.style.transform = '';
                    el.style.filter = '';
                });
            }, 280);
        }, 140);
    };

    // --- init swipers for each slider (multiple tabs => multiple sliders)
    const swipers = new Map();

    const initSwiperFor = (el) => {
        if (!el) return null;
        if (swipers.has(el)) return swipers.get(el);

        const prev = el.querySelector('.services__nav--prev');
        const next = el.querySelector('.services__nav--next');

        const instance = new Swiper(el, {
            wrapperClass: 'services__list',
            slideClass: 'service-card',
            slidesPerView: 4,
            spaceBetween: 32,
            speed: 450,
            watchOverflow: true,
            navigation: {
                prevEl: prev,
                nextEl: next,
                disabledClass: 'is-disabled',
            },
            breakpoints: {
                0: { slidesPerView: 1.15, spaceBetween: 16 },
                468: { slidesPerView: 1.5, spaceBetween: 16 },
                640: { slidesPerView: 2, spaceBetween: 20 },
                1024: { slidesPerView: 3, spaceBetween: 24 },
                1280: { slidesPerView: 4, spaceBetween: 32 },
            },
        });

        swipers.set(el, instance);
        return instance;
    };

    const updateVisibleSwiper = (el) => {
        const sw = initSwiperFor(el);
        if (!sw) return;

        // после show/hide swiper часто думает что width=0 — делаем 2 апдейта
        requestAnimationFrame(() => {
            sw.update();
            requestAnimationFrame(() => sw.update());
        });
    };

    // --- indicator "bounce" feel
    const animateIndicator = (btn) => {
        setIndicator(btn);
        if (!motionOK || !indicator) return;

        indicator.style.willChange = 'left, top, width, height, transform';
        indicator.style.transition =
            'left 260ms cubic-bezier(.2,.9,.2,1), width 260ms cubic-bezier(.2,.9,.2,1), top 260ms cubic-bezier(.2,.9,.2,1), height 260ms cubic-bezier(.2,.9,.2,1), transform 260ms cubic-bezier(.2,.9,.2,1)';
        indicator.style.transform = 'scale(0.96)';

        window.setTimeout(() => {
            indicator.style.transform = 'scale(1)';
            window.setTimeout(() => {
                indicator.style.willChange = '';
                indicator.style.transition = '';
                indicator.style.transform = '';
            }, 280);
        }, 90);
    };

    // --- click
    let activeTabId = null;

    const activate = (btn) => {
        if (!btn) return;

        const tabId = btn.getAttribute('data-tab');
        if (!tabId || tabId === activeTabId) {
            // даже если тот же таб — обновим индикатор (на случай ресайза/скролла)
            animateIndicator(btn);
            return;
        }

        const from = activeTabId ? getSliderByTab(activeTabId) : null;
        const to = getSliderByTab(tabId);

        // update tabs ui
        btns.forEach((b) => b.classList.toggle('is-active', b === btn));
        animateIndicator(btn);

        // show new tab (so it's measurable)
        showOnly(tabId);

        // animate between sliders (JS-only)
        animateSwitch(from, to);

        // update swiper sizes and reset position nicely
        if (to) {
            const sw = initSwiperFor(to);
            if (sw) {
                sw.slideTo(0, 0); // всегда стартуем с начала таба
            }
            updateVisibleSwiper(to);
        }

        activeTabId = tabId;
    };

    btns.forEach((btn) => btn.addEventListener('click', () => activate(btn)));

    // --- init state
    const initial = getActiveBtn();
    const initialTab = initial.getAttribute('data-tab');

    if (initialTab) {
        activeTabId = initialTab;
        showOnly(initialTab);

        // init visual + swiper after layout
        requestAnimationFrame(() => {
            animateIndicator(initial);
            const el = getSliderByTab(initialTab);
            if (el) updateVisibleSwiper(el);
        });
    }

    // --- resize handling (indicator + current swiper)
    let rAF = null;
    window.addEventListener('resize', () => {
        if (rAF) cancelAnimationFrame(rAF);
        rAF = requestAnimationFrame(() => {
            animateIndicator(getActiveBtn());
            const el = activeTabId ? getSliderByTab(activeTabId) : null;
            if (el) updateVisibleSwiper(el);
        });
    });
});


document.addEventListener('DOMContentLoaded', () => {
    const BP = 768;
    const container = document.querySelector('.services__controls');
    const wrapper = document.querySelector('.services-tabs');
    if (!container || !wrapper) return;

    let swiper = null;

    const check = () => {
        if (window.innerWidth < BP && !swiper) {
            // добавляем swiper-контейнер на лету (без правки pug)
            container.classList.add('swiper');

            swiper = new Swiper(container, {
                slidesPerView: 'auto',
        
                freeMode: true,
                watchOverflow: true,
                resistanceRatio: 0.85,
            });
        }

        if (window.innerWidth >= BP && swiper) {
            swiper.destroy(true, true);
            swiper = null;
            container.classList.remove('swiper');
        }
    };

    check();
    window.addEventListener('resize', check);
});


document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('.team__swiper');
    if (!el) return;

    const BP_MOBILE = 1068;
    const slides = el.querySelectorAll('.team-card.swiper-slide');

    let swiper = null;

    const shouldInit = () => {
        if (window.innerWidth <= BP_MOBILE) return true;      // < =767 всегда
        return slides.length > 4;                              // ПК: только если >4
    };

    const init = () => {
        if (swiper) return;

        swiper = new Swiper(el, {
            speed: 450,
            watchOverflow: true,
            wrapperClass: 'team__list',
            slideClass: 'team-card',
            breakpoints: {
                0: { slidesPerView: 1.15 },
                468: { slidesPerView: 1.5 },
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
                1280: { slidesPerView: 4 },
            },
        });
    };

    const destroy = () => {
        if (!swiper) return;
        swiper.destroy(true, true);
        swiper = null;
    };

    const check = () => {
        if (shouldInit()) init();
        else destroy();
    };

    check();
    window.addEventListener('resize', check);
});


document.addEventListener('DOMContentLoaded', () => {
	const BP = 767;
	const el = document.querySelector('.reviews__swiper');
	if (!el) return;

	const slides = el.querySelectorAll('.review-card');
	const hasManySlides = slides.length > 3;

	let swiper = null;

	const needSwiper = () => {
		if (window.innerWidth <= BP) return true;
		if (window.innerWidth > BP && hasManySlides) return true;
		return false;
	};

	const init = () => {
		if (swiper) return;

		swiper = new Swiper(el, {
			wrapperClass: 'reviews__list',
			slideClass: 'review-card',
			slidesPerView: 1,
			spaceBetween: 24,
			speed: 450,
			watchOverflow: true,
			breakpoints: {
				0: {
					slidesPerView: 1.1,
					spaceBetween: 24,
				},
				468: {
					slidesPerView: 1,
					spaceBetween: 24,
				},
				640: {
					slidesPerView: 1,
					spaceBetween: 24,
				},
				768: {
					slidesPerView: 2,
					spaceBetween: 24,
				},
        1024: {
          slidesPerView: 3,
					spaceBetween: 24,
        },
			},
		});
	};

	const destroy = () => {
		if (!swiper) return;
		swiper.destroy(true, true);
		swiper = null;
	};

	const check = () => {
		if (needSwiper()) {
			init();
		} else {
			destroy();
		}
	};

	check();
	window.addEventListener('resize', check);
});
