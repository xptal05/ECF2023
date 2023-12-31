//update data on the pages after fetch/ filter

const tagArray = []
const filter = document.getElementById('search')
const tagsEl = document.getElementById('tags')
const prevPageBtn = document.getElementById('prevPage')
const nextPageBtn = document.getElementById('nextPage')
let currentPage = 1
let totalPages = 1

fetchDataAndRenderList()    //fetch data first

//create the table headers according to mapping
function createTableHeaders() {
    const thead = document.getElementById('tableHeaders')
    // Clear the current list
    thead.innerHTML = ''
    // Create a table header row
    const headerRow = document.createElement('tr')

    // Loop through the table headers and create header cells
    tableHeaders.forEach(headerItem => {
        const th = document.createElement('th')
        th.textContent = headerItem // Set the header cell text to the property name
        headerRow.appendChild(th) // Append the header cell to the header row
    })
    thead.appendChild(headerRow)
}

//ORDER THE ITEMS SELON STATUS
function sortData(data) {
    const customStatusOrder = [2, 3, 1, 8, 4, 5, 6]
    const sortedData = Object.values(data).sort((a, b) => {
        const statusA = customStatusOrder.indexOf(Number(a.status))
        const statusB = customStatusOrder.indexOf(Number(b.status))

        return statusA - statusB
    })
    updateList(sortedData)
    attachEventListeners(sortedData)
}

//update the data according to filters applied
function updateList(data) {
    // Apply filters
    const filterText = filter.value.toLowerCase()
    const tags = Array.from(tagsEl.children).map(tag => tag.querySelector('span').innerText)

    const tagsMatch = tags.some(tagText => {
        tagText = tagText.toLowerCase()
    })

    // Check if currentMapping is defined and has a filter function
    if (currentMapping && currentMapping.filter) {
        const filteredData = data.filter(item => {
            return currentMapping.filter(item, filterText, tags, mapStatus(item.status), mapRole(item.role))
        })
        populateList(filteredData)

    } else {
        console.log('currentMapping or its filter is not defined')
    }
}

//populate the table fith the data that is filtered and paginated for each page
function populateList(data) {
    let pageData
    const list = document.getElementById('list')

    //get the table height
    const tblContainer = document.querySelector('.tbl')
    const containerHeight = tblContainer.offsetHeight-60 //60 -> padding + header row
    const minRowHeight = 80


    if (currentURL !== "web-pages.pgp") { //DO PAGINATION
        const itemsPerPage = Math.floor(containerHeight / minRowHeight);

        // Calculate pagination offsets
        const startIndex = (currentPage - 1) * itemsPerPage
        const endIndex = startIndex + itemsPerPage
        // Slice the data based on pagination offsets
        pageData = data.slice(startIndex, endIndex)

        // Calculate total pages based on filtered data
        totalPages = Math.ceil(data.length / itemsPerPage)

        // Update pagination controls (e.g., disable "Previous" on the first page)
        prevPageBtn.disabled = currentPage === 1
        nextPageBtn.disabled = currentPage === totalPages

        // Update the current page number display
        const currentPageDisplay = document.getElementById('currentPage')
        currentPageDisplay.textContent = `Page ${currentPage}`

        // Update the total pages display
        const totalPagesDisplay = document.getElementById('totalPages')
        totalPagesDisplay.textContent = `of ${totalPages}`

    } else { //if web pages page
        pageData = data
    }

    // Clear the current list
    list.innerHTML = ''

    pageData.forEach(item => {

        const row = document.createElement('tr')

        // Generate table columns based on tableHeaders
        for (const header of tableHeaders) {
            if (header === "Statut") {
                // Customize the "Statut" column using the mapStatus function
                const statusValue = mapStatus(item[customMappings[currentURL].headers[header]])
                row.innerHTML += `<td>${statusValue}</td>`
            } else if (header === "Droits") {
                const role = mapRole(item[customMappings[currentURL].headers[header]])
                row.innerHTML += `<td>${role}</td>`
            } else if (header === "Image") {
                if (item[customMappings[currentURL].headers[header]] != null) {
                    row.innerHTML += `<td><img src="${item[customMappings[currentURL].headers[header]]}" alt="Car Image" width="100"></td>`
                } else {
                    row.innerHTML += `<td></td>`
                }
            } else {
                row.innerHTML += `<td>${item[customMappings[currentURL].headers[header]]}</td>`
            }
        }

        // Customize the "Actions" column
        if (typeof customMappings[currentURL].actions[2] !== 'undefined') {
            row.querySelector('td:last-child').innerHTML = `<a id="action1" class="actionbtn svg-btn" href="${customMappings[currentURL].actions[0]}${item[customMappings[currentURL].headers["Actions"]]}" title="${customMappings[currentURL].actions[1]}"><img src="./assets/src/${actionSvgMapping(customMappings[currentURL].actions[1])}"></a><a id="action2" class="actionbtn svg-btn" href="${customMappings[currentURL].actions[2]}${item[customMappings[currentURL].headers["Actions"]]}" title="${customMappings[currentURL].actions[3]}"><img src="./assets/src/${actionSvgMapping(customMappings[currentURL].actions[3])}"></a>`
        } else {
            row.querySelector('td:last-child').innerHTML = `<a id="action1" class="actionbtn svg-btn" href="${action1_1}${item[customMappings[currentURL].headers["Actions"]]}" title"${action1_2}"><img src="./assets/src/${actionSvgMapping(action1_2)}"></a>`
        }

        list.appendChild(row)
    })
    attachActionBtnListeners(pageData)
}

