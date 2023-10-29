const activeTags = [];

// Function to create a tag element
function createTagElement(tagText) {
    const tagEl = document.createElement('span');
    tagEl.classList.add('tag');

    // Create the tag text
    const textSpan = document.createElement('span');
    textSpan.innerText = tagText;
    tagEl.appendChild(textSpan);

    // Create the remove button (X)
    const removeButton = document.createElement('span');
    removeButton.classList.add('tag-remove');
    removeButton.innerText = 'X';

    // Attach a click event listener to the remove button
    removeButton.addEventListener('click', () => {
        // Remove the tag when the X is clicked
        tagEl.remove();
        // Remove the tag from the tagArray
        const index = tagArray.indexOf(tagText);
        if (index !== -1) {
            tagArray.splice(index, 1);
        }

        // Call the function to filter and update the item list here
        updateList(data);
    });

    // Append the remove button to the tag
    tagEl.appendChild(removeButton);

    return tagEl;
}