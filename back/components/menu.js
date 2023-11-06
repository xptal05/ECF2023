
// Get the current page URL
const currentPageURL = window.location.href;

// Get all menu links
const menuLinks = document.querySelectorAll('.menu-section a');

// Loop through menu links
menuLinks.forEach(link => {
    // Compare the link's href attribute with the current page URL
    if (link.href === currentPageURL) {
        // Add the "selected" class to the matching menu item
        link.classList.add('selected');
    }
});
