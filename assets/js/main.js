"use strict";

// Initialisation des tooltips Bootstrap
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

/* Vanilla RSS - https://github.com/sdepold/vanilla-rss */
const rss = new RSS(
    document.querySelector("#rss-feeds"),
    //Change this to your own rss feeds
    "https://feeds.feedburner.com/TechCrunch/startups",
    {
        // how many entries do you want?
        // default: 4
        // valid values: any integer
        limit: 3,
        
        // will request the API via https
        // default: false
        // valid values: false, true
        ssl: true,
      
        // outer template for the html transformation
        // default: "<ul>{entries}</ul>"
        // valid values: any string
        layoutTemplate: "<div class='items'>{entries}</div>",
    
        // inner template for each entry
        // default: '<li><a href="{url}">[{author}@{date}] {title}</a><br/>{shortBodyPlain}</li>'
        // valid values: any string
        entryTemplate: '<div class="item"><h3 class="title"><a href="{url}" target="_blank">{title}</a></h3><div><p>{shortBodyPlain}</p><a class="more-link" href="{url}" target="_blank"><i class="fas fa-external-link-alt"></i>Read more</a></div></div>',
    }
);
rss.render();

/* Github Calendar - https://github.com/IonicaBizau/github-calendar */
new GitHubCalendar("#github-graph", "IonicaBizau", { responsive: true });

/* Github Activity Feed - https://github.com/caseyscarborough/github-activity */
GitHubActivity.feed({ username: "mdo", selector: "#ghfeed" });

// Gestion du mode sombre
document.addEventListener('DOMContentLoaded', function() {
    // Récupération du switch de mode sombre
    const darkSwitch = document.getElementById('darkSwitch');
    if (darkSwitch) {
        // Vérifier si un mode sombre est déjà enregistré dans le localStorage
        const darkMode = localStorage.getItem('darkMode');
        if (darkMode === 'enabled') {
            document.body.classList.add('dark-mode');
            darkSwitch.checked = true;
        }

        // Écouter les changements du switch
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

    // Initialisation des tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Animation des barres de compétences
    const skillBars = document.querySelectorAll('.level-bar-inner');
    const animateSkills = () => {
        skillBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    };

    // Observer pour déclencher l'animation des compétences
    const skillsSection = document.querySelector('.skills');
    if (skillsSection) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateSkills();
                    observer.unobserve(entry.target);
                }
            });
        });
        observer.observe(skillsSection);
    }

    // Gestion du scroll fluide pour les ancres
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});