async function fetchDatabase() {
    const phpScriptURL = './func-one.php?action=fetchData';

    try {
        const response = await fetch(phpScriptURL, {
            method: 'GET', // Use GET method
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        return response.json();
    } catch (error) {
        console.error('Fetch error:', error);
        throw error; // Re-throw the error to propagate it further
    }
}

// Example of how to use fetchUserDatabase and updateList
async function fetchDataAndRenderList() {
    try {
        data = await fetchDatabase();
        console.log('data', data)
        createTableHeaders()

        sortData(data)


    } catch (error) {
        // Handle errors if needed
    }
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

fetchDataAndRenderList()

