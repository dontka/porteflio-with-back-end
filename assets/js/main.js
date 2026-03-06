"use strict";

document.addEventListener('DOMContentLoaded', function() {

    // ===== Navbar scroll effect =====
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });
    }

    // ===== Mobile menu toggle =====
    const navToggle = document.getElementById('navToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    if (navToggle && mobileMenu) {
        navToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('open');
            navToggle.classList.toggle('open');
        });
        // Close on link click
        mobileMenu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('open');
                navToggle.classList.remove('open');
            });
        });
    }

    // ===== Active nav link on scroll =====
    var navLinksAll = document.querySelectorAll('.nav-links a[href^="#"], .mobile-menu a[href^="#"]');
    var sections = [];
    navLinksAll.forEach(function(link) {
        var id = link.getAttribute('href').substring(1);
        var section = document.getElementById(id);
        if (section) sections.push({ id: id, el: section });
    });
    function updateActiveNav() {
        var scrollPos = window.scrollY + 120;
        var current = '';
        sections.forEach(function(s) {
            if (s.el.offsetTop <= scrollPos) current = s.id;
        });
        navLinksAll.forEach(function(link) {
            link.classList.toggle('active', link.getAttribute('href') === '#' + current);
        });
    }
    if (sections.length) {
        window.addEventListener('scroll', updateActiveNav, { passive: true });
        updateActiveNav();
    }

    // ===== Typing animation =====
    const typedEl = document.getElementById('typed-text');
    if (typedEl) {
        const phrases = [
            'Développeur Web Full-Stack',
            'Analyste de Données',
            'Passionné de Santé & Tech',
            'Développeur PHP / Laravel',
            'Créateur d\'applications Web'
        ];
        var phraseIndex = 0;
        var charIndex = 0;
        var isDeleting = false;

        function typeEffect() {
            var current = phrases[phraseIndex];
            if (isDeleting) {
                typedEl.textContent = current.substring(0, charIndex - 1);
                charIndex--;
            } else {
                typedEl.textContent = current.substring(0, charIndex + 1);
                charIndex++;
            }

            var delay = isDeleting ? 40 : 80;

            if (!isDeleting && charIndex === current.length) {
                delay = 2000;
                isDeleting = true;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                delay = 400;
            }

            setTimeout(typeEffect, delay);
        }
        typeEffect();
    }

    // ===== Counter animation =====
    var countersAnimated = false;
    function animateCounters() {
        if (countersAnimated) return;
        var counters = document.querySelectorAll('.stat-number[data-count]');
        if (counters.length === 0) return;
        countersAnimated = true;

        counters.forEach(function(counter) {
            var target = parseInt(counter.getAttribute('data-count'), 10);
            var duration = 2000;
            var start = 0;
            var startTime = null;

            function animate(timestamp) {
                if (!startTime) startTime = timestamp;
                var progress = Math.min((timestamp - startTime) / duration, 1);
                var eased = 1 - Math.pow(1 - progress, 3);
                counter.textContent = Math.floor(eased * target);
                if (progress < 1) {
                    requestAnimationFrame(animate);
                } else {
                    counter.textContent = target;
                }
            }
            requestAnimationFrame(animate);
        });
    }

    // ===== Skill bars & rings animation =====
    var skillsAnimated = false;
    function animateSkillBars() {
        if (skillsAnimated) return;
        var bars = document.querySelectorAll('.skill-bar-fill[data-width]');
        var rings = document.querySelectorAll('.skill-ring-fill[data-percent]');
        if (bars.length === 0 && rings.length === 0) return;
        skillsAnimated = true;

        var circumference = 2 * Math.PI * 26; // r=26

        bars.forEach(function(bar, index) {
            setTimeout(function() {
                bar.style.width = bar.getAttribute('data-width') + '%';
            }, index * 80);
        });

        rings.forEach(function(ring, index) {
            setTimeout(function() {
                var percent = parseInt(ring.getAttribute('data-percent'), 10);
                var offset = circumference - (circumference * percent / 100);
                ring.style.strokeDashoffset = offset;
            }, index * 80);
        });
    }

    // ===== Scroll reveal =====
    function setupScrollReveal() {
        var revealSections = document.querySelectorAll('.section-block, .stats-bar');
        revealSections.forEach(function(el) {
            el.classList.add('reveal');
        });

        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');

                    if (entry.target.classList.contains('stats-bar')) {
                        animateCounters();
                    }
                    if (entry.target.classList.contains('skills-section')) {
                        animateSkillBars();
                    }
                }
            });
        }, { threshold: 0.15 });

        revealSections.forEach(function(el) {
            observer.observe(el);
        });
    }
    setupScrollReveal();

    // ===== Back to top =====
    var backToTop = document.getElementById('backToTop');
    if (backToTop) {
        window.addEventListener('scroll', function() {
            backToTop.classList.toggle('visible', window.scrollY > 400);
        });
        backToTop.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ===== Dark mode =====
    var darkSwitch = document.getElementById('darkSwitch');
    if (darkSwitch) {
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            darkSwitch.checked = true;
        }
        darkSwitch.addEventListener('change', function() {
            if (this.checked) {
                document.body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                document.body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', null);
            }
        });
    }

    // ===== Smooth scroll for anchor links =====
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var href = this.getAttribute('href');
            if (href === '#') return;
            var target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ===== Bootstrap tooltips =====
    var tooltipTriggers = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggers.forEach(function(el) {
        new bootstrap.Tooltip(el);
    });

    // ===== Services Carousel =====
    (function() {
        var track = document.getElementById('servicesTrack');
        var prevBtn = document.getElementById('servicesPrev');
        var nextBtn = document.getElementById('servicesNext');
        var dotsContainer = document.getElementById('servicesDots');
        if (!track || !prevBtn || !nextBtn || !dotsContainer) return;

        var slides = track.querySelectorAll('.service-slide');
        var totalSlides = slides.length;
        var currentIndex = 0;
        var autoplayTimer;

        function getSlidesPerView() {
            if (window.innerWidth <= 575) return 1;
            if (window.innerWidth <= 991) return 2;
            return 3;
        }

        function getMaxIndex() {
            return Math.max(0, totalSlides - getSlidesPerView());
        }

        function buildDots() {
            dotsContainer.innerHTML = '';
            var count = getMaxIndex() + 1;
            for (var i = 0; i < count; i++) {
                var dot = document.createElement('button');
                dot.className = 'carousel-dot' + (i === currentIndex ? ' active' : '');
                dot.setAttribute('aria-label', 'Slide ' + (i + 1));
                dot.dataset.index = i;
                dot.addEventListener('click', function() {
                    goTo(parseInt(this.dataset.index));
                });
                dotsContainer.appendChild(dot);
            }
        }

        function updateCarousel() {
            var perView = getSlidesPerView();
            var slideWidth = 100 / perView;
            track.style.transform = 'translateX(-' + (currentIndex * slideWidth) + '%)';

            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex >= getMaxIndex();

            var dots = dotsContainer.querySelectorAll('.carousel-dot');
            dots.forEach(function(d, i) {
                d.classList.toggle('active', i === currentIndex);
            });
        }

        function goTo(index) {
            currentIndex = Math.max(0, Math.min(index, getMaxIndex()));
            updateCarousel();
            resetAutoplay();
        }

        function next() {
            goTo(currentIndex + 1);
        }

        function prev() {
            goTo(currentIndex - 1);
        }

        function resetAutoplay() {
            clearInterval(autoplayTimer);
            autoplayTimer = setInterval(function() {
                if (currentIndex >= getMaxIndex()) {
                    goTo(0);
                } else {
                    next();
                }
            }, 5000);
        }

        prevBtn.addEventListener('click', prev);
        nextBtn.addEventListener('click', next);

        window.addEventListener('resize', function() {
            if (currentIndex > getMaxIndex()) currentIndex = getMaxIndex();
            buildDots();
            updateCarousel();
        });

        buildDots();
        updateCarousel();
        resetAutoplay();
    })();
});

/* GitHub Calendar */
if (document.querySelector("#github-graph")) {
    new GitHubCalendar("#github-graph", "dontka", { responsive: true });
}

/* GitHub Activity Feed */
if (document.querySelector("#ghfeed")) {
    GitHubActivity.feed({ username: "dontka", selector: "#ghfeed", limit: 10 });
}

/* Vanilla RSS */
var rssFeedElement = document.querySelector("#rss-feeds");
if (rssFeedElement) {
    var rss = new RSS(
        rssFeedElement,
        "https://feeds.feedburner.com/TechCrunch/startups",
        {
            limit: 3,
            ssl: true,
            layoutTemplate: "<div class='items'>{entries}</div>",
            entryTemplate: '<div class="item"><h3 class="title"><a href="{url}" target="_blank">{title}</a></h3><div><p>{shortBodyPlain}</p><a class="more-link" href="{url}" target="_blank"><i class="fas fa-external-link-alt"></i>Lire plus</a></div></div>',
        }
    );
    rss.render();
}