//Event Listners for Filter - messages, feedback, vehiclules, users
function attachEventListeners(data) {
    // Attach keydown event listener to the filter input
    filter.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && e.target.value.trim() !== '') {
            // Create a tag when Enter key is pressed and the input is not empty
            const tagText = e.target.value.trim()
            const tagEl = createTagElement(tagText, data)

            tagArray.push(tagText)

            tagsEl.appendChild(tagEl)
            e.target.value = '' // Clear the input field

            // Call the function to filter and update the item list here
            updateList(data)
        }
    })

    // Attach blur event listener to the filter input
    filter.addEventListener('blur', (e) => {
        if (e.target.value.trim() !== '') {
            // Create a tag when leaving the input area (blur) if the input is not empty
            const tagText = e.target.value.trim()
            tagArray.push(tagText)
            const tagEl = createTagElement(tagText, data)
            tagsEl.appendChild(tagEl)
            e.target.value = '' // Clear the input field
            // Call the function to filter and update the item list here
            updateList(data)
        }
    })

    // Attach event listener to filter
    filter.addEventListener('input', () => updateList(data))

    // Attach event listeners to pagination controls
    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--
            updateList(data)
        }
    })

    nextPageBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++
            updateList(data)
        }
    })

    console.log('current page', currentPage)
}

const activeTags = []

// Function to create a tag element
function createTagElement(tagText, data) {
    const tagEl = document.createElement('span')
    tagEl.classList.add('tag')

    // Create the tag text
    const textSpan = document.createElement('span')
    textSpan.innerText = tagText
    tagEl.appendChild(textSpan)

    // Create the remove button (X)
    const removeButton = document.createElement('span')
    removeButton.classList.add('tag-remove')
    removeButton.innerText = 'X'

    // Attach a click event listener to the remove button
    removeButton.addEventListener('click', () => {
        // Remove the tag when the X is clicked
        tagEl.remove()
        // Remove the tag from the tagArray
        const index = tagArray.indexOf(tagText)
        if (index !== -1) {
            tagArray.splice(index, 1)
        }

        // Call the function to filter and update the item list here
        updateList(data)
    })

    // Append the remove button to the tag
    tagEl.appendChild(removeButton)

    return tagEl
}