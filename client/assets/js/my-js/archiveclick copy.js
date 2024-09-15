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

    // Toggle abstract content on button click
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

    // Fetch volume data
    fetch('fetch_volumes.php')
        .then(response => response.json())
        .then(volumes => {
            allVolumesData = volumes; // Store fetched volume data
            console.log('Fetched volumes data:', allVolumesData); // Log the fetched data
        })
        .catch(error => console.error('Error fetching volumes data:', error));

    // Fetch issues data
    fetch('fetch_issues.php')
        .then(response => response.json())
        .then(issues => {
            allIssuesData = issues; // Store fetched issues data
            console.log('Fetched issues data:', allIssuesData); // Log the fetched data
        })
        .catch(error => console.error('Error fetching issues data:', error));
});
