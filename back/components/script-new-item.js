//FORMS NEW

const btnNew = document.getElementById('new-item')
const form = document.querySelector('.form-new-container')

btnNew.addEventListener('click', () => {
    form.classList.toggle('on')
    })

const closeBtn = document.getElementById('close-btn')
closeBtn.addEventListener('click', () => {
    form.classList.toggle('on')
})