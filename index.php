<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="build/style.css">
    <title>Portfolio</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <section id="accueil" class="home-screen">
        <div class="app-header">
            <img src="images/LogoLP.png" alt="">
            <span>Accueil</span>
        </div>
        <div class="container">
            <div class="logo-container">
                <img src="images/LogoLP.png" alt="LP Logo">
            </div>

            <div class="main-search">
                <div class="main-search-bar">
                    <span class="home-name">Paul Leroy</span>
                    <span class="home-role">Infographiste/ Web Designer</span>
                </div>
            </div>

            <div class="apps-grid">
                <div class="app">
                    <a href="#app" data-app="photoshop">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/af/Adobe_Photoshop_CC_icon.svg" alt="Photoshop">
                    </a>
                </div>
                <div class="app">
                    <a href="#app" data-app="illustrator">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/fb/Adobe_Illustrator_CC_icon.svg" alt="Illustrator">
                    </a>
                </div>
                <div class="app">
                    <a href="#app" data-app="indesign">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/4/48/Adobe_InDesign_CC_icon.svg" alt="InDesign">
                    </a>
                </div>
                <div class="app">
                    <a href="#app" data-app="vscode">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/9/9a/Visual_Studio_Code_1.35_icon.svg" alt="VS Code">
                    </a>
                </div>
                <div class="app">
                    <a href="#app" data-app="php">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Php-logo.png/960px-Php-logo.png" alt="PHP">
                    </a>
                </div>
                <div class="app">
                    <a href="#app" data-app="procreate">
                        <img src="images/Procreate.jpeg" alt="Procreate">
                    </a>
                </div>
                <div class="app">
                    <a href="#app" data-app="procreate-dreams">
                        <img src="images/Procreate-Dreams.jpeg" alt="Procreate Dreams">
                    </a>
                </div>
                <div class="app">
                    <a href="#app" data-app="figma">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/33/Figma-logo.svg" alt="Figma">
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="app" class="app-section">
        <div class="sidebar">
            <div class="app-header">
                <img id="current-app-icon" src="" alt="">
                <span id="current-app-name"></span>
            </div>

            <div class="google-menu">
                <div class="google-menu__header">
                    <span class="google-menu__title">Mes compétences</span>
                </div>
                <div class="google-menu__grid" id="skills-grid"></div>
            </div>
        </div>

        <div class="content">
            <div class="works-carousel">
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

    <section id="contact" class="contact-section">
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

            <div class="contact-socials">
                <p class="contact-socials__title">Mes réseaux</p>
                <div class="contact-socials__buttons">
                    <a href="https://www.instagram.com/paul_leroydesign/" class="social-btn social-btn--instagram" target="_blank" rel="noopener noreferrer">
                        <ion-icon name="logo-instagram"></ion-icon>
                        <span>Instagram</span>
                    </a>
                    <a href="https://www.tiktok.com/@paulleroydesign" class="social-btn social-btn--tiktok" target="_blank" rel="noopener noreferrer">
                        <ion-icon name="logo-tiktok"></ion-icon>
                        <span>TikTok</span>
                    </a>
                    <a href="mailto:leroypaul.design@gmail.com" class="social-btn social-btn--mail">
                        <ion-icon name="mail-outline"></ion-icon>
                        <span>Mail</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script>
        const apps = {
            photoshop: {
                name: 'Photoshop',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/a/af/Adobe_Photoshop_CC_icon.svg',
                works: [
                    { alt: 'Photoshop — Image 1' },
                    { alt: 'Photoshop — Image 2' },
                    { alt: 'Photoshop — Image 3' },
                    { alt: 'Photoshop — Image 4' }
                ]
            },
            illustrator: {
                name: 'Illustrator',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/f/fb/Adobe_Illustrator_CC_icon.svg',
                works: [
                    { alt: 'Illustrator — Image 1' },
                    { alt: 'Illustrator — Image 2' },
                    { alt: 'Illustrator — Image 3' }
                ]
            },
            indesign: {
                name: 'InDesign',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/4/48/Adobe_InDesign_CC_icon.svg',
                works: [
                    { alt: 'InDesign — Image 1' },
                    { alt: 'InDesign — Image 2' },
                    { alt: 'InDesign — Image 3' },
                    { alt: 'InDesign — Image 4' }
                ]
            },
            vscode: {
                name: 'VS Code',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/9/9a/Visual_Studio_Code_1.35_icon.svg',
                works: [
                    { alt: 'VS Code — Image 1' },
                    { alt: 'VS Code — Image 2' },
                    { alt: 'VS Code — Image 3' }
                ]
            },
            php: {
                name: 'PHP',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Php-logo.png/960px-Php-logo.png',
                works: [
                    { alt: 'PHP — Image 1' },
                    { alt: 'PHP — Image 2' },
                    { alt: 'PHP — Image 3' },
                    { alt: 'PHP — Image 4' }
                ]
            },
            procreate: {
                name: 'Procreate',
                icon: 'images/Procreate.jpeg',
                works: [
                    { alt: 'Procreate — Image 1' },
                    { alt: 'Procreate — Image 2' },
                    { alt: 'Procreate — Image 3' }
                ]
            },
            'procreate-dreams': {
                name: 'Procreate Dreams',
                icon: 'images/Procreate-Dreams.jpeg',
                works: [
                    { alt: 'Procreate Dreams — Image 1' },
                    { alt: 'Procreate Dreams — Image 2' },
                    { alt: 'Procreate Dreams — Image 3' },
                    { alt: 'Procreate Dreams — Image 4' }
                ]
            },
            figma: {
                name: 'Figma',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/3/33/Figma-logo.svg',
                works: [
                    { alt: 'Figma — Image 1' },
                    { alt: 'Figma — Image 2' },
                    { alt: 'Figma — Image 3' }
                ]
            }
        };

        const appSection = document.getElementById('app');
        const appIcon = document.getElementById('current-app-icon');
        const appName = document.getElementById('current-app-name');
        let currentApp = null;

        function showApp(id, scrollToApp, updateHash = true) {
            const app = apps[id];
            if (!app) return;

            currentApp = id;
            appIcon.src = app.icon;
            appIcon.alt = app.name;
            appName.textContent = app.name;

            document.querySelectorAll('#skills-grid .google-menu__item').forEach((item) => {
                item.classList.toggle('is-hidden', item.dataset.app === id);
            });

            renderCarousel(id);

            if (updateHash) {
                history.replaceState(null, '', '#' + id);
                document.title = app.name + ' — Portfolio';
            }

            if (scrollToApp) {
                goToSlide(1);
            }
        }

        const slides = [
            document.getElementById('accueil'),
            document.getElementById('app'),
            document.getElementById('contact')
        ];

        let isSlideScrolling = false;
        const SLIDE_SCROLL_MS = 900;

        function getCurrentSlideIndex() {
            const marker = window.scrollY + window.innerHeight * 0.35;
            let index = 0;
            slides.forEach((slide, i) => {
                if (slide.offsetTop <= marker + 10) {
                    index = i;
                }
            });
            return index;
        }

        function goToSlide(index) {
            if (index < 0 || index >= slides.length || isSlideScrolling) {
                return false;
            }
            isSlideScrolling = true;
            slides[index].scrollIntoView({ behavior: 'smooth' });
            setTimeout(() => {
                isSlideScrolling = false;
            }, SLIDE_SCROLL_MS);
            return true;
        }

        function contactAllowsInternalScroll(direction) {
            const contact = slides[2];
            if (getCurrentSlideIndex() !== 2) {
                return false;
            }
            const maxScroll = contact.scrollHeight - contact.clientHeight;
            if (maxScroll <= 0) {
                return false;
            }
            if (direction > 0 && contact.scrollTop < maxScroll - 2) {
                return true;
            }
            if (direction < 0 && contact.scrollTop > 2) {
                return true;
            }
            return false;
        }

        window.addEventListener('wheel', (event) => {
            if (isSlideScrolling) {
                event.preventDefault();
                return;
            }

            if (Math.abs(event.deltaY) < 15) {
                return;
            }

            const direction = event.deltaY > 0 ? 1 : -1;

            if (contactAllowsInternalScroll(direction)) {
                return;
            }

            const current = getCurrentSlideIndex();
            const next = current + direction;

            if (next < 0 || next >= slides.length) {
                return;
            }

            event.preventDefault();
            goToSlide(next);
        }, { passive: false });

        const skillsGrid = document.getElementById('skills-grid');

        Object.entries(apps).forEach(([id, app]) => {
            const item = document.createElement('a');
            item.href = '#app';
            item.className = 'google-menu__item';
            item.dataset.app = id;
            item.innerHTML = `
                <img src="${app.icon}" alt="${app.name}">
                <span>${app.name}</span>
            `;
            skillsGrid.appendChild(item);
        });

        document.querySelectorAll('[data-app]').forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault();
                const id = link.dataset.app;
                const fromHome = link.closest('#accueil') !== null;
                showApp(id, fromHome);
            });
        });

        function initPageOnLoad() {
            const params = new URLSearchParams(location.search);
            const cleanUrl = location.pathname;

            if (params.has('success') || params.has('error')) {
                history.replaceState(null, '', cleanUrl);
                setTimeout(() => goToSlide(2), 50);
                return;
            }

            history.replaceState(null, '', cleanUrl);
            window.scrollTo(0, 0);
            document.title = 'Portfolio';
        }

        initPageOnLoad();
        showApp('photoshop', false, false);

        const carouselTrack = document.getElementById('works-carousel-track');
        const carouselDots = document.getElementById('works-carousel-dots');
        let carouselIndex = 0;

        function renderCarousel(appId) {
            const works = apps[appId]?.works || [];
            carouselIndex = 0;

            carouselTrack.innerHTML = works.map((work) => {
                const content = work.src
                    ? `<img src="${work.src}" alt="${work.alt}">`
                    : `<div class="works-carousel__placeholder">${work.alt}</div>`;
                return `<div class="works-carousel__slide">${content}</div>`;
            }).join('');

            carouselDots.innerHTML = '';
            works.forEach((_, index) => {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'works-carousel__dot' + (index === 0 ? ' is-active' : '');
                dot.setAttribute('aria-label', 'Image ' + (index + 1));
                dot.addEventListener('click', () => goToCarouselSlide(index));
                carouselDots.appendChild(dot);
            });

            updateCarousel();
        }

        function updateCarousel() {
            const slideCount = carouselTrack.querySelectorAll('.works-carousel__slide').length;
            if (slideCount === 0) {
                return;
            }
            carouselIndex = Math.min(carouselIndex, slideCount - 1);
            carouselTrack.style.transform = `translateX(-${carouselIndex * 100}%)`;
            carouselDots.querySelectorAll('.works-carousel__dot').forEach((dot, index) => {
                dot.classList.toggle('is-active', index === carouselIndex);
            });
        }

        function goToCarouselSlide(index) {
            const slideCount = carouselTrack.querySelectorAll('.works-carousel__slide').length;
            if (slideCount === 0) {
                return;
            }
            carouselIndex = (index + slideCount) % slideCount;
            updateCarousel();
        }

        document.querySelector('.works-carousel__btn--prev').addEventListener('click', () => {
            goToCarouselSlide(carouselIndex - 1);
        });

        document.querySelector('.works-carousel__btn--next').addEventListener('click', () => {
            goToCarouselSlide(carouselIndex + 1);
        });
    </script>
</body>
</html>
