

// Function to fetch user data
async function fetchDatabase() {
    const phpScriptURL = './func-one.php?action=fetchDataDashbord';

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
        console.log(data); // Call updateList with the fetched data
    } catch (error) {
        // Handle errors if needed
    }
}

// Call fetchDataAndRenderList to fetch and render data
fetchDataAndRenderList();

