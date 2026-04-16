(function () {
    const pageData = window.deploymentPageData;

    if (!pageData || !Array.isArray(pageData.items)) {
        return;
    }

    const items = Array.isArray(pageData.items) ? pageData.items : [];

    const state = {
        search: '',
        category: '',
        company: '',
        department: '',
        entries: 10,
        page: 1,
    };

    const elements = {
        totalPill: document.getElementById('deploymentTotalPill'),
        availableCount: document.getElementById('deploymentAvailableCount'),
        readyTodayCount: document.getElementById('deploymentReadyTodayCount'),
        highPriorityCount: document.getElementById('deploymentHighPriorityCount'),
        categoryCount: document.getElementById('deploymentCategoryCount'),
        searchInput: document.getElementById('deploymentSearchInput'),
        entriesSelect: document.getElementById('deploymentEntriesSelect'),
        categoryFilter: document.getElementById('deploymentCategoryFilter'),
        companyFilter: document.getElementById('deploymentCompanyFilter'),
        departmentFilter: document.getElementById('deploymentDepartmentFilter'),
        resetButton: document.getElementById('deploymentResetBtn'),
        tableBody: document.getElementById('deploymentTableBody'),
        tableSummary: document.getElementById('deploymentTableSummary'),
        pagination: document.getElementById('deploymentPagination'),
    };

    const statusColorMap = {
        AVAILABLE: { bg: '#d1fae5', fg: '#047857' },
        RETURNED: { bg: '#cffafe', fg: '#0e7490' },
        'RETURNED WITH ISSUE/S': { bg: '#fae8ff', fg: '#a21caf' },
        TEMPORARY: { bg: '#e0f2fe', fg: '#0369a1' },
        DEPLOYED: { bg: '#d1fae5', fg: '#047857' },
        TRANSFER: { bg: '#ede9fe', fg: '#6d28d9' },
        BORROWED: { bg: '#fef9c3', fg: '#a16207' },
    };

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function populateFilter(select, values) {
        if (!select) {
            return;
        }

        const options = Array.from(new Set(values))
            .sort((left, right) => left.localeCompare(right))
            .map((value) => `<option value="${escapeHtml(value)}">${escapeHtml(value)}</option>`)
            .join('');

        select.insertAdjacentHTML('beforeend', options);
    }

    function getStatusBadgeStyle(label) {
        return statusColorMap[String(label || '').trim().toUpperCase()] || { bg: '#f4f4f5', fg: '#3f3f46' };
    }

    function getFilteredItems() {
        const search = state.search.trim().toLowerCase();

        return items.filter((item) => {
            const haystack = [
                item.inventory_no,
                item.item_name,
                item.category,
                item.company,
                item.department,
                item.serial_number,
                item.model,
                item.deploy_to,
                item.priority,
                item.deployment_status,
            ].join(' ').toLowerCase();

            if (search && !haystack.includes(search)) {
                return false;
            }

            if (state.category && item.category !== state.category) {
                return false;
            }

            if (state.company && item.company !== state.company) {
                return false;
            }

            if (state.department && item.department !== state.department) {
                return false;
            }

            return true;
        });
    }

    function updateMetrics(filteredItems) {
        const availableCount = filteredItems.length;
        const readyTodayCount = filteredItems.filter((item) => item.ready_window === 'Today').length;
        const highPriorityCount = filteredItems.filter((item) => item.priority === 'High').length;
        const categoryCount = new Set(filteredItems.map((item) => item.category)).size;

        if (elements.totalPill) {
            elements.totalPill.textContent = `${availableCount} preview item${availableCount === 1 ? '' : 's'}`;
        }

        if (elements.availableCount) {
            elements.availableCount.textContent = String(availableCount);
        }

        if (elements.readyTodayCount) {
            elements.readyTodayCount.textContent = String(readyTodayCount);
        }

        if (elements.highPriorityCount) {
            elements.highPriorityCount.textContent = String(highPriorityCount);
        }

        if (elements.categoryCount) {
            elements.categoryCount.textContent = String(categoryCount);
        }
    }

    function getPaginatedItems(filteredItems) {
        const totalItems = filteredItems.length;
        const totalPages = Math.max(1, Math.ceil(totalItems / state.entries));

        if (state.page > totalPages) {
            state.page = totalPages;
        }

        const startIndex = (state.page - 1) * state.entries;
        return {
            items: filteredItems.slice(startIndex, startIndex + state.entries),
            totalItems,
            totalPages,
            startIndex,
        };
    }

    function renderPagination(totalPages) {
        if (!elements.pagination) {
            return;
        }

        if (totalPages <= 1) {
            elements.pagination.innerHTML = '';
            return;
        }

        const buttons = [];

        for (let page = 1; page <= totalPages; page += 1) {
            buttons.push(`
                <button class="page-btn ${page === state.page ? 'active' : ''}" type="button" data-page="${page}">
                    ${page}
                </button>
            `);
        }

        elements.pagination.innerHTML = buttons.join('');
    }

    function renderTable() {
        const filteredItems = getFilteredItems();
        const pagination = getPaginatedItems(filteredItems);
        const visibleItems = pagination.items;

        updateMetrics(filteredItems);

        if (!elements.tableBody || !elements.tableSummary) {
            return;
        }

        if (!visibleItems.length) {
            elements.tableBody.innerHTML = `
                <tr>
                    <td colspan="12" class="inventory-empty-cell">
                        No deployment items match the current filters.
                    </td>
                </tr>
            `;
            elements.tableSummary.textContent = 'Showing 0 to 0 of 0 items';
            renderPagination(pagination.totalPages);
            return;
        }

        elements.tableBody.innerHTML = visibleItems.map((item, index) => `
            <tr>
                <td class="inventory-row-number">${pagination.startIndex + index + 1}</td>
                <td class="inventory-cell-code">${escapeHtml(item.inventory_no)}</td>
                <td>${escapeHtml(item.item_name)}</td>
                <td>${escapeHtml(item.category)}</td>
                <td>${escapeHtml(item.company)}</td>
                <td>${escapeHtml(item.department)}</td>
                <td class="inventory-cell-code">${escapeHtml(item.serial_number)}</td>
                <td>${escapeHtml(item.model)}</td>
                <td>${escapeHtml(item.deploy_to)}</td>
                <td>${escapeHtml(item.priority)}</td>
                <td>
                    <span class="inventory-status-badge" style="--status-bg:${escapeHtml(getStatusBadgeStyle(item.deployment_status).bg)};--status-fg:${escapeHtml(getStatusBadgeStyle(item.deployment_status).fg)};">
                        ${escapeHtml(item.deployment_status)}
                    </span>
                </td>
                <td>
                    <button class="btn btn-success btn-xs" type="button" data-item="${escapeHtml(item.item_name)}">
                        Deploy
                    </button>
                </td>
            </tr>
        `).join('');

        const from = pagination.startIndex + 1;
        const to = pagination.startIndex + visibleItems.length;
        elements.tableSummary.textContent = `Showing ${from} to ${to} of ${pagination.totalItems} items`;

        renderPagination(pagination.totalPages);
    }

    function resetFilters() {
        state.search = '';
        state.category = '';
        state.company = '';
        state.department = '';
        state.entries = 10;
        state.page = 1;

        if (elements.searchInput) elements.searchInput.value = '';
        if (elements.categoryFilter) elements.categoryFilter.value = '';
        if (elements.companyFilter) elements.companyFilter.value = '';
        if (elements.departmentFilter) elements.departmentFilter.value = '';
        if (elements.entriesSelect) elements.entriesSelect.value = '10';

        renderTable();
    }

    populateFilter(elements.categoryFilter, items.map((item) => item.category));
    populateFilter(elements.companyFilter, items.map((item) => item.company));
    populateFilter(elements.departmentFilter, items.map((item) => item.department));
    renderTable();

    if (elements.searchInput) {
        elements.searchInput.addEventListener('input', (event) => {
            state.search = event.target.value || '';
            state.page = 1;
            renderTable();
        });
    }

    if (elements.entriesSelect) {
        elements.entriesSelect.addEventListener('change', (event) => {
            state.entries = Number(event.target.value) || 10;
            state.page = 1;
            renderTable();
        });
    }

    if (elements.categoryFilter) {
        elements.categoryFilter.addEventListener('change', (event) => {
            state.category = event.target.value || '';
            state.page = 1;
            renderTable();
        });
    }

    if (elements.companyFilter) {
        elements.companyFilter.addEventListener('change', (event) => {
            state.company = event.target.value || '';
            state.page = 1;
            renderTable();
        });
    }

    if (elements.departmentFilter) {
        elements.departmentFilter.addEventListener('change', (event) => {
            state.department = event.target.value || '';
            state.page = 1;
            renderTable();
        });
    }

    if (elements.resetButton) {
        elements.resetButton.addEventListener('click', resetFilters);
    }

    if (elements.pagination) {
        elements.pagination.addEventListener('click', (event) => {
            const button = event.target.closest('[data-page]');

            if (!button) {
                return;
            }

            state.page = Number(button.dataset.page) || 1;
            renderTable();
        });
    }

    if (elements.tableBody) {
        elements.tableBody.addEventListener('click', (event) => {
            const button = event.target.closest('button[data-item]');

            if (!button) {
                return;
            }

            showToast(`Preview only: ${button.dataset.item} is ready for deployment.`, 'info');
        });
    }
})();
