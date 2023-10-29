
//MENU
const burger_menu = document.querySelector('.burger-menu')
const burger_menu_container = document.querySelector('.burger-menu-container')
const burger_lines = document.querySelector('.burger-lines')

burger_menu.addEventListener('click', () => {
    burger_menu_container.classList.toggle('active')
    burger_lines.classList.toggle('selected')
    console.log('clicked')
})




