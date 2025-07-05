document.addEventListener('DOMContentLoaded', () => {
    console.log("JS Loaded!");

    const sections = [
        { id: "index", file: "./dashboard/index.php" },
        { id: "skills", file: "./skill/skills.php" },
        { id: "project", file: "./project/projects.php" },
        { id: "experience", file: "./experience/experiences.php" },
        { id: "contact", file: "./contact/contacts.php" }
    ];

    function loadSection(sectionId) {
        console.log("Loading section:", sectionId);
        const section = sections.find(s => s.id === sectionId);
        if (section) {
            fetch(section.file)
                .then(response => response.text())
                .then(html => {
                    console.log(`Loaded content for ${sectionId}`);
                    document.getElementById("content").innerHTML = html;
                })
                .catch(error => console.error("Error loading section:", error));

            // Set active class on clicked nav item
            setActiveNav(sectionId);
        } else {
            console.error(`Section '${sectionId}' not found!`);
        }
    }

    function setActiveNav(activeId) {
        document.querySelectorAll("[data-target]").forEach(item => {
            if (item.getAttribute("data-target") === activeId) {
                item.classList.add("active");
            } else {
                item.classList.remove("active");
            }
        });
    }

    // Check URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    let addedSection = urlParams.get("added");

    console.log("URL Param 'added':", addedSection);

    // If no section is in the URL, load "edit_user" by default
    if (!addedSection) {
        addedSection = "index";
        const newUrl = `dashboard.php?added=${addedSection}`;
        window.history.replaceState({ path: newUrl }, "", newUrl);
    }

    loadSection(addedSection);

    // Handle navbar clicks
    document.querySelectorAll("[data-target]").forEach(item => {
        item.addEventListener("click", function (event) {
            event.preventDefault();
            const targetSection = this.getAttribute("data-target");

            console.log("Clicked on:", targetSection);

            loadSection(targetSection);

            // Update URL without reloading
            const newUrl = `dashboard.php?added=${targetSection}`;
            window.history.pushState({ path: newUrl }, "", newUrl);
        });
    });
});
