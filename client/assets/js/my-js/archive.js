document.addEventListener('DOMContentLoaded', function () {
    const volumeButtonsContainer = document.getElementById('volume-buttons');
    let allVolumesData = []; // To store all data fetched from fetch_volumes.php
    let allIssuesData = []; // To store all data fetched from fetch_issues.php

    // Fetch volume data
    fetch('fetch_volumes.php')
        .then(response => response.json())
        .then(volumes => {
            console.log('Fetched volumes data:', volumes);
            allVolumesData = volumes; // Store fetched volume data

            // Filter unique volumes based on journal_name
            const uniqueVolumes = volumes.filter((volume, index, self) =>
                index === self.findIndex(v => v.journal_name === volume.journal_name && volume.status === "active")
            );

            // Create volume buttons for unique volumes
            uniqueVolumes.forEach(volume => {
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
                    console.log("Volume ID clicked:", volume.id);

                    // Store volumeId in localStorage
                    localStorage.setItem('selectedVolumeId', volume.id);

                    // Redirect to archiveclick.html
                    window.location.href = 'archiveclick.html';
                });

                volumeButtonsContainer.appendChild(button);
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
