// Common function to fetch data
async function fetchData(phpScriptURL) {
    try {
        const response = await fetch(phpScriptURL, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        if (!response.ok) {
            throw new Error(`Network response was not ok for ${phpScriptURL}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Fetch error:', error);
        throw error;
    }
}

//Fetch web info and icons to web pages
async function fetchAndUpdatePageInfo() {
    try {
        // Clear existing data in arrays
        for (const key in webInfo) {
            webInfo[key].array = [];
        }
        imageData = [];
        iconData = [];

        // Fetch dropdown data from the server
        const phpScriptURLData = './func-one.php?action=fetchData';
        const phpScriptURLImages = './func-one.php?action=fetchData&data=images';

        const webInfoData = await fetchData(phpScriptURLData);
        const imageInfoData = await fetchData(phpScriptURLImages);
        
        // Group the fetched data by "type" key
        webInfoData.forEach((item) => {
            for (const key in webInfo) {
                if (webInfo[key].key == item.type) {
                    webInfo[key].array.push(item);
                }
            }
        });

        imageData = imageInfoData;
        imageData.forEach(image => {
            if (image.type == 3) {
                iconData.push(image);
            }
        });
        updateData();
    } catch (error) {
        console.log('Error fetching and updating web info data:', error);
    }
}



// Function to fetch data and render a list - all data
async function fetchDataAndRenderList() {
    const phpScriptURL = './func-one.php?action=fetchData';

    try {
        const data = await fetchData(phpScriptURL);
        console.log('data', data);
        createTableHeaders();
        sortData(data);
    } catch (error) {
        console.error('Fetch error:', error);
        throw error;
    }
}