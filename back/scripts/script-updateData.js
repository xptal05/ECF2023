const tagArray = []
const filter = document.getElementById('search');
const tagsEl = document.getElementById('tags')
const prevPageBtn = document.getElementById('prevPage');
const nextPageBtn = document.getElementById('nextPage');
let currentPage = 1
let totalPages = 1

fetchDataAndRenderList()

function createTableHeaders() {

    const thead = document.getElementById('tableHeaders')
    // Clear the current list
    thead.innerHTML = ''
    // Create a table header row
    const headerRow = document.createElement('tr');

    // Loop through the table headers and create header cells
    tableHeaders.forEach(headerItem => {
        const th = document.createElement('th');
        th.textContent = headerItem; // Set the header cell text to the property name
        headerRow.appendChild(th); // Append the header cell to the header row
    });
    thead.appendChild(headerRow);
}

function sortData(data) {
    //ORDER THE ITEMS SELON STATUS
    const customStatusOrder = [2, 3, 1, 8, 4, 5, 6];

    const sortedData = Object.values(data).sort((a, b) => {
        const statusA = customStatusOrder.indexOf(Number(a.status));
        const statusB = customStatusOrder.indexOf(Number(b.status));

        return statusA - statusB;
    });
        console.log('sorted data', sortedData)
        updateList(sortedData); 
        attachEventListeners(sortedData)
}

function updateList(data) {

    console.log('update data', data)
    // Apply filters
    const filterText = filter.value.toLowerCase();
    const tags = Array.from(tagsEl.children).map(tag => tag.querySelector('span').innerText);

    const tagsMatch = tags.some(tagText => {
        tagText = tagText.toLowerCase()
    });

    // Check if currentMapping is defined and has a filter function
    if (currentMapping && currentMapping.filter) {
        const filteredData = data.filter(item => {
            // Assuming you have set currentMapping based on the current page
            return currentMapping.filter(item, filterText, tags, mapStatus(item.status), mapRole(item.role));
        });

        // Now you have filteredData containing filtered results
        populateList(filteredData)

    } else {
        console.log('currentMapping or its filter is not defined');
    }
}

function populateList(data) {
    console.log('populate data', data)

    let pageData
    const list = document.getElementById('list')

    if (currentURL !== "web-pages.pgp") { //DO PAGINATION
        const itemsPerPage = 5
        // Calculate pagination offsets
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        // Slice the data based on pagination offsets
        pageData = data.slice(startIndex, endIndex);

        // Calculate total pages based on filtered data
        totalPages = Math.ceil(data.length / itemsPerPage);

        // Update pagination controls (e.g., disable "Previous" on the first page)
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;

        // Update the current page number display
        const currentPageDisplay = document.getElementById('currentPage');
        currentPageDisplay.textContent = `Page ${currentPage}`;

        // Update the total pages display
        const totalPagesDisplay = document.getElementById('totalPages');
        totalPagesDisplay.textContent = `of ${totalPages}`;

    } else {
        pageData = data
    }

    // Clear the current list
    list.innerHTML = '';

    pageData.forEach(item => {

        const row = document.createElement('tr');
        console.log('item', item)

        // Generate table columns based on tableHeaders
        for (const header of tableHeaders) {
            console.log('header', header);

            if (header === "Statut") {
                // Customize the "Statut" column using the mapStatus function
                const statusValue = mapStatus(item[customMappings[currentURL].headers[header]]);
                row.innerHTML += `<td>${statusValue}</td>`;
            } else if (header === "Droits") {
                const role = mapRole(item[customMappings[currentURL].headers[header]]);
                row.innerHTML += `<td>${role}</td>`;
            } else if (header === "Image") {
                if (item[customMappings[currentURL].headers[header]] != null) {
                    row.innerHTML += `<td><img src="${item[customMappings[currentURL].headers[header]]}" alt="Car Image" width="100"></td>`;
                } else {
                    row.innerHTML += `<td></td>`
                }
            } else {
                row.innerHTML += `<td>${item[customMappings[currentURL].headers[header]]}</td>`;
            }
        }

        // Customize the "Actions" column
        if (typeof customMappings[currentURL].actions[2] !== 'undefined') {
            row.querySelector('td:last-child').innerHTML = `<a id="action1" class="actionbtn svg-btn" href="${customMappings[currentURL].actions[0]}${item[customMappings[currentURL].headers["Actions"]]}" title="${customMappings[currentURL].actions[1]}"><img src="./src/${actionSvgMapping(customMappings[currentURL].actions[1])}"></a><a id="action2" class="actionbtn svg-btn" href="${customMappings[currentURL].actions[2]}${item[customMappings[currentURL].headers["Actions"]]}" title="${customMappings[currentURL].actions[3]}"><img src="./src/${actionSvgMapping(customMappings[currentURL].actions[3])}"></a>`;
        } else {
            row.querySelector('td:last-child').innerHTML = `<a id="action1" class="actionbtn svg-btn" href="${action1_1}${item[customMappings[currentURL].headers["Actions"]]}" title"${action1_2}"><img src="./src/${actionSvgMapping(action1_2)}"></a>`;
        }

        list.appendChild(row);
    });
    console.log('beforeaction')
    attachActionBtnListeners(pageData)
}

