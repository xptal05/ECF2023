<?php 
$template ='
    <template id="taskTemplate">
        <li>
            <span class="task-text"></span>
            <button class="delete-button">Delete</button>
        </li>
    </template>';




/* JS     
const IMG_TYPE_MAIN = 'Main';
const IMG_TYPE_GALLERY = 'Gallery';

const taskTemplateHTML = '<?php echo getTaskTemplate(); ?>';
    taskList.innerHTML = taskTemplateHTML;





function createPopup(title, content) {
    const popup = document.getElementById('popup');
    popup.innerHTML = '';

    const popupDiv = document.createElement('div');
    popupDiv.id = 'popupDiv';
    popupDiv.innerHTML = `
        <button class="close-sign" id="close">X</button>
        <div class="popup-header">
            <h2>${title}</h2>
        </div>
        <div class="popup-body">
            ${content}
        </div>
    `;
    popup.appendChild(popupDiv);
    popupDiv.classList.add('popup-window');

    // Add event listeners for close and other actions
    // ...

    closePopup();
}


PHP
function getTaskTemplate() {
    return '<template id="taskTemplate">
        <li>
            <span class="task-text"></span>
            <button class="delete-button">Delete</button>
        </li>
    </template>';
}
