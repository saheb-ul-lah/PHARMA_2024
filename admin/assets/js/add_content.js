let categoryCount = 1;
    let titleCount = {
        1: 1
    };

    function addCategory() {
        categoryCount++;
        titleCount[categoryCount] = 1;

        const categoryColor = (categoryCount % 2 === 0) ? '#28a745' : '#007bff'; // Alternating colors

        const categoryContainer = document.createElement('div');
        categoryContainer.classList.add('category-container');
        categoryContainer.dataset.categoryNumber = categoryCount;
        categoryContainer.style.border = `2px solid ${categoryColor}`;
        categoryContainer.innerHTML = `
            <div class="category-header" style="background-color: #e9ecef;">
                <label for="category_${categoryCount}">Category ${categoryCount}:</label>
                <input type="text" class="form-control" id="category_${categoryCount}" name="categories[${categoryCount}][category_name]" required>
            </div>
            <div class="titles-container">
                <div class="title-container" data-title-number="1" style="border: 2px solid ${categoryColor};">
                    <label for="title_${categoryCount}_1">Title ${categoryCount}.1:</label>
                    <input type="text" class="form-control" id="title_${categoryCount}_1" name="categories[${categoryCount}][titles][1][title]" required>
                    <label for="abstract_${categoryCount}_1">Abstract ${categoryCount}.1:</label>
                    <input type="text" class="form-control" id="abstract_${categoryCount}_1" name="categories[${categoryCount}][titles][1][abstract]" required>
                    <label for="authors_${categoryCount}_1">Authors ${categoryCount}.1:</label>
                    <input type="text" class="form-control" id="authors_${categoryCount}_1" name="categories[${categoryCount}][titles][1][authors]" required>

                    <!-- PDF Upload or URL Input -->
                    <div class="form-group">
                        <label for="pdf_upload_${categoryCount}_1">Upload PDF for Title ${categoryCount}.1:</label>
                        <input type="file" class="form-control" id="pdf_upload_${categoryCount}_1" name="categories[${categoryCount}][titles][1][pdf_upload]">
                        <label for="pdf_url_${categoryCount}_1">Or Paste PDF URL:</label>
                        <input type="url" class="form-control" id="pdf_url_${categoryCount}_1" name="categories[${categoryCount}][titles][1][pdf_url]">
                    </div>

                    <button type="button" class="btn btn-danger btn-sm remove-title" onclick="removeTitle(this)">Remove Title</button>
                </div>
            </div>
            <button type="button" class="btn btn-success btn-sm add-title" onclick="addTitle(this, ${categoryCount})">Add Title</button>
            <button type="button" class="btn btn-danger btn-sm remove-category" onclick="removeCategory(this)">Remove Category</button>
        `;

        document.getElementById('categories-container').appendChild(categoryContainer);
    }

    function addTitle(button, categoryNumber) {
        titleCount[categoryNumber]++;
        const titleColor = (categoryNumber % 2 === 0) ? '#28a745' : '#007bff'; // Alternating colors

        const titleContainer = document.createElement('div');
        titleContainer.classList.add('title-container');
        titleContainer.dataset.titleNumber = titleCount[categoryNumber];
        titleContainer.style.border = `2px solid ${titleColor}`;
        titleContainer.innerHTML = `
            <label for="title_${categoryNumber}_${titleCount[categoryNumber]}">Title ${categoryNumber}.${titleCount[categoryNumber]}:</label>
            <input type="text" class="form-control" id="title_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][title]" required>
            <label for="abstract_${categoryNumber}_${titleCount[categoryNumber]}">Abstract ${categoryNumber}.${titleCount[categoryNumber]}:</label>
            <input type="text" class="form-control" id="abstract_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][abstract]" required>
            <label for="authors_${categoryNumber}_${titleCount[categoryNumber]}">Authors ${categoryNumber}.${titleCount[categoryNumber]}:</label>
            <input type="text" class="form-control" id="authors_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][authors]" required>

            <!-- PDF Upload or URL Input -->
            <div class="form-group">
                <label for="pdf_upload_${categoryNumber}_${titleCount[categoryNumber]}">Upload PDF for Title ${categoryNumber}.${titleCount[categoryNumber]}:</label>
                <input type="file" class="form-control" id="pdf_upload_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][pdf_upload]">
                <label for="pdf_url_${categoryNumber}_${titleCount[categoryNumber]}">Or Paste PDF URL:</label>
                <input type="url" class="form-control" id="pdf_url_${categoryNumber}_${titleCount[categoryNumber]}" name="categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][pdf_url]">
            </div>

            <button type="button" class="btn btn-danger btn-sm remove-title" onclick="removeTitle(this)">Remove Title</button>
        `;

        button.parentElement.querySelector('.titles-container').appendChild(titleContainer);
    }

    function removeCategory(button) {
        button.parentElement.remove();
        resetCategoryNumbers();
    }

    function removeTitle(button) {
        const titleContainer = button.parentElement;
        const categoryContainer = titleContainer.parentElement.parentElement;
        titleContainer.remove();

        resetTitleNumbers(categoryContainer);
    }

    function resetCategoryNumbers() {
        categoryCount = 0;
        const categories = document.querySelectorAll('.category-container');

        categories.forEach((category, index) => {
            categoryCount++;
            const categoryColor = (categoryCount % 2 === 0) ? '#28a745' : '#007bff'; // Alternating colors
            category.dataset.categoryNumber = categoryCount;
            category.style.border = `2px solid ${categoryColor}`;

            category.querySelector('.category-header label').setAttribute('for', `category_${categoryCount}`);
            category.querySelector('.category-header label').innerText = `Category ${categoryCount}:`;
            category.querySelector('.category-header input').setAttribute('id', `category_${categoryCount}`);
            category.querySelector('.category-header input').setAttribute('name', `categories[${categoryCount}][category_name]`);

            resetTitleNumbers(category);
        });
    }

    function resetTitleNumbers(categoryContainer) {
        const categoryNumber = categoryContainer.dataset.categoryNumber;
        titleCount[categoryNumber] = 0;
        const titles = categoryContainer.querySelectorAll('.title-container');

        titles.forEach((title, index) => {
            titleCount[categoryNumber]++;
            const titleColor = (categoryNumber % 2 === 0) ? '#28a745' : '#007bff'; // Alternating colors
            title.dataset.titleNumber = titleCount[categoryNumber];
            title.style.border = `2px solid ${titleColor}`;

            title.querySelector('label[for^="title_"]').setAttribute('for', `title_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('label[for^="title_"]').innerText = `Title ${categoryNumber}.${titleCount[categoryNumber]}:`;
            title.querySelector('input[id^="title_"]').setAttribute('id', `title_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="title_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][title]`);

            title.querySelector('label[for^="abstract_"]').setAttribute('for', `abstract_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('label[for^="abstract_"]').innerText = `Abstract ${categoryNumber}.${titleCount[categoryNumber]}:`;
            title.querySelector('input[id^="abstract_"]').setAttribute('id', `abstract_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="abstract_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][abstract]`);

            title.querySelector('label[for^="authors_"]').setAttribute('for', `authors_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('label[for^="authors_"]').innerText = `Authors ${categoryNumber}.${titleCount[categoryNumber]}:`;
            title.querySelector('input[id^="authors_"]').setAttribute('id', `authors_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="authors_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][authors]`);

            title.querySelector('label[for^="pdf_upload_"]').setAttribute('for', `pdf_upload_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="pdf_upload_"]').setAttribute('id', `pdf_upload_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="pdf_upload_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][pdf_upload]`);

            title.querySelector('label[for^="pdf_url_"]').setAttribute('for', `pdf_url_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="pdf_url_"]').setAttribute('id', `pdf_url_${categoryNumber}_${titleCount[categoryNumber]}`);
            title.querySelector('input[id^="pdf_url_"]').setAttribute('name', `categories[${categoryNumber}][titles][${titleCount[categoryNumber]}][pdf_url]`);
        });
    }