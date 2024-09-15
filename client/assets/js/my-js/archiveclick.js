const selectedVolumeId = localStorage.getItem('selectedVolumeId');
console.log('Selected Volume ID:', selectedVolumeId);

// Declare variables to store fetched data
let allVolumesData = []; // To store data fetched from fetch_volumes.php
let allIssuesData = []; // To store data fetched from fetch_issues.php

document.addEventListener('DOMContentLoaded', function () {
    // Toggle dropdowns on button click
    document.querySelectorAll('.dropdown-button').forEach(button => {
        button.addEventListener('click', function () {
            const dropdownContent = this.nextElementSibling;
            const isActive = dropdownContent.classList.contains('show');

            // Close all dropdowns
            document.querySelectorAll('.dropdown-content').forEach(content => {
                content.classList.remove('show');
            });

            // Toggle the clicked dropdown
            if (!isActive) {
                dropdownContent.classList.add('show');
            }
        });
    });

    // Fetch volume data
    fetch('fetch_volumes.php')
        .then(response => response.json())
        .then(volumes => {
            allVolumesData = volumes; // Store fetched volume data
            console.log('Fetched volumes data:', allVolumesData); // Log the fetched data

            // Filter the selected volume
            const selectedVolume = allVolumesData.find(volume => volume.id === selectedVolumeId);
            if (selectedVolume) {
                document.querySelector('.header-volume h1').textContent = `${selectedVolume.journal_name}`;
                const thumbnailUrl = selectedVolume.volume_thumbnail.startsWith('http://') || selectedVolume.volume_thumbnail.startsWith('https://')
                    ? selectedVolume.volume_thumbnail
                    : `http://localhost/PHARMA_2024/admin/uploads/${selectedVolume.volume_thumbnail}`;
                document.querySelector('.aside-thumbnail').src = thumbnailUrl;
            } else {
                document.querySelector('.header-volume h1').textContent = 'No volume data available';
                document.querySelector('.aside-thumbnail').src = ''; // Clear the image if no data
            }
        })
        .catch(error => console.error('Error fetching volumes data:', error));

    // Fetch issues data
    fetch('fetch_issues.php')
        .then(response => response.json())
        .then(issues => {
            allIssuesData = issues; // Store fetched issues data
            console.log('Fetched issues data:', allIssuesData); // Log the fetched data

            // Filter issues based on selected volume
            const filteredIssues = allIssuesData.filter(issue => issue.volume_id === selectedVolumeId);
            console.log('Filtered issues data:', filteredIssues);

            const itemList = document.querySelector('.item-list');
            itemList.innerHTML = ''; // Clear previous items

            if (filteredIssues.length === 0) {
                itemList.innerHTML = '<li class="list-item">No issues available for this volume.</li>';
                return;
            }

            // Organize issues by unique issue numbers
            const issuesByNumber = {};
            filteredIssues.forEach(issue => {
                if (!issuesByNumber[issue.issue_number]) {
                    issuesByNumber[issue.issue_number] = [];
                }
                issuesByNumber[issue.issue_number].push(issue);
            });

            // Generate issue list HTML
            Object.keys(issuesByNumber).forEach(issueNumber => {
                const issueItems = issuesByNumber[issueNumber];
            
                // Create a container for the issue number
                const issueContainer = document.createElement('div');
                issueContainer.classList.add('issue-container');
            
                // Add a span or div to display the issue number
                const issueNumberElement = document.createElement('h2');
                issueNumberElement.classList.add('issue-number');
                issueNumberElement.textContent = `Issue No: ${issueNumber}`;
            
                // Append the issue number element to the container
                issueContainer.appendChild(issueNumberElement);
            
                // Loop through the issues and add them as list items
                issueItems.forEach(issue => {
                    if (issue.status=="active"){
                        const listItem = document.createElement('li');
                        listItem.classList.add('list-item');
                
                        listItem.innerHTML = `
                        <div class="item-info">
                            <h3><a href="${issue.pdf_url || `http://localhost/PHARMA_2024/admin/${issue.pdf_file_path}`}" target="_blank">${issue.title}</a></h3>
                            <p>Authors: <br> ${issue.authors} <br> Publication Year: ${issue.publication_year || 'N/A'}</p>
                            <div class="item-links">
                                ${issue.pdf_url || issue.pdf_file_path ? `
                                <a href="${issue.pdf_url || `http://localhost/PHARMA_2024/admin/${issue.pdf_file_path}`}" download>
                                    <span><i class="fas fa-file-pdf"></i> PDF</span>
                                </a>` : ''}
                                <button class="abstract-button">Abstract &nbsp;<i class="fas fa-chevron-down"></i></button>
                                <div class="abstract-content">
                                    <p>${issue.abstract}</p>
                                </div>
                            </div>
                        </div>
                    `;

            
                    // Append each list item to the issue container
                    issueContainer.appendChild(listItem);
                    }
                });
            
                // Finally, append the issue container to the main item list
                itemList.appendChild(issueContainer);
            });
            

            // Reattach abstract toggle functionality
            document.querySelectorAll('.abstract-button').forEach(button => {
                button.addEventListener('click', function () {
                    const abstractContent = this.nextElementSibling;
                    const isActive = abstractContent.classList.contains('show');

                    // Close all abstract contents
                    document.querySelectorAll('.abstract-content').forEach(content => {
                        content.classList.remove('show');
                    });

                    // Toggle the clicked abstract content
                    if (!isActive) {
                        abstractContent.classList.add('show');
                    }
                });
            });
        })
        .catch(error => console.error('Error fetching issues data:', error));

    // Live search functionality
    const searchInput = document.querySelector('.search-bar input');
    searchInput.addEventListener('input', function () {
        const query = this.value.toLowerCase();
        const items = document.querySelectorAll('.item-list .list-item');

        items.forEach(item => {
            const title = item.querySelector('h3').textContent.toLowerCase();
            const description = item.querySelector('p').textContent.toLowerCase();

            if (title.includes(query) || description.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });
});

// Get the buttons for loading previous and next volumes
const loadPrevBtn = document.querySelector('.load-prev-btn');
const loadNextBtn = document.querySelector('.load-next-btn');

// Function to update the volume ID and reload the page
function updateVolumeId(change) {
    // Get the current selectedVolumeId from localStorage
    let selectedVolumeId = localStorage.getItem('selectedVolumeId');
    
    // Convert it to an integer and update by the specified change value
    selectedVolumeId = parseInt(selectedVolumeId) + change;

    // Update the selectedVolumeId in localStorage
    localStorage.setItem('selectedVolumeId', selectedVolumeId);

    // Reload the page to reflect the change
    window.location.reload();
}

// Add click event listener for the previous button
loadPrevBtn.addEventListener('click', () => {
    updateVolumeId(-1); // Decrement the volume ID by 1
});

// Add click event listener for the next button
loadNextBtn.addEventListener('click', () => {
    updateVolumeId(1); // Increment the volume ID by 1
});


