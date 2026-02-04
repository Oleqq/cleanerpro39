
// sections animations
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


// hero-swiper
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

// service-features__swiper
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

// .services-tabs
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
			slidesPerView: 1,
			spaceBetween: 110,
			navigation: { prevEl: prev, nextEl: next, disabledClass: 'is-disabled' },
      breakpoints: {
        0: {
          slidesPerView: 1,
        },
        467: {
          slidesPerView: 1,
          spaceBetween: 26,
        },
        567: {
          slidesPerView: 2,
          spaceBetween: 26,
        },
        767: {
          slidesPerView: 2,
        },
        1024: {
          slidesPerView: 2,
          spaceBetween: 26,
        },
        1279: {
            slidesPerView: 1,
        },
        1440: {
            slidesPerView: 1,
        },
        1920: {
          slidesPerView: 1,
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


// footer.js
document.addEventListener('DOMContentLoaded', () => {
	const BP = 767;
	const cols = Array.from(document.querySelectorAll('.footer-col'));
	if (!cols.length) return;

	const isMobile = () => window.innerWidth <= BP;

	const closeAll = (except) => {
		cols.forEach((c) => {
			if (c === except) return;
			c.classList.remove('is-open');
			const btn = c.querySelector('[data-footer-acc]');
			if (btn) btn.setAttribute('aria-expanded', 'false');
		});
	};

	const setA11y = () => {
		cols.forEach((c, i) => {
			const btn = c.querySelector('[data-footer-acc]');
			const body = c.querySelector('.footer-col__body');
			if (!btn || !body) return;

			const id = body.id || `footer-col-${i + 1}`;
			body.id = id;

			btn.setAttribute('aria-controls', id);
			btn.setAttribute('aria-expanded', c.classList.contains('is-open') ? 'true' : 'false');
		});
	};

	const enableMobileAcc = () => {
		cols.forEach((c) => c.classList.remove('is-open')); // старт: всё закрыто как в макете
		setA11y();
	};

	const disableMobileAcc = () => {
		cols.forEach((c) => c.classList.remove('is-open')); // на десктопе аккордеон не нужен
		setA11y();
	};

	const onClick = (e) => {
		const btn = e.target.closest('[data-footer-acc]');
		if (!btn) return;

		// на десктопе клики по "шапке" не должны ломать ссылки/структуру
		if (!isMobile()) return;

		const col = btn.closest('.footer-col');
		if (!col) return;

		const willOpen = !col.classList.contains('is-open');
		closeAll(col);

		col.classList.toggle('is-open', willOpen);
		btn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
	};

	document.addEventListener('click', onClick);

	// init by breakpoint
	const sync = () => {
		if (isMobile()) enableMobileAcc();
		else disableMobileAcc();
	};
	sync();
	window.addEventListener('resize', sync);
});



// blog-overview.js
document.addEventListener('DOMContentLoaded', () => {
	const root = document.querySelector('.blog-overview');
	if (!root) return;

	const grid = root.querySelector('.blog-overview__grid');
	const pager = root.querySelector('.blog-pager');
	if (!grid || !pager) return;

	const btns = Array.from(pager.querySelectorAll('.blog-pager__btn'));
	const prev = pager.querySelector('.blog-pager__nav--prev');
	const next = pager.querySelector('.blog-pager__nav--next');

	const getActive = () => pager.querySelector('.blog-pager__btn.is-active') || btns[0];
	const setActive = (page) => {
		btns.forEach((b) => b.classList.toggle('is-active', b.getAttribute('data-page') === String(page)));
	};

	const setNavDisabled = () => {
		const active = Number(getActive()?.getAttribute('data-page') || 1);
		const pages = btns.map((b) => Number(b.getAttribute('data-page'))).filter((n) => !Number.isNaN(n));
		const max = Math.max(...pages, active);

		if (prev) prev.disabled = active <= 1;
		if (next) next.disabled = active >= max;
	};

	// тут ты подключишь реальный бек/шаблонизатор.
	// я делаю "мок" с плавным рефрешем контента без лома верстки.
	const renderMockPage = (page) => {
		root.classList.add('is-switching');

		// минимально: обновим aria + сместим фокус
		grid.setAttribute('aria-busy', 'true');

		// имитация загрузки (в реале — fetch/рендер)
		window.setTimeout(() => {
			// ⚠️ тут ничего не меняю в DOM намеренно — ты сам подставишь статьи
			grid.setAttribute('aria-busy', 'false');

			// перезапуск анимации
			root.classList.remove('is-switching');
			// reflow чтобы повторно проигрывалась
			void root.offsetWidth;
			root.classList.add('is-switching');

			window.setTimeout(() => root.classList.remove('is-switching'), 280);
		}, 40);
	};

	const goTo = (page) => {
		const p = Number(page);
		if (Number.isNaN(p)) return;

		setActive(p);
		setNavDisabled();
		renderMockPage(p);

		// nice: прокрутка к началу секции при пагинации
		root.scrollIntoView({ behavior: 'smooth', block: 'start' });
	};

	btns.forEach((btn) => {
		btn.addEventListener('click', () => goTo(btn.getAttribute('data-page')));
	});

	if (prev) {
		prev.addEventListener('click', () => {
			const current = Number(getActive()?.getAttribute('data-page') || 1);
			goTo(Math.max(1, current - 1));
		});
	}

	if (next) {
		next.addEventListener('click', () => {
			const current = Number(getActive()?.getAttribute('data-page') || 1);
			goTo(current + 1);
		});
	}

	// init
	setNavDisabled();
});



// blog-article.js
document.addEventListener('DOMContentLoaded', () => {
	const post = document.querySelector('.blog-article__post');
	if (!post) return;

	// nice: внешний линк в контенте — безопасно + UX
	const content = post.querySelector('.wysiwyg');
	if (!content) return;

	content.querySelectorAll('a[href]').forEach((a) => {
		const href = a.getAttribute('href') || '';
		if (!href) return;

		const isHash = href.startsWith('#');
		const isTel = href.startsWith('tel:');
		const isMail = href.startsWith('mailto:');
		const isExternal = !isHash && !isTel && !isMail && (() => {
			try {
				const url = new URL(href, window.location.href);
				return url.origin !== window.location.origin;
			} catch {
				return false;
			}
		})();

		if (isExternal) {
			a.target = '_blank';
			a.rel = 'noopener noreferrer';
		}
	});

	// optional: плавный скролл по якорям внутри статьи
	content.addEventListener('click', (e) => {
		const a = e.target.closest('a[href^="#"]');
		if (!a) return;

		const id = a.getAttribute('href');
		if (!id || id === '#') return;

		const target = document.querySelector(id);
		if (!target) return;

		e.preventDefault();
		target.scrollIntoView({ behavior: 'smooth', block: 'start' });
	});
});


// breadcrumbs.js
document.addEventListener('DOMContentLoaded', () => {
	const bc = document.querySelector('.breadcrumbs');
	if (!bc) return;

	const items = Array.from(bc.querySelectorAll('.breadcrumbs__item'));
	const prefersReduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (prefersReduce) return;

	// лёгкая "stagger" анимация на вход (без библиотек)
	items.forEach((it, i) => {
		it.style.opacity = '0';
		it.style.transform = 'translateY(6px)';
		it.style.transition = 'opacity 260ms ease, transform 260ms ease';
		it.style.transitionDelay = `${i * 70}ms`;
	});

	requestAnimationFrame(() => {
		items.forEach((it) => {
			it.style.opacity = '1';
			it.style.transform = 'translateY(0)';
		});
	});
});

// includes-tabs
document.addEventListener('DOMContentLoaded', () => {
	const BP = 767;

	const root = document.querySelector('.includes-tabs');
	if (!root) return;

	const tabsTrack = root.querySelector('.includes-tabs__tabs');
	const btns = Array.from(root.querySelectorAll('.includes-tabs__btn'));
	const panels = Array.from(root.querySelectorAll('.includes-tabs__panel'));
	const swiperEl = root.querySelector('.includes-tabs__tabs-swiper');

	if (!tabsTrack || !btns.length || !panels.length) return;

	let tabsSwiper = null;

	// --- utils
	const normText = (s) =>
		(s || '')
			.replace(/\u00A0/g, ' ')
			.replace(/\s+/g, ' ')
			.trim();

	const slugifyRu = (s) => {
		const map = {
			а: 'a', б: 'b', в: 'v', г: 'g', д: 'd', е: 'e', ё: 'yo', ж: 'zh', з: 'z',
			и: 'i', й: 'y', к: 'k', л: 'l', м: 'm', н: 'n', о: 'o', п: 'p', р: 'r',
			с: 's', т: 't', у: 'u', ф: 'f', х: 'h', ц: 'ts', ч: 'ch', ш: 'sh', щ: 'sch',
			ъ: '', ы: 'y', ь: '', э: 'e', ю: 'yu', я: 'ya',
		};

		const str = normText(s).toLowerCase();

		const tr = str
			.split('')
			.map((ch) => (map[ch] !== undefined ? map[ch] : ch))
			.join('');

		return tr
			.replace(/&/g, ' and ')
			.replace(/[^a-z0-9]+/g, '-')
			.replace(/-+/g, '-')
			.replace(/^-|-$/g, '');
	};

	const ensureUnique = (base, used) => {
		let key = base || 'tab';
		let i = 2;
		while (used.has(key)) {
			key = `${base}-${i}`;
			i += 1;
		}
		used.add(key);
		return key;
	};

	// --- AUTOLINK: buttons[i] <-> panels[i]
	// если панелей меньше/больше — не падаем, просто связываем по min
	const used = new Set();
	const count = Math.min(btns.length, panels.length);

	for (let i = 0; i < count; i++) {
		const btn = btns[i];
		const panel = panels[i];

		const txt = normText(btn.textContent);
		const base = slugifyRu(txt);
		const key = ensureUnique(base, used);

		// проставляем кнопке
		btn.dataset.tab = key;

		const panelId = `includes-tab-${key}`;
		btn.setAttribute('aria-controls', panelId);

		// проставляем панели
		panel.id = panelId;
		panel.dataset.tabContent = key;
		panel.setAttribute('role', 'tabpanel');

		// a11y норм: связываем панель с кнопкой
		if (!btn.id) btn.id = `includes-tabbtn-${key}`;
		panel.setAttribute('aria-labelledby', btn.id);
	}

	// если панелей больше — остальным хотя бы роль выставим
	panels.slice(count).forEach((p) => p.setAttribute('role', 'tabpanel'));

	// --- indicator
	const getActiveBtn = () => root.querySelector('.includes-tabs__btn.is-active') || btns[0];

	const setIndicator = (btn) => {
		if (!btn) return;

		const trackRect = tabsTrack.getBoundingClientRect();
		const r = btn.getBoundingClientRect();

		tabsTrack.style.setProperty('--i-left', `${r.left - trackRect.left}px`);
		tabsTrack.style.setProperty('--i-top', `${r.top - trackRect.top}px`);
		tabsTrack.style.setProperty('--i-width', `${r.width}px`);
		tabsTrack.style.setProperty('--i-height', `${r.height}px`);
	};

	// --- show/hide
	const showPanelById = (panelId) => {
		panels.forEach((p) => {
			p.hidden = p.id !== panelId;
		});
	};

	const activate = (btn) => {
		if (!btn) return;

		btns.forEach((b) => {
			const isActive = b === btn;
			b.classList.toggle('is-active', isActive);
			b.setAttribute('aria-selected', isActive ? 'true' : 'false');
		});

		const panelId = btn.getAttribute('aria-controls');
		if (panelId) showPanelById(panelId);

		requestAnimationFrame(() => setIndicator(btn));

		if (tabsSwiper && typeof tabsSwiper.slideTo === 'function') {
			const idx = btns.indexOf(btn);
			if (idx >= 0) tabsSwiper.slideTo(idx, 220);
		}
	};

	btns.forEach((btn) => btn.addEventListener('click', () => activate(btn)));

	// --- swiper only <= 767
	const initTabsSwiper = () => {
		if (!swiperEl || tabsSwiper) return;

		tabsSwiper = new Swiper(swiperEl, {
			slidesPerView: 'auto',
			
			speed: 350,
			freeMode: true,
			resistanceRatio: 0.6,
			watchOverflow: true,
		});
	};

	const destroyTabsSwiper = () => {
		if (!tabsSwiper) return;
		tabsSwiper.destroy(true, true);
		tabsSwiper = null;
	};

	const check = () => {
		if (window.innerWidth <= BP) initTabsSwiper();
		else destroyTabsSwiper();

		requestAnimationFrame(() => setIndicator(getActiveBtn()));
	};

	// --- init state
	const initial = getActiveBtn();
	const initialPanelId = initial.getAttribute('aria-controls');

	// если панель не скрыта/не выставлено hidden — мы всё равно приводим к одному открытому
	if (initialPanelId) showPanelById(initialPanelId);

	requestAnimationFrame(() => setIndicator(initial));
	check();

	window.addEventListener('resize', check);
});



document.addEventListener('DOMContentLoaded', () => {
	const BP = 767;

	const root = document.querySelector('.works-examples');
	if (!root) return;

	const sliderEl = root.querySelector('.works-examples__slider');
	const cards = Array.from(root.querySelectorAll('.work-card'));

	
	const prevBtn = root.querySelector('.works-examples__btn--prev');
	const nextBtn = root.querySelector('.works-examples__btn--next');

	let cardsSwiper = null;
	const mediaSwipers = new Map();

	// --- inner media sliders
	const initMediaSwiper = (card) => {
		const mediaEl = card.querySelector('.work-card__media');
		if (!mediaEl || mediaSwipers.has(mediaEl)) return;

		const slides = mediaEl.querySelectorAll('.work-card__media-item.swiper-slide');
		const paginationEl = card.querySelector('.work-card__media-pagination');

		const swiper = new Swiper(mediaEl, {
			slidesPerView: 1,
			speed: 400,
			watchOverflow: true,
			// если фоток 1 — пагинация будет залочена через swiper-pagination-lock
           
			pagination: paginationEl 
				? {
						el: paginationEl,
						clickable: true,
                        type: 'progressbar',
				  }
				: undefined,
		});

		// если внутри 0/1 фото — можно скрыть пагинацию руками (на всякий)
		if (paginationEl && slides.length <= 1) paginationEl.style.display = 'none';

		mediaSwipers.set(mediaEl, swiper);
	};

	const destroyMediaSwipers = () => {
		mediaSwipers.forEach((sw) => sw.destroy(true, true));
		mediaSwipers.clear();
	};

	// --- outer buttons show/hide
	const toggleOuterNav = (show) => {
		if (!prevBtn || !nextBtn) return;
		prevBtn.style.display = show ? '' : 'none';
		nextBtn.style.display = show ? '' : 'none';
	};

	// --- cards swiper
	const initCardsSwiper = () => {
		if (cardsSwiper) return;

		cardsSwiper = new Swiper(sliderEl, {
			slidesPerView: 1,
			
			speed: 450,
			watchOverflow: true,
			navigation:
				prevBtn && nextBtn
					? { prevEl: prevBtn, nextEl: nextBtn, disabledClass: 'is-disabled' }
					: undefined,
			breakpoints: {
				768: {
					slidesPerView: 3,
					spaceBetween: 28,
				},
			},
		});
	};

	const destroyCardsSwiper = () => {
		if (!cardsSwiper) return;
		cardsSwiper.destroy(true, true);
		cardsSwiper = null;
	};

	const check = () => {
		const isMobile = window.innerWidth <= BP;
		const needOuterSwiper = isMobile || cards.length > 3;

		// inner sliders always
		cards.forEach(initMediaSwiper);

		// outer slider by your rules
		if (needOuterSwiper) initCardsSwiper();
		else destroyCardsSwiper();

		// nav visibility by your criteria:
		// показываем кнопки только если свайпер реально есть
		// и (мобилка ИЛИ карточек больше 3)
		toggleOuterNav(needOuterSwiper);
	};

	check();
	window.addEventListener('resize', check);
});



document.addEventListener('DOMContentLoaded', () => {
	const BP = 767;

	const root = document.querySelector('.equipment');
	if (!root) return;

	const swiperEl = root.querySelector('.equipment__swiper');
	const slides = root.querySelectorAll('.equipment-card');
	const prev = root.querySelector('.equipment__nav--prev');
	const next = root.querySelector('.equipment__nav--next');

	if (!swiperEl || !slides.length) return;

	let swiper = null;

	const setNavVisibility = (show) => {
		if (!prev || !next) return;
		prev.classList.toggle('is-hidden', !show);
		next.classList.toggle('is-hidden', !show);
	};

	const init = () => {
		if (swiper) return;

		swiper = new Swiper(swiperEl, {
			speed: 450,
			watchOverflow: true,
			slidesPerView: 1,
			spaceBetween: 18,
			navigation: prev && next ? { prevEl: prev, nextEl: next, disabledClass: 'is-disabled' } : undefined,

			breakpoints: {
				768: { slidesPerView: 3, spaceBetween: 34 },
			},
		});

		// если по факту overflow нет — скрываем навигацию
		const updateNav = () => setNavVisibility(swiper && !swiper.isLocked);
		updateNav();
		swiper.on('lock', updateNav);
		swiper.on('unlock', updateNav);
	};

	const destroy = () => {
		if (!swiper) return;
		swiper.destroy(true, true);
		swiper = null;
		setNavVisibility(false);
	};

	const check = () => {
		const isMobile = window.innerWidth <= BP;

		if (isMobile) {
			init();
			setNavVisibility(false); // на мобиле стрелки скрыты по макету/логике
			return;
		}

		// desktop: включаем swiper только если > 3
		if (slides.length > 3) {
			init();
			setNavVisibility(true);
		} else {
			destroy();
		}
	};

	check();
	window.addEventListener('resize', check);
});



document.addEventListener('DOMContentLoaded', () => {
	const root = document.querySelector('.services-accordion');
	if (!root) return;

	const items = Array.from(root.querySelectorAll('.sa-item'));
	if (!items.length) return;

	const closeItem = (item) => {
		item.classList.remove('is-open');
		const head = item.querySelector('.sa-item__head');
		if (head) head.setAttribute('aria-expanded', 'false');
	};

	const openItem = (item) => {
		item.classList.add('is-open');
		const head = item.querySelector('.sa-item__head');
		if (head) head.setAttribute('aria-expanded', 'true');
	};

	// init a11y state
	items.forEach((item) => {
		const head = item.querySelector('.sa-item__head');
		if (!head) return;
		head.setAttribute('aria-expanded', item.classList.contains('is-open') ? 'true' : 'false');
	});

	// if none open -> open first
	if (!items.some((i) => i.classList.contains('is-open'))) openItem(items[0]);

	items.forEach((item) => {
		const head = item.querySelector('.sa-item__head');
		if (!head) return;

		head.addEventListener('click', () => {
			const isOpen = item.classList.contains('is-open');

			// only one open (accordion behavior)
			items.forEach(closeItem);

			if (!isOpen) openItem(item);
		});
	});
});
