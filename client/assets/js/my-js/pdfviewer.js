// Function to get the query parameter from the URL
function getQueryParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Function to get the filename from the URL query parameter
function getPdfFilename() {
    return new URLSearchParams(window.location.search).get('pdf');
}

const pdfFilename = getPdfFilename();
const pdfUrl = `http://localhost/PHARMA_2024/admin/uploads/${pdfFilename}`;

console.log("PDF URL:", pdfUrl);

let countSection = 1;

// Fetch volumes and then use the PDF filename to get the corresponding journal ID
function fetchVolumesAndSections() {
    fetch('fetch_volumes.php')
        .then(response => response.json())
        .then(volumes => {
            allVolumes = volumes;
            console.log('Fetched volumes:', volumes);

            const volume = volumes.find(volume => volume.pdf === pdfFilename);
            if (volume) {
                journalId = volume.id; // Assuming volume object contains journal_id
                console.log('Found journal ID:', journalId);
                return fetch(`fetch_sections.php?journal_id=${journalId}`);
            } else {
                throw new Error('No volume found for the given PDF filename.');
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(sections => {
            console.log('Fetched sections:', sections);
            allSections = sections;
            filteredSections = sections.filter(section => section.id === journalId);
            console.log("Sections with section.id -> ", journalId, " is ", filteredSections);

            console.log("Final outputs below...");
            console.log("This is Journal ID : ", journalId);
            console.log("This is allVolumes : ", allVolumes);
            console.log("This is allSections : ", allSections);

            allSections.forEach(section => {
                console.log("Section name : ", section.name);
                console.log("Starts at : ", section.startPage);
                console.log("Ends at : ", section.endPage);
                
                populateSidebar(section, countSection);
                countSection++;
                console.log("Done populating");
            });
            console.log("This is filteredSections : ", filteredSections);
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function populateSidebar(section, countSection) {
    const sidebar = document.getElementById('sidebar');

    // Create section header
    const sectionHeader = document.createElement('h2');
    sectionHeader.textContent = `${countSection}. ${section.name}`;
    
    sectionHeader.dataset.pages = `${section.startPage}-${section.endPage}`;

    // Create download icon
    const downloadIcon = document.createElement('span');
    downloadIcon.textContent = '⬇';
    downloadIcon.classList.add('download-icon');
    downloadIcon.dataset.pages = `${section.startPage}-${section.endPage}`;

    sectionHeader.appendChild(downloadIcon);

    // Create profile info
    const profileInfo = document.createElement('div');
    profileInfo.classList.add('profile-info');

    const profileImg = document.createElement('img');
    profileImg.src = "../assets/img/testimonials/testimonials-3.jpg"; // Assuming `profileImage` is a URL
    profileImg.alt = 'Profile Picture';

    const profileName = document.createElement('span');
    profileName.textContent = `${section.author} (${section.endPage - section.startPage + 1} pages)`;

    profileInfo.appendChild(profileImg);
    profileInfo.appendChild(profileName);

    sidebar.appendChild(sectionHeader);
    sidebar.appendChild(profileInfo);

    sectionHeader.addEventListener('click', () => {
        document.querySelectorAll('.sidebar h2').forEach(h => h.classList.remove('active'));
        sectionHeader.classList.add('active');
        renderPages(section.startPage, section.endPage);
    });
}

// Call the function to perform the fetch operations
fetchVolumesAndSections();

let pdfDoc = null;
let currentScale = 0.8;
let currentRotation = 0;

async function getPDFLength() {
    try {
        pdfDoc = await pdfjsLib.getDocument(pdfUrl).promise;
        return pdfDoc.numPages;
    } catch (error) {
        console.error('Error loading the PDF:', error);
        return 0; // Return 0 or handle the error appropriately
    }
}

let sectionBegins = 1;

// Wait for the PDF length to be determined
getPDFLength().then(totalPages => {
    let sectionEnds = totalPages; // Now you have the correct number of pages

    // Render the pages only after sectionEnds is determined
    pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
        pdfDoc = pdf;
        renderPages(sectionBegins, sectionEnds); // Render the pages
    });
});

document.querySelectorAll('.sidebar h2').forEach(header => {
    header.addEventListener('click', () => {
        document.querySelectorAll('.sidebar h2').forEach(h => h.classList.remove('active'));
        header.classList.add('active');
        const pages = header.getAttribute('data-pages').split('-').map(Number);
        renderPages(pages[0], pages[1]);
    });
});

function renderPages(startPage, endPage) {
    const pdfViewer = document.getElementById('pdf-viewer');
    pdfViewer.innerHTML = ''; // Clear previous content

    for (let pageNum = startPage; pageNum <= endPage; pageNum++) {
        pdfDoc.getPage(pageNum).then(page => {
            const canvas = document.createElement('canvas');
            canvas.id = `pdf-canvas-${pageNum}`;
            pdfViewer.appendChild(canvas);

            const viewport = page.getViewport({ scale: currentScale, rotation: currentRotation });
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            const context = canvas.getContext('2d');
            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            page.render(renderContext);
        });
    }
}

document.getElementById('zoom-in').addEventListener('click', () => {
    currentScale = Math.min(3, currentScale + 0.1); // Limiting the maximum zoom level
    rerenderCurrentPage();
});

document.getElementById('zoom-out').addEventListener('click', () => {
    currentScale = Math.max(0.1, currentScale - 0.1); // Limiting the minimum zoom level
    rerenderCurrentPage();
});

document.getElementById('rotate').addEventListener('click', () => {
    currentRotation = (currentRotation + 90) % 360;
    rerenderCurrentPage();
});

function rerenderCurrentPage() {
    const canvas = document.querySelector('canvas');
    if (canvas) {
        const pageNum = parseInt(canvas.id.split('-')[2], 10);
        renderPages(pageNum, pageNum);
    }
}

document.getElementById('toggle-btn').addEventListener('click', () => {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    if (sidebar.style.transform === 'translateX(-100%)') {
        sidebar.style.transform = 'translateX(0)';
        content.style.marginLeft = '30%';
    } else {
        sidebar.style.transform = 'translateX(-100%)';
        content.style.marginLeft = '0';
    }
});

window.addEventListener('resize', rerenderCurrentPage);

document.querySelectorAll('.download-icon').forEach(icon => {
    icon.addEventListener('click', (e) => {
        e.stopPropagation();
        const pages = e.target.getAttribute('data-pages').split('-').map(Number);
        downloadSection(pages[0], pages[1]);
    });
});

async function downloadSection(startPage, endPage) {
    try {
        const pdfBytes = await fetch(pdfUrl).then(res => res.arrayBuffer());
        const pdfDoc = await PDFLib.PDFDocument.load(pdfBytes);
        const pdfDocNew = await PDFLib.PDFDocument.create();
        const pages = await pdfDoc.copyPages(pdfDoc, Array.from({ length: endPage - startPage + 1 }, (_, i) => startPage - 1 + i));
        pages.forEach(page => pdfDocNew.addPage(page));

        const pdfBytesNew = await pdfDocNew.save();
        const blob = new Blob([pdfBytesNew], { type: 'application/pdf' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = `section-${startPage}-${endPage}.pdf`;
        link.click();
    } catch (error) {
        console.error('Error downloading section:', error);
    }
}

document.getElementById('download-all').addEventListener('click', async () => {
    try {
        const pdfBytes = await fetch(pdfUrl).then(res => res.arrayBuffer());
        const blob = new Blob([pdfBytes], { type: 'application/pdf' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = pdfFilename; // Ensure the filename is correctly set
        link.click();
    } catch (error) {
        console.error('Error downloading the PDF:', error);
    }
});
