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

        <div class="content"></div>
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
        </div>
    </section>

    <script>
        const apps = {
            photoshop: {
                name: 'Photoshop',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/a/af/Adobe_Photoshop_CC_icon.svg'
            },
            illustrator: {
                name: 'Illustrator',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/f/fb/Adobe_Illustrator_CC_icon.svg'
            },
            indesign: {
                name: 'InDesign',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/4/48/Adobe_InDesign_CC_icon.svg'
            },
            vscode: {
                name: 'VS Code',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/9/9a/Visual_Studio_Code_1.35_icon.svg'
            },
            php: {
                name: 'PHP',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Php-logo.png/960px-Php-logo.png'
            },
            procreate: {
                name: 'Procreate',
                icon: 'images/Procreate.jpeg'
            },
            'procreate-dreams': {
                name: 'Procreate Dreams',
                icon: 'images/Procreate-Dreams.jpeg'
            },
            figma: {
                name: 'Figma',
                icon: 'https://upload.wikimedia.org/wikipedia/commons/3/33/Figma-logo.svg'
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

        const contactSection = document.getElementById('contact');
        const revealItems = contactSection.querySelectorAll('.contact-header, .form-field, .contact-submit, .contact-feedback');

        const contactObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    revealItems.forEach((item, index) => {
                        item.style.animationDelay = `${index * 0.1}s`;
                        item.classList.add('is-visible');
                    });
                    contactObserver.disconnect();
                }
            });
        }, { threshold: 0.2 });

        contactObserver.observe(contactSection);
    </script>
</body>
</html>
