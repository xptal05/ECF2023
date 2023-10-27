
//MENU
const burger_menu = document.querySelector('.burger-menu')
const burger_menu_container = document.querySelector('.burger-menu-container')

burger_menu.addEventListener('click', () => {
    burger_menu_container.classList.toggle('active')
    console.log('clicked')
})

const filter_toggle = document.querySelector('.filter-toggle')
const filter_pop_up = document.querySelector('.filter-pop-up-container')


filter_toggle.addEventListener('click', () => {
    filter_pop_up.classList.toggle('off')
})

