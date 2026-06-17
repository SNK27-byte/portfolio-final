<?php
require __DIR__ . '/config/connexion.php';

if (!function_exists('portfolio_to_lower')) {
    function portfolio_to_lower($value)
    {
        if (function_exists('mb_strtolower')) {
            return mb_strtolower($value, 'UTF-8');
        }
        return strtolower($value);
    }
}

if (!function_exists('portfolio_slug')) {
    function portfolio_slug($name)
    {
        $slug = portfolio_to_lower(trim($name));
        $slug = preg_replace('/\s+/', '-', $slug);

        if ($slug === 'vs-code') {
            return 'vscode';
        }

        if ($slug === 'procreate-dreams' || $slug === 'procreate-dream') {
            return 'procreate-dreams';
        }

        return $slug;
    }
}

if (!function_exists('portfolio_work_rank')) {
    function portfolio_work_rank($work, $patterns)
    {
        $haystack = portfolio_to_lower($work['alt'] . ' ' . $work['src']);

        foreach ($patterns as $index => $pattern) {
            if (strpos($haystack, portfolio_to_lower($pattern)) !== false) {
                return $index;
            }
        }

        return PHP_INT_MAX;
    }
}

if (!function_exists('portfolio_sort_works')) {
    function portfolio_sort_works($works, $patterns)
    {
        if (empty($patterns) || count($works) < 2) {
            return $works;
        }

        usort($works, function ($a, $b) use ($patterns) {
            $rankA = portfolio_work_rank($a, $patterns);
            $rankB = portfolio_work_rank($b, $patterns);
            if ($rankA === $rankB) {
                return 0;
            }
            return ($rankA < $rankB) ? -1 : 1;
        });

        return $works;
    }
}

if (!function_exists('portfolio_clean_text')) {
    function portfolio_clean_text($value)
    {
        if ($value === null || $value === '') {
            return '';
        }

        return html_entity_decode(trim((string) $value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

if (!function_exists('build_portfolio_apps')) {
    function build_portfolio_apps(PDO $bdd)
    {
        $apps = array();

        $skills = $bdd->query('SELECT id, nom, image FROM skills ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($skills as $skill) {
            if (empty($skill['image'])) {
                continue;
            }

            $slug = portfolio_slug($skill['nom']);
            $apps[$slug] = array(
                'name' => $skill['nom'],
                'icon' => 'images/' . $skill['image'],
            );
        }

        $categories = $bdd->query('SELECT id, name, image FROM categories ORDER BY name ASC')->fetchAll(PDO::FETCH_ASSOC);
        $products = $bdd->query(
            'SELECT p.id, p.name, p.date, p.description, p.cover, p.category
             FROM products p
             ORDER BY p.date DESC, p.id DESC'
        )->fetchAll(PDO::FETCH_ASSOC);

        $productsByCategory = array();
        foreach ($products as $product) {
            $productsByCategory[$product['category']][] = $product;
        }

        $galleryByProduct = array();
        $galleryStmt = $bdd->query('SELECT id_product, fichier FROM images ORDER BY id ASC');
        while ($row = $galleryStmt->fetch(PDO::FETCH_ASSOC)) {
            $galleryByProduct[$row['id_product']][] = $row['fichier'];
        }

        foreach ($categories as $category) {
            if (empty($productsByCategory[$category['id']])) {
                continue;
            }

            $slug = portfolio_slug($category['name']);
            $works = array();

            foreach ($productsByCategory[$category['id']] as $product) {
                if (!empty($product['cover'])) {
                    $works[] = array(
                        'id' => (int) $product['id'],
                        'src' => 'images/' . $product['cover'],
                        'alt' => $product['name'] . ' - Paul Leroy',
                        'title' => $product['name'],
                        'date' => $product['date'],
                        'description' => portfolio_clean_text($product['description']),
                    );
                }

                if (!empty($galleryByProduct[$product['id']])) {
                    foreach ($galleryByProduct[$product['id']] as $fichier) {
                        if ($fichier === $product['cover']) {
                            continue;
                        }

                        $works[] = array(
                            'id' => (int) $product['id'],
                            'src' => 'images/' . $fichier,
                            'alt' => $product['name'] . ' - Paul Leroy',
                            'title' => $product['name'],
                            'date' => $product['date'],
                            'description' => portfolio_clean_text($product['description']),
                        );
                    }
                }
            }

            if (empty($works)) {
                continue;
            }

            if ($slug === 'figma') {
                $works = portfolio_sort_works($works, array('himalaya', 'couleur', 'liege', 'festival'));
            }

            if (!isset($apps[$slug])) {
                $apps[$slug] = array(
                    'name' => $category['name'],
                    'icon' => 'images/' . $category['image'],
                );
            }

            $apps[$slug]['name'] = $category['name'];
            $apps[$slug]['icon'] = 'images/' . $category['image'];
            $apps[$slug]['works'] = $works;
        }

        return $apps;
    }
}

$portfolioApps = array();
try {
    $portfolioApps = build_portfolio_apps($bdd);
} catch (Throwable $e) {
    $portfolioApps = array();
}

$portfolioAppsJson = json_encode($portfolioApps, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
if ($portfolioAppsJson === false || $portfolioAppsJson === '[]') {
    $portfolioAppsJson = '{}';
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php include __DIR__ . '/partials/head-icons.php'; ?>
    <link rel="stylesheet" href="build/style.css">
    <title>Portfolio</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <nav class="site-nav">
        <a href="#" class="site-nav__logo" id="nav-logo">
            <img src="images/LogoLP.png" alt="">
            <span>Paul Leroy</span>
        </a>
        <button type="button" id="burger" class="site-nav__burger" aria-label="Menu" aria-expanded="false">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </nav>

    <div class="burger-overlay" id="burger-overlay"></div>

    <aside class="burger-panel" id="burger-panel" aria-hidden="true">
        <ul class="burger-panel__links">
            <li><a href="#" data-nav-slide="0"><span>Accueil</span></a></li>
            <li><a href="#" data-nav-slide="1"><span>Présentation</span></a></li>
            <li><a href="#" data-nav-slide="2"><span>Mes oeuvres</span></a></li>
            <li><a href="#" data-nav-slide="3"><span>Contact</span></a></li>
        </ul>
        <div class="burger-panel__footer">
            <div class="burger-panel__socials">
                <a href="https://www.instagram.com/paul_leroydesign/" target="_blank" rel="noopener noreferrer">Instagram</a>
                <a href="https://www.tiktok.com/@paulleroydesign" target="_blank" rel="noopener noreferrer">TikTok</a>
                <a href="mailto:leroypaul.design@gmail.com">Mail</a>
            </div>
            <span class="burger-panel__year">&copy; 2026</span>
        </div>
    </aside>

    <section id="accueil" class="home-screen">
        <div class="container">
            <div class="logo-container">
                <div class="logo-stage">
                    <img class="logo-final" src="images/LogoLP.png" alt="Paul Leroy">
                </div>
            </div>

            <div class="main-search">
                <div class="main-search-bar">
                    <span class="home-name">Paul Leroy</span>
                    <span class="home-role">Infographiste/ Web Designer</span>
                </div>
            </div>

            <div class="apps-grid" id="home-apps-grid"></div>
        </div>
    </section>

    <section id="presentation" class="presentation-section">
        <div class="presentation-inner">
            <header class="presentation-header">
                <h2>Présentation</h2>
                <p class="presentation-role">Infographiste / Web Designer</p>
            </header>

            <div class="presentation-layout">
                <figure class="presentation-photo">
                    <img src="images/paul-leroy.png" alt="Portrait de Paul Leroy">
                </figure>

                <div class="presentation-content">
                <p>
                    Je suis <strong>Paul Leroy</strong>, étudiant à l'EPSE en Belgique, passionné de web et de design.
                </p>

                <ul class="presentation-timeline">
                    <li class="presentation-timeline__item">
                        <span class="presentation-timeline__year">2024</span>
                        <p class="presentation-timeline__text">Première année d'étude en tant qu'infographiste à l'EPSE</p>
                    </li>
                    <li class="presentation-timeline__item">
                        <span class="presentation-timeline__year">2025</span>
                        <p class="presentation-timeline__text">Deuxième année d'étude en tant qu'infographiste à l'EPSE</p>
                    </li>
                    <li class="presentation-timeline__item">
                        <span class="presentation-timeline__year">En cours</span>
                        <p class="presentation-timeline__text">Formation de web developper</p>
                    </li>
                </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="app" class="app-section">
        <div class="sidebar">
            <a href="#" class="sidebar__brand" id="sidebar-brand" aria-hidden="true" tabindex="-1">
                <img src="images/LogoLP.png" alt="">
                <span>Paul Leroy</span>
            </a>
            <div class="app-header">
                <img id="current-app-icon" src="" alt="">
                <span id="current-app-name"></span>
            </div>

            <div class="google-menu">
                <div class="google-menu__header">
                    <span class="google-menu__title">Mes oeuvres</span>
                </div>
                <div class="google-menu__grid" id="skills-grid"></div>
            </div>
        </div>

        <div class="content">
            <div class="works-carousel" id="works-carousel">
                <div class="works-carousel__controls">
                    <button type="button" class="works-carousel__btn works-carousel__btn--prev" aria-label="Image précédente">
                        <ion-icon name="chevron-back-outline"></ion-icon>
                    </button>

                    <div class="works-carousel__viewport">
                        <div class="works-carousel__track" id="works-carousel-track"></div>
                    </div>

                    <button type="button" class="works-carousel__btn works-carousel__btn--next" aria-label="Image suivante">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </button>
                </div>

                <div class="works-carousel__dots" id="works-carousel-dots"></div>
            </div>
        </div>
    </section>

    <div class="work-sheet" id="work-sheet" aria-hidden="true">
        <div class="work-sheet__overlay" id="work-sheet-overlay"></div>
        <div class="work-sheet__dialog" role="dialog" aria-modal="true" aria-labelledby="work-sheet-title">
            <button type="button" class="work-sheet__close" id="work-sheet-close" aria-label="Fermer">
                <ion-icon name="close-outline"></ion-icon>
            </button>
            <div class="work-sheet__layout">
                <figure class="work-sheet__image-wrap">
                    <img id="work-sheet-image" src="" alt="">
                </figure>
                <div class="work-sheet__content">
                    <h2 class="work-sheet__title" id="work-sheet-title"></h2>
                    <p class="work-sheet__date" id="work-sheet-date"></p>
                    <div class="work-sheet__description" id="work-sheet-description"></div>
                </div>
            </div>
        </div>
    </div>

    <section id="contact" class="contact-section">
        <aside class="contact-sidebar">
            <p class="contact-sidebar__title">Mes réseaux</p>
            <nav class="contact-sidebar__socials" aria-label="Réseaux sociaux">
                <a href="https://www.instagram.com/paul_leroydesign/" class="contact-sidebar__social contact-sidebar__social--instagram" target="_blank" rel="noopener noreferrer">
                    <ion-icon name="logo-instagram"></ion-icon>
                    <span>Instagram</span>
                </a>
                <a href="https://www.tiktok.com/@paulleroydesign" class="contact-sidebar__social contact-sidebar__social--tiktok" target="_blank" rel="noopener noreferrer">
                    <ion-icon name="logo-tiktok"></ion-icon>
                    <span>TikTok</span>
                </a>
                <a href="mailto:leroypaul.design@gmail.com" class="contact-sidebar__social contact-sidebar__social--mail">
                    <ion-icon name="mail-outline"></ion-icon>
                    <span>Mail</span>
                </a>
            </nav>
        </aside>

        <div class="contact-main">
        <div class="contact-layout">
        <div class="contact-inner">
            <div class="contact-header">
                <h2>Contact</h2>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <p class="contact-feedback contact-feedback--success">Message envoyé avec succès.</p>
            <?php elseif (isset($_GET['error'])): ?>
                <p class="contact-feedback contact-feedback--error">Veuillez remplir tous les champs.</p>
            <?php endif; ?>

            <form class="contact-form" action="treatmentContact.php" method="post">
                <div class="form-field">
                    <input type="text" id="nom" name="nom" placeholder=" " required>
                    <label for="nom">Nom</label>
                </div>

                <div class="form-field">
                    <input type="email" id="email" name="email" placeholder=" " required>
                    <label for="email">Mail</label>
                </div>

                <div class="form-field form-field--message">
                    <textarea id="message" name="message" placeholder=" " rows="5" required></textarea>
                    <label for="message">Message</label>
                </div>

                <button type="submit" class="contact-submit">
                    <span>Envoyer</span>
                    <ion-icon name="arrow-forward-outline"></ion-icon>
                </button>
            </form>
        </div>

        <?php include __DIR__ . '/partials/footer.php'; ?>
        </div>
        </div>
    </section>

    <script>
        const apps = <?= $portfolioAppsJson ?>;

        const appIcon = document.getElementById('current-app-icon');
        const appName = document.getElementById('current-app-name');
        const worksCarousel = document.getElementById('works-carousel');
        const carouselViewport = worksCarousel ? worksCarousel.querySelector('.works-carousel__viewport') : null;
        const carouselTrack = document.getElementById('works-carousel-track');
        const carouselDots = document.getElementById('works-carousel-dots');
        const SLIDE = { ACCUEIL: 0, PRESENTATION: 1, COMPETENCES: 2, CONTACT: 3 };
        const slides = ['accueil', 'presentation', 'app', 'contact'].map((id) => document.getElementById(id));
        let carouselIndex = 0;
        let isSlideScrolling = false;
        let currentWorks = [];

        function formatWorkDate(dateValue) {
            if (!dateValue) {
                return '';
            }
            const parts = String(dateValue).split('-');
            if (parts.length === 3) {
                return parts[0] + '-' + parts[1] + '-' + parts[2];
            }
            return dateValue;
        }

        const workSheet = document.getElementById('work-sheet');
        const workSheetOverlay = document.getElementById('work-sheet-overlay');
        const workSheetClose = document.getElementById('work-sheet-close');
        const workSheetImage = document.getElementById('work-sheet-image');
        const workSheetTitle = document.getElementById('work-sheet-title');
        const workSheetDate = document.getElementById('work-sheet-date');
        const workSheetDescription = document.getElementById('work-sheet-description');

        function openWorkSheet(work) {
            if (!work || !workSheet || !work.src) {
                return;
            }

            const description = (work.description || '').trim();

            workSheetImage.src = work.src;
            workSheetImage.alt = work.title || work.alt || 'Oeuvre';
            workSheetTitle.textContent = work.title || work.alt || 'Oeuvre';
            workSheetDate.textContent = formatWorkDate(work.date);
            workSheetDescription.textContent = description || 'Aucune description disponible pour cette oeuvre.';

            workSheet.classList.add('is-open');
            workSheet.setAttribute('aria-hidden', 'false');
            document.body.classList.add('work-sheet-open');
        }

        function escapeWorkData(work) {
            return JSON.stringify(work)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;');
        }

        function getWorkFromSlide(slide) {
            const raw = slide.getAttribute('data-work');
            if (!raw) {
                return null;
            }

            try {
                return JSON.parse(raw);
            } catch (error) {
                return null;
            }
        }

        function closeWorkSheet() {
            if (!workSheet) {
                return;
            }

            workSheet.classList.remove('is-open');
            workSheet.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('work-sheet-open');
        }

        if (workSheetOverlay) {
            workSheetOverlay.addEventListener('click', closeWorkSheet);
        }
        if (workSheetClose) {
            workSheetClose.addEventListener('click', closeWorkSheet);
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && workSheet && workSheet.classList.contains('is-open')) {
                closeWorkSheet();
            }
        });

        function bindWorkSheetClicks() {
            if (!carouselTrack) {
                return;
            }

            carouselTrack.querySelectorAll('.works-carousel__slide').forEach((slide) => {
                slide.addEventListener('click', () => {
                    const work = getWorkFromSlide(slide);
                    if (work) {
                        openWorkSheet(work);
                    }
                });
            });
        }

        const hasCarousel = (id) => Boolean(apps[id] && apps[id].works && apps[id].works.length);
        const defaultAppId = Object.keys(apps).find((id) => hasCarousel(id)) || null;

        function handleAppLinkClick(event, id, fromHome) {
            if (!hasCarousel(id)) {
                return;
            }
            event.preventDefault();
            showApp(id, fromHome);
        }

        function showApp(id, scrollToApp, updateHash = true) {
            const app = apps[id];
            if (!app || !hasCarousel(id)) return;

            appIcon.src = app.icon;
            appIcon.alt = app.name;
            appName.textContent = app.name;

            document.querySelectorAll('#skills-grid .google-menu__item').forEach((item) => {
                item.classList.toggle('is-hidden', item.dataset.app === id);
            });

            renderCarousel(app.works);

            if (updateHash) {
                history.replaceState(null, '', '#' + id);
                document.title = app.name + ' — Portfolio';
            }

            if (scrollToApp) goToSlide(SLIDE.COMPETENCES);
        }

        function getCurrentSlideIndex() {
            const marker = window.scrollY + window.innerHeight * 0.35;
            return slides.reduce((index, slide, i) => (slide.offsetTop <= marker + 10 ? i : index), 0);
        }

        function setSkillsSlideLayout(active) {
            document.body.classList.toggle('is-skills-slide', active);
            const sidebarBrand = document.getElementById('sidebar-brand');
            if (!sidebarBrand) return;
            sidebarBrand.setAttribute('aria-hidden', active ? 'false' : 'true');
            sidebarBrand.tabIndex = active ? 0 : -1;
        }

        function resetHomeState() {
            history.replaceState(null, '', location.pathname);
            document.title = 'Portfolio';
            setSkillsSlideLayout(false);

            document.querySelectorAll('#skills-grid .google-menu__item').forEach((item) => {
                item.classList.remove('is-hidden');
            });

            if (defaultAppId) showApp(defaultAppId, false, false);
        }

        function goToSlide(index) {
            if (index < 0 || index >= slides.length || isSlideScrolling) return;
            isSlideScrolling = true;

            if (index === 0) {
                resetHomeState();
            }

            setSkillsSlideLayout(index === SLIDE.COMPETENCES);
            slides[index].scrollIntoView({ behavior: 'smooth' });
            setTimeout(() => {
                isSlideScrolling = false;
            }, 900);
        }

        function slideAllowsInternalScroll(slideIndex, direction) {
            const slide = slides[slideIndex];
            if (!slide) return false;
            const maxScroll = slide.scrollHeight - slide.clientHeight;
            if (maxScroll <= 0) return false;
            if (direction > 0) return slide.scrollTop < maxScroll - 2;
            return slide.scrollTop > 2;
        }

        function currentSlideAllowsInternalScroll(direction) {
            const index = getCurrentSlideIndex();
            if (index === SLIDE.PRESENTATION || index === SLIDE.CONTACT) {
                return slideAllowsInternalScroll(index, direction);
            }
            return false;
        }

        window.addEventListener('wheel', (event) => {
            if (isSlideScrolling) {
                event.preventDefault();
                return;
            }
            if (Math.abs(event.deltaY) < 15) return;

            const direction = event.deltaY > 0 ? 1 : -1;
            if (currentSlideAllowsInternalScroll(direction)) return;

            const next = getCurrentSlideIndex() + direction;
            if (next < 0 || next >= slides.length) return;

            event.preventDefault();
            goToSlide(next);
        }, { passive: false });

        Object.entries(apps).forEach(([id, app]) => {
            const homeGrid = document.getElementById('home-apps-grid');
            const skillsGridEl = document.getElementById('skills-grid');
            const clickable = hasCarousel(id);

            const appDiv = document.createElement('div');
            appDiv.className = 'app' + (clickable ? '' : ' app--display-only');

            const homeLink = document.createElement('a');
            homeLink.href = clickable ? '#app' : '#';
            homeLink.dataset.app = id;
            if (!clickable) {
                homeLink.setAttribute('aria-disabled', 'true');
            } else {
                homeLink.addEventListener('click', (event) => handleAppLinkClick(event, id, true));
            }
            homeLink.innerHTML = `
                <img src="${app.icon}" alt="${app.name}">
                <span class="app__label">${app.name}</span>
            `;
            appDiv.appendChild(homeLink);
            homeGrid.appendChild(appDiv);

            if (clickable && skillsGridEl) {
                const skillLink = document.createElement('a');
                skillLink.href = '#app';
                skillLink.className = 'google-menu__item';
                skillLink.dataset.app = id;
                skillLink.addEventListener('click', (event) => handleAppLinkClick(event, id, false));
                skillLink.innerHTML = `
                    <img src="${app.icon}" alt="${app.name}">
                    <span>${app.name}</span>
                `;
                skillsGridEl.appendChild(skillLink);
            }
        });

        function getCarouselMaxBounds() {
            const content = document.querySelector('.app-section .content');
            const contentWidth = content?.clientWidth ?? window.innerWidth;

            if (window.matchMedia('(max-width: 768px)').matches) {
                return {
                    maxW: Math.max(Math.min(contentWidth - 40, 380), 220),
                    maxH: Math.max(Math.min(window.innerHeight * 0.52, 420), 200),
                };
            }

            if (window.matchMedia('(max-width: 1024px)').matches) {
                return {
                    maxW: Math.max(Math.min(contentWidth - 100, 500), 240),
                    maxH: Math.max(Math.min(window.innerHeight - 120, 520), 200),
                };
            }

            return {
                maxW: Math.max(Math.min(contentWidth - 120, 820), 320),
                maxH: Math.max(Math.min(window.innerHeight - 130, 640), 240),
            };
        }

        function fitCarouselViewport() {
            if (!carouselViewport || !carouselTrack.children.length) return;

            const slide = carouselTrack.children[carouselIndex];
            const img = slide?.querySelector('img');
            if (!img) {
                carouselViewport.style.width = '';
                carouselViewport.style.height = '';
                carouselViewport.style.minHeight = '';
                carouselViewport.style.maxHeight = '';
                return;
            }

            const applyFit = () => {
                const { naturalWidth, naturalHeight } = img;
                if (!naturalWidth || !naturalHeight) return;

                const { maxW, maxH } = getCarouselMaxBounds();
                const ratio = naturalWidth / naturalHeight;

                let width = maxW;
                let height = width / ratio;

                if (height > maxH) {
                    height = maxH;
                    width = height * ratio;
                }

                const fitWidth = Math.round(width);
                const fitHeight = Math.round(height);

                carouselViewport.style.width = fitWidth + 'px';
                carouselViewport.style.height = fitHeight + 'px';
                carouselViewport.style.minHeight = fitHeight + 'px';
                carouselViewport.style.maxHeight = fitHeight + 'px';
            };

            if (img.complete && img.naturalWidth) {
                applyFit();
            } else {
                img.addEventListener('load', applyFit, { once: true });
            }
        }

        function renderCarousel(works) {
            const hasWorks = works && works.length > 0;
            worksCarousel.classList.toggle('is-hidden', !hasWorks);
            if (!hasWorks) {
                carouselTrack.innerHTML = '';
                carouselDots.innerHTML = '';
                currentWorks = [];
                if (carouselViewport) {
                    carouselViewport.style.width = '';
                    carouselViewport.style.height = '';
                    carouselViewport.style.minHeight = '';
                    carouselViewport.style.maxHeight = '';
                }
                return;
            }

            carouselIndex = 0;
            currentWorks = works.slice();
            carouselTrack.innerHTML = works.map((work) => {
                const workData = escapeWorkData(work);
                return `
                <div class="works-carousel__slide works-carousel__slide--clickable" data-work="${workData}">
                    ${work.src
                        ? `<img src="${work.src}" alt="${work.alt}">`
                        : `<div class="works-carousel__placeholder">${work.alt}</div>`}
                </div>
            `;
            }).join('');

            carouselDots.innerHTML = works.map((_, index) => `
                <button type="button" class="works-carousel__dot${index === 0 ? ' is-active' : ''}" aria-label="Image ${index + 1}" data-index="${index}"></button>
            `).join('');

            updateCarousel();
            fitCarouselViewport();
            bindWorkSheetClicks();
        }

        function updateCarousel() {
            const count = carouselTrack.children.length;
            if (!count) return;
            carouselIndex = Math.min(carouselIndex, count - 1);
            carouselTrack.style.transform = `translateX(-${carouselIndex * 100}%)`;
            [...carouselDots.children].forEach((dot, index) => {
                dot.classList.toggle('is-active', index === carouselIndex);
            });
            fitCarouselViewport();
        }

        function goToCarouselSlide(index) {
            const count = carouselTrack.children.length;
            if (!count) return;
            carouselIndex = (index + count) % count;
            updateCarousel();
        }

        const carouselPrevBtn = document.querySelector('.works-carousel__btn--prev');
        const carouselNextBtn = document.querySelector('.works-carousel__btn--next');

        if (carouselPrevBtn) {
            carouselPrevBtn.addEventListener('click', () => goToCarouselSlide(carouselIndex - 1));
        }
        if (carouselNextBtn) {
            carouselNextBtn.addEventListener('click', () => goToCarouselSlide(carouselIndex + 1));
        }
        if (carouselDots) {
            carouselDots.addEventListener('click', (event) => {
                const dot = event.target.closest('[data-index]');
                if (dot) goToCarouselSlide(Number(dot.dataset.index));
            });
        }

        const params = new URLSearchParams(location.search);
        if (params.has('success') || params.has('error')) {
            history.replaceState(null, '', location.pathname);
            setTimeout(() => goToSlide(SLIDE.CONTACT), 50);
        } else {
            history.replaceState(null, '', location.pathname);
            window.scrollTo(0, 0);
            document.title = 'Portfolio';
        }

        if (defaultAppId) showApp(defaultAppId, false, false);

        let carouselResizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(carouselResizeTimer);
            carouselResizeTimer = setTimeout(fitCarouselViewport, 120);
        });

        function startLogoFloat(img) {
            const amplitude = 5;
            const period = 2800;
            const start = performance.now();

            const tick = (now) => {
                const y = Math.sin(((now - start) / period) * Math.PI * 2) * amplitude;
                img.style.transform = `translate3d(0, ${y.toFixed(4)}px, 0)`;
                requestAnimationFrame(tick);
            };

            requestAnimationFrame(tick);
        }

        function initHomeLogoAnimation() {
            const stage = document.querySelector('.logo-stage');
            const img = stage?.querySelector('.logo-final');
            if (!stage || !img) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            const startIntro = () => {
                stage.classList.add('logo-intro');
                img.classList.add('logo-intro');

                img.addEventListener('animationend', (event) => {
                    if (event.animationName !== 'logo-reveal') return;
                    img.classList.remove('logo-intro');
                    stage.classList.remove('logo-intro');
                    img.style.willChange = 'transform';
                    startLogoFloat(img);
                }, { once: true });
            };

            if (img.complete) {
                startIntro();
            } else {
                img.addEventListener('load', startIntro, { once: true });
            }
        }

        initHomeLogoAnimation();

        const burger = document.getElementById('burger');
        const burgerPanel = document.getElementById('burger-panel');
        const burgerOverlay = document.getElementById('burger-overlay');

        function openMenu() {
            burger.classList.add('open');
            burgerPanel.classList.add('open');
            burgerOverlay.classList.add('open');
            burger.setAttribute('aria-expanded', 'true');
            burgerPanel.setAttribute('aria-hidden', 'false');
            document.body.classList.add('menu-open');
        }

        function closeMenu() {
            burger.classList.remove('open');
            burgerPanel.classList.remove('open');
            burgerOverlay.classList.remove('open');
            burger.setAttribute('aria-expanded', 'false');
            burgerPanel.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('menu-open');
        }

        burger.addEventListener('click', () => {
            burger.classList.contains('open') ? closeMenu() : openMenu();
        });

        burgerOverlay.addEventListener('click', closeMenu);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') closeMenu();
        });

        document.querySelectorAll('[data-nav-slide]').forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                closeMenu();
                goToSlide(Number(link.dataset.navSlide));
            });
        });

        function goHome() {
            closeMenu();
            if (getCurrentSlideIndex() === 0) {
                resetHomeState();
                window.scrollTo(0, 0);
            } else {
                goToSlide(0);
            }
        }

        document.getElementById('nav-logo').addEventListener('click', (event) => {
            event.preventDefault();
            goHome();
        });

        document.getElementById('sidebar-brand').addEventListener('click', (event) => {
            event.preventDefault();
            goHome();
        });
    </script>
</body>
</html>
