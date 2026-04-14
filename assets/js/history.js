(function () {
    const pageData = window.historyPageData;
    if (!pageData) {
        return;
    }

    const logs = Array.isArray(pageData.logs) ? pageData.logs : [];
    const state = {
        search: '',
        entries: 10,
        currentPage: 1,
        filters: {
            company_id: '',
            category_id: '',
            department_id: '',
            deployment_status_id: '',
        },
    };

    const statusColorMap = {
        RETURNED: { bg: '#cffafe', fg: '#0e7490' },
        'RETURNED WITH ISSUE/S': { bg: '#fae8ff', fg: '#a21caf' },
        TEMPORARY: { bg: '#e0f2fe', fg: '#0369a1' },
        DEPLOYED: { bg: '#d1fae5', fg: '#047857' },
        TRANSFER: { bg: '#ede9fe', fg: '#6d28d9' },
        BORROWED: { bg: '#fef9c3', fg: '#a16207' },
    };

    const columns = [
        { name: 'log_code', label: 'LOG ID' },
        { name: 'inventory_no', label: 'INVENTORY NO.' },
        { name: 'company_name', label: 'COMPANY' },
        { name: 'category_name', label: 'CATEGORY' },
        { name: 'sub_category_name', label: 'SUB CATEGORY' },
        { name: 'brand_name', label: 'BRAND' },
        { name: 'model', label: 'MODEL' },
        { name: 'serial_number', label: 'SERIAL NUMBER' },
        { name: 'deployed_to', label: 'DEPLOYED TO' },
        { name: 'department_name', label: 'DEPARTMENT' },
        { name: 'deployment_status_name', label: 'DEPLOYMENT STATUS' },
        { name: 'date_deployed', label: 'DATE DEPLOYED' },
        { name: 'returned_date', label: 'RETURNED DATE' },
        { name: 'deployed_by_name', label: 'DEPLOYED BY' },
        { name: 'created_at', label: 'LOGGED AT' },
    ];

    const elements = {
        searchInput: document.getElementById('historySearchInput'),
        entriesSelect: document.getElementById('historyEntriesSelect'),
        filterSelects: Array.from(document.querySelectorAll('.history-filter-select')),
        resetButton: document.getElementById('historyResetBtn'),
        exportButton: document.getElementById('historyExportBtn'),
        tableHead: document.getElementById('historyTableHead'),
        tableBody: document.getElementById('historyTableBody'),
        summary: document.getElementById('historyTableSummary'),
        pagination: document.getElementById('historyPagination'),
    };

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function normalizeValue(value) {
        return value == null ? '' : String(value);
    }

    function formatDateTime(dateString) {
        if (!dateString) {
            return '';
        }

        const date = new Date(dateString);
        if (Number.isNaN(date.getTime())) {
            return String(dateString);
        }

        return date.toLocaleString('en-PH', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
        });
    }

    function formatDateOnly(dateString) {
        if (!dateString) {
            return '';
        }

        const date = new Date(`${dateString}T00:00:00`);
        if (Number.isNaN(date.getTime())) {
            return String(dateString);
        }

        return date.toLocaleDateString('en-PH', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        });
    }

    function getStatusBadgeStyle(label) {
        return statusColorMap[String(label || '').trim().toUpperCase()] || { bg: '#f4f4f5', fg: '#3f3f46' };
    }

    function getCellValue(columnName, log) {
        if (columnName === 'date_deployed' || columnName === 'returned_date') {
            return formatDateOnly(log[columnName]);
        }

        if (columnName === 'created_at') {
            return formatDateTime(log[columnName]);
        }

        return normalizeValue(log[columnName]);
    }

    function getFilteredLogs() {
        return logs.filter((log) => {
            const matchesFilters = Object.entries(state.filters).every(([columnName, expectedValue]) => {
                if (!expectedValue) {
                    return true;
                }

                return normalizeValue(log[columnName]) === normalizeValue(expectedValue);
            });

            if (!matchesFilters) {
                return false;
            }

            if (!state.search) {
                return true;
            }

            const searchableText = columns
                .map((column) => getCellValue(column.name, log).toLowerCase())
                .join(' ');

            return searchableText.includes(state.search);
        });
    }

    function getPaginatedLogs(filteredLogs) {
        const totalPages = Math.max(1, Math.ceil(filteredLogs.length / state.entries));
        if (state.currentPage > totalPages) {
            state.currentPage = totalPages;
        }

        const startIndex = (state.currentPage - 1) * state.entries;
        return {
            totalPages,
            startIndex,
            rows: filteredLogs.slice(startIndex, startIndex + state.entries),
        };
    }

    function renderHeader() {
        elements.tableHead.innerHTML = `
            <tr>
                <th>#</th>
                ${columns.map((column) => `<th>${escapeHtml(column.label)}</th>`).join('')}
            </tr>
        `;
    }

    function renderBody(filteredLogs) {
        const { rows, startIndex } = getPaginatedLogs(filteredLogs);

        if (!rows.length) {
            elements.tableBody.innerHTML = `
                <tr>
                    <td colspan="${columns.length + 1}" class="inventory-empty-cell">
                        No deployment logs match the current filters.
                    </td>
                </tr>
            `;
            return;
        }

        elements.tableBody.innerHTML = rows.map((log, index) => {
            const cells = columns.map((column) => {
                const value = getCellValue(column.name, log);

                if (column.name === 'deployment_status_name' && value) {
                    const badgeStyle = getStatusBadgeStyle(value);
                    return `
                        <td>
                            <span class="inventory-status-badge" style="--status-bg:${escapeHtml(badgeStyle.bg)};--status-fg:${escapeHtml(badgeStyle.fg)};">
                                ${escapeHtml(value)}
                            </span>
                        </td>
                    `;
                }

                if (column.name === 'log_code' || column.name === 'inventory_no' || column.name === 'serial_number') {
                    return `<td class="inventory-cell-code">${escapeHtml(value || '—')}</td>`;
                }

                return `<td>${escapeHtml(value || '—')}</td>`;
            }).join('');

            return `
                <tr>
                    <td class="inventory-row-number">${startIndex + index + 1}</td>
                    ${cells}
                </tr>
            `;
        }).join('');
    }

    function renderSummary(filteredLogs) {
        if (!filteredLogs.length) {
            elements.summary.textContent = 'Showing 0 to 0 of 0 logs';
            return;
        }

        const start = (state.currentPage - 1) * state.entries + 1;
        const end = Math.min(state.currentPage * state.entries, filteredLogs.length);
        elements.summary.textContent = `Showing ${start} to ${end} of ${filteredLogs.length} logs`;
    }

    function paginationButton(label, page, isActive, isDisabled) {
        return `
            <button
                class="page-btn ${isActive ? 'active' : ''}"
                type="button"
                data-page="${page}"
                ${isDisabled ? 'disabled' : ''}
            >${label}</button>
        `;
    }

    function renderPagination(filteredLogs) {
        const totalPages = Math.max(1, Math.ceil(filteredLogs.length / state.entries));
        const buttons = [];

        buttons.push(paginationButton('&lsaquo;', state.currentPage - 1, false, state.currentPage === 1));

        const startPage = Math.max(1, state.currentPage - 1);
        const endPage = Math.min(totalPages, startPage + 2);

        for (let page = startPage; page <= endPage; page += 1) {
            buttons.push(paginationButton(page, page, state.currentPage === page, false));
        }

        buttons.push(paginationButton('&rsaquo;', state.currentPage + 1, false, state.currentPage === totalPages));
        elements.pagination.innerHTML = buttons.join('');
    }

    function renderTable() {
        const filteredLogs = getFilteredLogs();
        renderHeader();
        renderBody(filteredLogs);
        renderSummary(filteredLogs);
        renderPagination(filteredLogs);
    }

    function exportRows() {
        const filteredLogs = getFilteredLogs();

        if (!filteredLogs.length) {
            showToast('There are no deployment logs to export.', 'info');
            return;
        }

        const csvRows = [];
        csvRows.push(['#'].concat(columns.map((column) => column.label)).join(','));

        filteredLogs.forEach((log, index) => {
            const row = [index + 1].concat(
                columns.map((column) => `"${getCellValue(column.name, log).replace(/"/g, '""')}"`)
            );
            csvRows.push(row.join(','));
        });

        const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const downloadLink = document.createElement('a');
        const dateStamp = new Date().toISOString().slice(0, 10);
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = `deployment-history-${dateStamp}.csv`;
        downloadLink.click();
        URL.revokeObjectURL(downloadLink.href);
    }

    function attachEventListeners() {
        elements.searchInput?.addEventListener('input', (event) => {
            state.search = event.target.value.trim().toLowerCase();
            state.currentPage = 1;
            renderTable();
        });

        elements.entriesSelect?.addEventListener('change', (event) => {
            state.entries = Number(event.target.value) || 10;
            state.currentPage = 1;
            renderTable();
        });

        elements.filterSelects.forEach((select) => {
            select.addEventListener('change', (event) => {
                const columnName = event.target.dataset.filterColumn;
                if (!columnName) {
                    return;
                }

                state.filters[columnName] = event.target.value;
                state.currentPage = 1;
                renderTable();
            });
        });

        elements.resetButton?.addEventListener('click', () => {
            state.search = '';
            state.currentPage = 1;
            Object.keys(state.filters).forEach((key) => {
                state.filters[key] = '';
            });

            if (elements.searchInput) {
                elements.searchInput.value = '';
            }

            elements.filterSelects.forEach((select) => {
                select.value = '';
            });

            renderTable();
        });

        elements.exportButton?.addEventListener('click', exportRows);

        elements.pagination?.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLButtonElement) || target.disabled) {
                return;
            }

            const requestedPage = Number(target.dataset.page);
            if (!requestedPage) {
                return;
            }

            state.currentPage = requestedPage;
            renderTable();
        });
    }

    renderTable();
    attachEventListeners();
})();
