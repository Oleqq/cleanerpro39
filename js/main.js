document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                console.log('Element is visible:', entry.target);
                entry.target.classList.add('animated');
                observer.unobserve(entry.target);
            }
        });
    }, {
        root: null, 
        rootMargin: '0px', 
        threshold: 0.01
    });
  
    const sections = document.querySelectorAll('section');
    console.log('Sections found:', sections);  // Логирование выбранных элементов
  
    sections.forEach(section => {
        observer.observe(section);
    });
});



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


document.addEventListener('DOMContentLoaded', () => {
	const root = document.querySelector('.faq');
	if (!root) return;

	const items = Array.from(root.querySelectorAll('[data-faq-item]'));
	if (!items.length) return;

	const getParts = (item) => {
		const head = item.querySelector('.faq-item__head');
		const body = item.querySelector('.faq-item__body');
		return { head, body };
	};

	// --- init: фикс "криво" = оставляем ОДИН открытый (если есть), иначе все закрыты
	let firstOpen = null;

	items.forEach((item) => {
		const { head, body } = getParts(item);
		if (!head || !body) return;

		// aria связываем стабильно
		if (!body.id) body.id = `faq-${Math.random().toString(16).slice(2)}`;
		head.setAttribute('aria-controls', body.id);

		const isOpen = item.classList.contains('is-open');
		if (isOpen && !firstOpen) firstOpen = item;
	});

	items.forEach((item) => {
		const { head } = getParts(item);
		if (!head) return;

		const shouldBeOpen = firstOpen ? item === firstOpen : item.classList.contains('is-open');
		item.classList.toggle('is-open', shouldBeOpen);
		head.setAttribute('aria-expanded', shouldBeOpen ? 'true' : 'false');
	});

	const closeAllExcept = (except) => {
		items.forEach((item) => {
			if (item === except) return;

			const { head } = getParts(item);
			if (!item.classList.contains('is-open')) return;

			item.classList.remove('is-open');
			if (head) head.setAttribute('aria-expanded', 'false');
		});
	};

	const openItem = (item) => {
		const { head } = getParts(item);
		closeAllExcept(item);
		item.classList.add('is-open');
		if (head) head.setAttribute('aria-expanded', 'true');
	};

	const closeItem = (item) => {
		const { head } = getParts(item);
		item.classList.remove('is-open');
		if (head) head.setAttribute('aria-expanded', 'false');
	};

	const toggleItem = (item) => {
		if (item.classList.contains('is-open')) closeItem(item);
		else openItem(item);
	};

	// --- events (и клики, и клавиатура)
	const heads = items
		.map((it) => getParts(it).head)
		.filter(Boolean);

	heads.forEach((head) => {
		head.addEventListener('click', () => {
			const item = head.closest('[data-faq-item]');
			if (item) toggleItem(item);
		});

		head.addEventListener('keydown', (e) => {
			const idx = heads.indexOf(head);
			if (idx < 0) return;

			if (e.key === 'ArrowDown') {
				e.preventDefault();
				heads[Math.min(idx + 1, heads.length - 1)].focus();
			}

			if (e.key === 'ArrowUp') {
				e.preventDefault();
				heads[Math.max(idx - 1, 0)].focus();
			}

			if (e.key === 'Home') {
				e.preventDefault();
				heads[0].focus();
			}

			if (e.key === 'End') {
				e.preventDefault();
				heads[heads.length - 1].focus();
			}

			if (e.key === 'Enter' || e.key === ' ') {
				e.preventDefault();
				const item = head.closest('[data-faq-item]');
				if (item) toggleItem(item);
			}

			if (e.key === 'Escape') {
				e.preventDefault();
				const item = head.closest('[data-faq-item]');
				if (item) closeItem(item);
			}
		});
	});
});




// before-after.js
document.addEventListener('DOMContentLoaded', () => {
	const BP = 0; // тут не нужен, просто оставляю как стиль проекта

	// --- Swiper (каждый "до-после" = слайд)
	const swiperEl = document.querySelector('.before-after__swiper');
	if (swiperEl) {
		const prev = swiperEl.closest('.before-after__box')?.querySelector('.before-after__nav--prev');
		const next = swiperEl.closest('.before-after__box')?.querySelector('.before-after__nav--next');

		const baSwiper = new Swiper(swiperEl, {
			speed: 450,
			watchOverflow: true,
			wrapperClass: 'before-after__list',
			slideClass: 'before-after__slide',
			slidesPerView: 2,
			spaceBetween: 110,
			navigation: { prevEl: prev, nextEl: next, disabledClass: 'is-disabled' },
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        467: {
          slidesPerView: 1,
          spaceBetween: 64,
        },
        767: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 2,
        },
        1920: {
          slidesPerView: 2,
        },
      }
		});

		// ресет сравнения при смене слайда — чтобы не было "залипаний"
		baSwiper.on('slideChangeTransitionStart', () => {
			const active = baSwiper.slides[baSwiper.activeIndex];
			if (!active) return;
			const cmp = active.querySelector('.ba');
			if (!cmp) return;
			resetCompare(cmp);
		});
	}

	// --- Before/After compare
	const compares = Array.from(document.querySelectorAll('.ba'));
	compares.forEach((cmp) => initCompare(cmp));

	function resetCompare(cmp) {
		const start = Number(cmp.getAttribute('data-start') || 50);
		const range = cmp.querySelector('.ba__range');
		cmp.style.setProperty('--pos', `${start}`);
		if (range) range.value = String(start);
	}

	function initCompare(cmp) {
		const frame = cmp.querySelector('.ba__frame');
		const range = cmp.querySelector('.ba__range');
		if (!frame || !range) return;

		resetCompare(cmp);

		const setPos = (val) => {
			const v = Math.max(0, Math.min(100, val));
			cmp.style.setProperty('--pos', `${v}`);
			range.value = String(v);
		};

		// input range (клава/а11y)
		range.addEventListener('input', () => setPos(Number(range.value)));

		// drag по всему кадру (и на мобилках норм)
		let dragging = false;

		const pointerToPos = (e) => {
			const r = frame.getBoundingClientRect();
			const x = e.clientX - r.left;
			return (x / r.width) * 100;
		};

		const lockSwiper = (locked) => {
			const swiperRoot = cmp.closest('.swiper');
			if (!swiperRoot || !swiperRoot.swiper) return;
			swiperRoot.swiper.allowTouchMove = !locked;
		};

		const onDown = (e) => {
			dragging = true;
			lockSwiper(true);
			frame.setPointerCapture?.(e.pointerId);
			setPos(pointerToPos(e));
		};

		const onMove = (e) => {
			if (!dragging) return;
			setPos(pointerToPos(e));
		};

		const onUp = () => {
			if (!dragging) return;
			dragging = false;
			lockSwiper(false);
		};

		frame.addEventListener('pointerdown', onDown, { passive: true });
		frame.addEventListener('pointermove', onMove, { passive: true });
		frame.addEventListener('pointerup', onUp);
		frame.addEventListener('pointercancel', onUp);

		// клик по картинке тоже двигает (приятно)
		frame.addEventListener('click', (e) => {
			// если это был drag — click прилетит следом, игнорим
			if (dragging) return;
			setPos(pointerToPos(e));
		});
	}
});
