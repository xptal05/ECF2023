
//MENU
const burger_menu = document.querySelector('.burger-menu')
const burger_menu_container = document.querySelector('.burger-menu-container')
const burger_lines = document.querySelector('.burger-lines')

burger_menu.addEventListener('click', () => {
    burger_menu_container.classList.toggle('active')
    burger_lines.classList.toggle('selected')
    console.log('clicked')
})


const filter_toggle = document.querySelector('.filter-toggle')
const filter_pop_up = document.querySelector('.filter-pop-up-container')


filter_toggle.addEventListener('click', () => {
    filter_pop_up.classList.toggle('off')
})

