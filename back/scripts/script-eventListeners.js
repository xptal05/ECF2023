//Event Listners for Filter - messages, feedback, vehiclules, users
function attachEventListeners(data) {
    // Attach keydown event listener to the filter input
    filter.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && e.target.value.trim() !== '') {
            // Create a tag when Enter key is pressed and the input is not empty
            const tagText = e.target.value.trim();
            const tagEl = createTagElement(tagText);

            tagArray.push(tagText);

            tagsEl.appendChild(tagEl);
            e.target.value = ''; // Clear the input field

            // Call the function to filter and update the item list here
            updateList(data);
        }
    });

    // Attach blur event listener to the filter input
    filter.addEventListener('blur', (e) => {
        if (e.target.value.trim() !== '') {
            // Create a tag when leaving the input area (blur) if the input is not empty
            const tagText = e.target.value.trim();
            tagArray.push(tagText);
            const tagEl = createTagElement(tagText);
            tagsEl.appendChild(tagEl);
            e.target.value = ''; // Clear the input field
            // Call the function to filter and update the item list here
            updateList(data);
        }
    });

    // Attach event listener to filter
    filter.addEventListener('input', () => updateList(data));

    // Attach event listeners to pagination controls
    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            updateList(data);
        }
    });

    nextPageBtn.addEventListener('click', () => {
        if (currentPage < totalPages) {
            currentPage++;
            updateList(data);
        }
    });

    console.log('current page', currentPage)
}