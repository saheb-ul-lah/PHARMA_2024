document.addEventListener('DOMContentLoaded', function () {
    const volumeButtonsContainer = document.getElementById('volume-buttons');
    const issuesContainer = document.getElementById('issues-container');
    const issueTabsContainer = document.getElementById('issue-tabs');
    let allVolumesData = []; // To store all data fetched from fetch_volumes.php
    let allIssuesData = []; // To store all data fetched from fetch_issues.php

    // Function to show issues for a given volume
    function populateModal(volumeId) {
        console.log("Inside the populateModal fn vol Id is:", volumeId);
        console.log("Inside the populateModal fn all issues data is:", allIssuesData);
        console.log("Inside the populateModal fn all volume is:", allVolumesData);

        // Clear existing tabs and content
        const navTabs = document.querySelector('.nav-tabs');
        const tabContent = document.querySelector('.tab-content');
        navTabs.innerHTML = '';
        tabContent.innerHTML = '';

        // Get unique issue numbers
        const uniqueIssues = [...new Set(allIssuesData
            .filter(issue => issue.volume_id === volumeId)
            .map(issue => issue.issue_number))];

        if (uniqueIssues.length === 0) {
            // Show "No data uploaded" message if no issues are found
            const noDataText = document.createElement('div');
            noDataText.className = 'no-data-text';
            noDataText.textContent = 'No data uploaded';
            tabContent.appendChild(noDataText);
            return;
        }

        // Iterate over unique issues to create tabs and content
        uniqueIssues.forEach((issueNumber, index) => {
            // Create tab
            const tabItem = document.createElement('li');
            tabItem.className = 'nav-item';
            const tabLink = document.createElement('a');
            tabLink.className = `nav-link ${index === 0 ? 'active' : ''}`;
            tabLink.id = `issue${issueNumber}-tab`;
            tabLink.href = `#issue${issueNumber}`;
            tabLink.setAttribute('role', 'tab');
            tabLink.setAttribute('data-toggle', 'tab');
            tabLink.setAttribute('aria-controls', `issue${issueNumber}`);
            tabLink.setAttribute('aria-selected', index === 0 ? 'true' : 'false');
            tabLink.textContent = `Issue ${issueNumber}`;
            tabItem.appendChild(tabLink);
            navTabs.appendChild(tabItem);

            // Create tab pane
            const tabPane = document.createElement('div');
            tabPane.className = `tab-pane fade ${index === 0 ? 'show active' : ''}`;
            tabPane.id = `issue${issueNumber}`;
            tabPane.setAttribute('role', 'tabpanel');
            tabPane.setAttribute('aria-labelledby', `issue${issueNumber}-tab`);

            // Filter data for the current issue number
            const issueData = allIssuesData.filter(issue => issue.issue_number === issueNumber && issue.volume_id === volumeId);

            // Get unique categories within this issue
            const uniqueCategories = [...new Set(issueData.map(issue => issue.category_name))];

            // Iterate over unique categories to create accordions
            uniqueCategories.forEach((categoryName, categoryIndex) => {
                const accordionId = `accordionIssue${issueNumber}${categoryIndex + 1}`;

                // Create accordion
                const accordion = document.createElement('div');
                accordion.className = 'accordion';
                accordion.id = accordionId;

                // Create card for the accordion
                const card = document.createElement('div');
                card.className = 'card';

                // Create card header
                const cardHeader = document.createElement('div');
                cardHeader.className = 'card-header';
                cardHeader.id = `heading${issueNumber}${categoryIndex + 1}`;
                const h5 = document.createElement('h5');
                h5.className = 'mb-0';
                const button = document.createElement('button');
                button.className = 'btn btn-link collapsed';
                button.setAttribute('type', 'button');
                button.setAttribute('data-toggle', 'collapse');
                button.setAttribute('data-target', `#collapse${issueNumber}${categoryIndex + 1}`);
                button.setAttribute('aria-expanded', 'false');
                button.setAttribute('aria-controls', `collapse${issueNumber}${categoryIndex + 1}`);
                button.textContent = `${categoryIndex + 1}. ${categoryName}`;
                h5.appendChild(button);
                cardHeader.appendChild(h5);

                // Create collapse div
                const collapseDiv = document.createElement('div');
                collapseDiv.id = `collapse${issueNumber}${categoryIndex + 1}`;
                collapseDiv.className = 'collapse';
                collapseDiv.setAttribute('aria-labelledby', `heading${issueNumber}${categoryIndex + 1}`);
                collapseDiv.setAttribute('data-parent', `#${accordionId}`);

                // Create card body
                const cardBody = document.createElement('div');
                cardBody.className = 'card-body';
                const ul = document.createElement('ul');

                // Filter data for the current category and create list items
                const categoryData = issueData.filter(issue => issue.category_name === categoryName);
                categoryData.forEach((issue, itemIndex) => {
                    const li = document.createElement('li');
                    li.setAttribute('data-serial', `${categoryIndex + 1}.${itemIndex + 1}`);
                    const a = document.createElement('a');
                    a.href = issue.pdf_url || issue.pdf_file_path;
                    a.textContent = `${itemIndex + 1}. ${issue.title} by ${issue.authors}`;
                    
                    // Apply text-decoration style
                    a.style.textDecoration = 'none';

                    li.appendChild(a);
                    ul.appendChild(li);
                });

                // Append elements to build the structure
                cardBody.appendChild(ul);
                collapseDiv.appendChild(cardBody);
                card.appendChild(cardHeader);
                card.appendChild(collapseDiv);
                accordion.appendChild(card);
                tabPane.appendChild(accordion);
            });

            // Append tab pane to tab content
            tabContent.appendChild(tabPane);
        });

        // Re-initialize Bootstrap's tab functionality to ensure everything is wired up correctly
        $('.nav-tabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    }

    // Fetch volume data
    fetch('fetch_volumes.php')
        .then(response => response.json())
        .then(volumes => {
            console.log('Fetched volumes data:', volumes);
            allVolumesData = volumes; // Store fetched volume data

            // Create volume buttons
            volumes.forEach(volume => {
                if (volume.status === "active") {
                    const button = document.createElement('div');
                    button.classList.add('image-wrapper');

                    let imageUrl;
                    if (volume.volume_thumbnail.startsWith('http://') || volume.volume_thumbnail.startsWith('https://')) {
                        imageUrl = volume.volume_thumbnail;
                    } else {
                        imageUrl = `http://localhost/PHARMA_2024/admin/uploads/${volume.volume_thumbnail}`;
                    }

                    button.innerHTML = `
                        <img src="${imageUrl}" class="volume-image" alt="${volume.journal_name}">
                        <div class="volume-name">${volume.journal_name}</div>
                    `;

                    // Add click event listener
                    button.addEventListener('click', () => {
                        $('#volumeModal').modal('show');
                        console.log("Volume Modal clicked:", volume);
                        console.log("Volume ID clicked:", volume.id);
                        populateModal(volume.id); // Pass volume_id to populateModal function
                    });

                    volumeButtonsContainer.appendChild(button);
                }
            });

            // Fetch issues data once volumes are ready
            return fetch('fetch_issues.php');
        })
        .then(response => response.json())
        .then(issues => {
            console.log('Fetched issues data:', issues);
            allIssuesData = issues; // Store fetched issues data
            console.log('Fetched issues-2 data:', allIssuesData);
        })
        .catch(error => console.error('Error fetching data:', error));
});
