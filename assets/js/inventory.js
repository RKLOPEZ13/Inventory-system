(function () {
    const pageData = window.inventoryPageData;
    if (!pageData) {
        return;
    }

    const columns = Array.isArray(pageData.columns) ? pageData.columns : [];
    const items = Array.isArray(pageData.items) ? pageData.items : [];
    const formColumns = Array.isArray(pageData.formColumns) ? pageData.formColumns : [];
    const lookups = pageData.lookups || {};
    const config = pageData.config || {};
    const nextInventoryNo = pageData.nextInventoryNo || '';
    const currentUserName = pageData.currentUserName || 'System Admin';
    const statusColorMap = {
        inventory_status_id: {
            STOLEN: { bg: '#fee2e2', fg: '#b91c1c' },
            MISSING: { bg: '#ffedd5', fg: '#c2410c' },
            SPARE: { bg: '#ccfbf1', fg: '#0f766e' },
            AVAILABLE: { bg: '#dcfce7', fg: '#15803d' },
        },
        deployment_status_id: {
            RETURNED: { bg: '#cffafe', fg: '#0e7490' },
            'RETURNED WITH ISSUE/S': { bg: '#fae8ff', fg: '#a21caf' },
            TEMPORARY: { bg: '#e0f2fe', fg: '#0369a1' },
            DEPLOYED: { bg: '#d1fae5', fg: '#047857' },
            TRANSFER: { bg: '#ede9fe', fg: '#6d28d9' },
            BORROWED: { bg: '#fef9c3', fg: '#a16207' },
        },
    };
    const columnLabels = {
        inventory_no: 'INVENTORY NO.',
        company_id: 'COMPANY',
        category_id: 'CATEGORY',
        sub_category_id: 'SUB CATEGORY',
        brand_id: 'BRAND',
        model: 'MODEL',
        item_description: 'ITEM DESCRIPTION',
        serial_number: 'SERIAL NUMBER',
        custodian_id: 'CUSTODIAN',
        department_id: 'DEPARTMENT',
        mac_address: 'MAC ADDRESS',
        device_name: 'DEVICE NAME',
        current_os: 'CURRENT OS',
        device_age_months: 'DEVICE AGE',
        age_status_id: 'AGE STATUS',
        inventory_status_id: 'INVENTORY STATUS',
        deployment_status_id: 'DEPLOYMENT STATUS',
        deployed_date: 'DEPLOYMENT DATE',
        returned_date: 'RETURNED DATE',
        purchase_date: 'PURCHASE DATE',
        purchase_month: 'PURCHASE MONTH',
        purchase_year: 'PURCHASE YEAR',
        remarks: 'REMARKS',
        created_at: 'ADDED AT',
        updated_at: 'UPDATED AT',
    };
    const lookupLabelMaps = Object.fromEntries(
        Object.entries(lookups).map(([columnName, options]) => [
            columnName,
            new Map(
                (Array.isArray(options) ? options : []).map((option) => [
                    normalizeLookupKey(option.option_value),
                    option.option_label,
                ])
            ),
        ])
    );

    const state = {
        search: '',
        entries: 10,
        currentPage: 1,
        filters: {
            category_id: '',
            sub_category_id: '',
            inventory_status_id: '',
            deployment_status_id: '',
        },
        visibleColumns: new Set(columns.map((column) => column.name)),
        itemModalMode: 'add',
        deploySearch: '',
        selectedDeployInventoryId: '',
    };

    const elements = {
        searchInput: document.getElementById('inventorySearchInput'),
        entriesSelect: document.getElementById('inventoryEntriesSelect'),
        filterSelects: Array.from(document.querySelectorAll('.inventory-filter-select')),
        resetButton: document.getElementById('inventoryResetBtn'),
        exportButton: document.getElementById('inventoryExportBtn'),
        addButton: document.getElementById('inventoryAddBtn'),
        deployButton: document.getElementById('inventoryDeployBtn'),
        columnToggle: document.getElementById('inventoryColumnToggle'),
        columnFilter: document.querySelector('.inventory-column-filter'),
        columnPanel: document.getElementById('inventoryColumnPanel'),
        columnOptions: document.getElementById('inventoryColumnOptions'),
        tableHead: document.getElementById('inventoryTableHead'),
        tableBody: document.getElementById('inventoryTableBody'),
        summary: document.getElementById('inventoryTableSummary'),
        pagination: document.getElementById('inventoryPagination'),
        itemModalTitle: document.getElementById('inventoryItemModalTitle'),
        itemForm: document.getElementById('inventoryItemForm'),
        itemFields: document.getElementById('inventoryItemFields'),
        itemIdInput: document.getElementById('inventoryItemId'),
        itemSubmitButton: document.getElementById('inventoryItemSubmitBtn'),
        itemModalSubtext: document.getElementById('inventoryItemModalSubtext'),
        deployForm: document.getElementById('inventoryDeployForm'),
        deploySearchShell: document.getElementById('deploySearchShell'),
        deploySearchInput: document.getElementById('deployInventorySearch'),
        deployResults: document.getElementById('deployInventoryResults'),
        deploySelectedItem: document.getElementById('deploySelectedItem'),
        deployInventoryId: document.getElementById('deployInventoryId'),
        deployCustodianId: document.getElementById('deployCustodianId'),
        deployDepartmentId: document.getElementById('deployDepartmentId'),
        deployDeploymentStatusId: document.getElementById('deployDeploymentStatusId'),
        deployDate: document.getElementById('deployDate'),
        deployProcessedBy: document.getElementById('deployProcessedBy'),
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

    function normalizeLookupKey(value) {
        return value == null ? '' : String(value);
    }

    function parseInventoryNoMeta(value) {
        const match = String(value || '').match(/^(.*?)(\d+)$/);
        if (!match) {
            return null;
        }

        return {
            prefix: match[1],
            sequence: Number(match[2]),
            width: match[2].length,
        };
    }

    function formatInventoryNoPreview(offset = 0) {
        const meta = parseInventoryNoMeta(nextInventoryNo);
        if (!meta) {
            return nextInventoryNo || 'Auto-generated';
        }

        return `${meta.prefix}${String(meta.sequence + offset).padStart(meta.width, '0')}`;
    }

    function getColumnLabel(columnName) {
        return columnLabels[columnName] || columnName.replace(/_/g, ' ').toUpperCase();
    }

    function getLookupLabel(columnName, value) {
        const lookupMap = lookupLabelMaps[columnName];
        if (!lookupMap) {
            return null;
        }

        return lookupMap.get(normalizeLookupKey(value)) ?? null;
    }

    function getDisplayValue(columnName, value) {
        if (value == null || value === '') {
            return '';
        }

        return getLookupLabel(columnName, value) ?? String(value);
    }

    function getCompactValue(columnName, value) {
        const displayValue = getDisplayValue(columnName, value);
        if (!['item_description', 'remarks'].includes(columnName)) {
            return displayValue;
        }

        const singleLineValue = displayValue.replace(/\s+/g, ' ').trim();
        if (singleLineValue.length <= 24) {
            return singleLineValue;
        }

        return `${singleLineValue.slice(0, 24)}...`;
    }

    function parseLocalDate(dateString) {
        if (!dateString) {
            return null;
        }

        const parsedDate = new Date(`${dateString}T00:00:00`);
        return Number.isNaN(parsedDate.getTime()) ? null : parsedDate;
    }

    function getDaysInMonth(year, monthIndex) {
        return new Date(year, monthIndex + 1, 0).getDate();
    }

    function calculateDeviceAgeMonthsFromDate(dateString) {
        const purchaseDate = parseLocalDate(dateString);
        if (!purchaseDate) {
            return '';
        }

        const today = new Date();
        let years = today.getFullYear() - purchaseDate.getFullYear();
        let months = today.getMonth() - purchaseDate.getMonth();
        let days = today.getDate() - purchaseDate.getDate();

        if (days < 0) {
            months -= 1;
        }

        if (months < 0) {
            years -= 1;
            months += 12;
        }

        const totalMonths = Math.max(0, (Math.max(0, years) * 12) + Math.max(0, months));
        return String(totalMonths);
    }

    function calculateAgeStatusIdFromMonths(monthsValue) {
        if (monthsValue === '' || monthsValue == null) {
            return '';
        }

        const totalMonths = Number(monthsValue);
        const targetLabel = totalMonths >= 60 ? 'OLD' : 'NEW';
        const options = Array.isArray(lookups.age_status_id) ? lookups.age_status_id : [];
        const match = options.find((option) => String(option.option_label).trim().toUpperCase() === targetLabel);

        return match ? normalizeValue(match.option_value) : '';
    }

    function getAgeStatusMeta(item) {
        const ageDetails = getDeviceAgeDetails(item);
        const totalMonths = Number(ageDetails.badge) || 0;
        const ageStatusId = calculateAgeStatusIdFromMonths(String(totalMonths));
        const ageStatusLabel = getDisplayValue('age_status_id', ageStatusId) || (totalMonths >= 60 ? 'OLD' : 'NEW');

        return {
            id: ageStatusId,
            label: ageStatusLabel,
        };
    }

    function getStatusBadgeStyle(columnName, label) {
        const normalizedLabel = String(label || '').trim().toUpperCase();
        const columnMap = statusColorMap[columnName] || {};
        return columnMap[normalizedLabel] || { bg: '#f4f4f5', fg: '#3f3f46' };
    }

    function getDeviceAgeDetails(item) {
        const purchaseDate = parseLocalDate(item.purchase_date);
        const fallbackMonths = Number(item.device_age_months) || 0;

        if (!purchaseDate) {
            return {
                text: fallbackMonths > 0 ? `${fallbackMonths} month${fallbackMonths === 1 ? '' : 's'}` : '0 months',
                badge: Math.max(0, fallbackMonths),
                title: fallbackMonths > 0 ? `${fallbackMonths} total month${fallbackMonths === 1 ? '' : 's'}` : 'Device age unavailable',
            };
        }

        const today = new Date();
        let years = today.getFullYear() - purchaseDate.getFullYear();
        let months = today.getMonth() - purchaseDate.getMonth();
        let days = today.getDate() - purchaseDate.getDate();

        if (days < 0) {
            months -= 1;
            const previousMonthIndex = (today.getMonth() - 1 + 12) % 12;
            const previousMonthYear = previousMonthIndex === 11 ? today.getFullYear() - 1 : today.getFullYear();
            days += getDaysInMonth(previousMonthYear, previousMonthIndex);
        }

        if (months < 0) {
            years -= 1;
            months += 12;
        }

        years = Math.max(0, years);
        months = Math.max(0, months);
        days = Math.max(0, days);

        return {
            text: `${years}y ${months}m ${days}d`,
            badge: (years * 12) + months,
            title: `Purchased on ${item.purchase_date}`,
        };
    }

    function getVisibleColumns() {
        return columns.filter((column) => state.visibleColumns.has(column.name));
    }

    function getSearchableValue(columnName, item) {
        if (columnName === 'device_age_months') {
            return getDeviceAgeDetails(item).text;
        }

        if (columnName === 'age_status_id') {
            return getAgeStatusMeta(item).label;
        }

        return getDisplayValue(columnName, item[columnName]);
    }

    function getFilteredItems() {
        return items.filter((item) => {
            const matchesFilters = Object.entries(state.filters).every(([columnName, expectedValue]) => {
                if (!expectedValue) {
                    return true;
                }

                if (columnName === 'age_status_id') {
                    return normalizeValue(getAgeStatusMeta(item).id) === normalizeValue(expectedValue);
                }

                return normalizeValue(item[columnName]) === normalizeValue(expectedValue);
            });

            if (!matchesFilters) {
                return false;
            }

            if (!state.search) {
                return true;
            }

            const searchableText = columns
                .map((column) => getSearchableValue(column.name, item).toLowerCase())
                .join(' ');

            return searchableText.includes(state.search);
        });
    }

    function getPaginatedItems(filteredItems) {
        const totalPages = Math.max(1, Math.ceil(filteredItems.length / state.entries));
        if (state.currentPage > totalPages) {
            state.currentPage = totalPages;
        }

        const startIndex = (state.currentPage - 1) * state.entries;
        return {
            totalPages,
            startIndex,
            rows: filteredItems.slice(startIndex, startIndex + state.entries),
        };
    }

    function cellClassName(columnName, value, item) {
        const classNames = [];

        if ((value == null || value === '') && !(columnName === 'device_age_months' && item.purchase_date)) {
            classNames.push('inventory-cell-empty');
        }

        if (columnName === 'item_description' || columnName === 'remarks') {
            classNames.push('inventory-cell-preview');
        }

        if ((!lookupLabelMaps[columnName] && /_id$/.test(columnName)) || ['inventory_no', 'serial_number', 'mac_address'].includes(columnName)) {
            classNames.push('inventory-cell-code');
        }

        return classNames.join(' ');
    }

    function renderHeader() {
        const visibleColumns = getVisibleColumns();
        const headerCells = visibleColumns
            .map((column) => `<th class="inventory-column-name">${escapeHtml(getColumnLabel(column.name))}</th>`)
            .join('');

        elements.tableHead.innerHTML = `
            <tr>
                <th>#</th>
                ${headerCells}
                <th>Action</th>
            </tr>
        `;
    }

    function renderBody(filteredItems) {
        const visibleColumns = getVisibleColumns();
        const { rows, startIndex } = getPaginatedItems(filteredItems);

        if (!rows.length) {
            elements.tableBody.innerHTML = `
                <tr>
                    <td colspan="${visibleColumns.length + 2}" class="inventory-empty-cell">
                        No inventory records match the current filters.
                    </td>
                </tr>
            `;
            return;
        }

        elements.tableBody.innerHTML = rows.map((item, rowIndex) => {
            const cells = visibleColumns.map((column) => {
                const value = item[column.name];
                const resolvedValue = getDisplayValue(column.name, value);
                let displayValue = '&mdash;';

                if (column.name === 'device_age_months') {
                    const ageDetails = getDeviceAgeDetails(item);
                    const ageTone = Number(ageDetails.badge) >= 60 ? 'old' : 'new';
                    displayValue = `
                        <div class="inventory-age-cell inventory-age-cell-${escapeHtml(ageTone)}" title="${escapeHtml(ageDetails.title)}">
                            <span class="inventory-age-text">${escapeHtml(ageDetails.text)}</span>
                        </div>
                    `;
                } else if (column.name === 'age_status_id') {
                    const ageStatus = getAgeStatusMeta(item);
                    displayValue = `<span class="inventory-plain-status">${escapeHtml(ageStatus.label)}</span>`;
                } else if ((column.name === 'inventory_status_id' || column.name === 'deployment_status_id') && resolvedValue !== '') {
                    const badgeStyle = getStatusBadgeStyle(column.name, resolvedValue);
                    displayValue = `
                        <span class="inventory-status-badge" style="--status-bg:${escapeHtml(badgeStyle.bg)};--status-fg:${escapeHtml(badgeStyle.fg)};">
                            ${escapeHtml(resolvedValue)}
                        </span>
                    `;
                } else if (resolvedValue !== '') {
                    if (column.name === 'item_description' || column.name === 'remarks') {
                        displayValue = `
                            <span class="inventory-cell-tooltip" tabindex="0" data-tooltip="${escapeHtml(resolvedValue)}">                                
                              ${escapeHtml(getCompactValue(column.name, value))}
                            </span>
                        `;
                    } else {
                        displayValue = escapeHtml(resolvedValue).replace(/\n/g, '<br>');
                    }
                }

                return `<td class="${cellClassName(column.name, value, item)}">${displayValue}</td>`;
            }).join('');

            const disabledAttribute = config.canEdit ? '' : 'disabled';
            const disabledTitle = config.canEdit ? '' : 'title="Read-only access"';

            return `
                <tr>
                    <td class="inventory-row-number">${startIndex + rowIndex + 1}</td>
                    ${cells}
                    <td>
                        <div class="col-actions">
                            <button class="btn btn-secondary btn-xs inventory-action-btn" type="button" data-action="update" data-id="${item.inventory_id}" aria-label="Update item" ${disabledAttribute} ${disabledTitle}>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M12 20h9"/>
                                    <path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                                </svg>
                                <span class="inventory-action-label">Update</span>
                            </button>
                            <button class="btn btn-danger btn-xs inventory-action-btn" type="button" data-action="delete" data-id="${item.inventory_id}" aria-label="Delete item" ${disabledAttribute} ${disabledTitle}>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                                <span class="inventory-action-label">Delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function renderSummary(filteredItems) {
        if (!filteredItems.length) {
            elements.summary.textContent = 'Showing 0 to 0 of 0 items';
            return;
        }

        const start = (state.currentPage - 1) * state.entries + 1;
        const end = Math.min(state.currentPage * state.entries, filteredItems.length);
        elements.summary.textContent = `Showing ${start} to ${end} of ${filteredItems.length} items`;
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

    function renderPagination(filteredItems) {
        const totalPages = Math.max(1, Math.ceil(filteredItems.length / state.entries));
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

    // --- GLOBAL TOOLTIP LOGIC ---
    const globalTooltip = document.createElement('div');
    globalTooltip.className = 'global-tooltip';
    document.body.appendChild(globalTooltip);

    document.addEventListener('mouseover', (event) => {
        const target = event.target.closest('.inventory-cell-tooltip');
        if (target) {
            // Get text and position
            globalTooltip.innerHTML = target.getAttribute('data-tooltip').replace(/\n/g, '<br>');
            const rect = target.getBoundingClientRect();
            
            // Position tooltip globally on the page
            globalTooltip.style.top = `${rect.bottom + window.scrollY + 8}px`;
            globalTooltip.style.left = `${rect.left + window.scrollX}px`;
            globalTooltip.classList.add('visible');
        }
    });

    document.addEventListener('mouseout', (event) => {
        if (event.target.closest('.inventory-cell-tooltip')) {
            globalTooltip.classList.remove('visible');
        }
    });
    // ----------------------------

    function renderColumnOptions() {
        elements.columnOptions.innerHTML = columns.map((column) => `
            <label class="inventory-column-option">
                <input
                    type="checkbox"
                    value="${escapeHtml(column.name)}"
                    ${state.visibleColumns.has(column.name) ? 'checked' : ''}
                >
                <span>${escapeHtml(getColumnLabel(column.name))}</span>
            </label>
        `).join('');
    }

    function renderTable() {
        const filteredItems = getFilteredItems();
        renderHeader();
        renderBody(filteredItems);
        renderSummary(filteredItems);
        renderPagination(filteredItems);
        initializeTableKeyboardSupport();
    }

    function initializeTableKeyboardSupport() {
        const scrollContainer = document.querySelector('.inventory-table-scroll');
        if (!scrollContainer) return;

        // Check if table overflows horizontally
        const isScrollable = scrollContainer.scrollWidth > scrollContainer.clientWidth;
        
        // Set tabindex only if scrollable
        if (isScrollable) {
            scrollContainer.setAttribute('tabindex', '0');
            scrollContainer.setAttribute('role', 'region');
            scrollContainer.setAttribute('aria-label', 'Inventory table, use arrow keys to scroll');
        } else {
            scrollContainer.removeAttribute('tabindex');
            scrollContainer.removeAttribute('role');
            scrollContainer.removeAttribute('aria-label');
        }
    }

    function lookupOptionsHtml(columnName, selectedValue, includeEmptyOption) {
        const options = Array.isArray(lookups[columnName]) ? lookups[columnName] : [];
        const initialOption = includeEmptyOption ? '<option value="">Select option</option>' : '';

        return initialOption + options.map((option) => {
            const isSelected = normalizeValue(option.option_value) === normalizeValue(selectedValue);
            return `
                <option value="${escapeHtml(option.option_value)}" ${isSelected ? 'selected' : ''}>
                    ${escapeHtml(option.option_label)}
                </option>
            `;
        }).join('');
    }

    function bulkCountFieldHtml() {
        return `
            <div class="form-col">
                <label class="label" for="field_bulk_count">NUMBER OF ENTRIES</label>
                <input
                    class="input"
                    id="field_bulk_count"
                    name="bulk_count"
                    type="number"
                    value="1"
                    min="1"
                    step="1"
                    inputmode="numeric"
                >
                <div class="field-note">Add several identical inventory records in one save.</div>
            </div>
        `;
    }

    function inputFieldHtml(column, item, mode) {
        const value = item ? item[column.name] : '';
        const required = column.required ? 'required' : '';
        const fullWidth = ['item_description', 'remarks', 'device_name', 'mac_address'].includes(column.name) ? 'form-full' : '';
        const label = escapeHtml(getColumnLabel(column.name));
        const name = escapeHtml(column.name);

        if (column.name === 'inventory_no') {
            const displayValue = mode === 'update' ? normalizeValue(value) : formatInventoryNoPreview(0);
            const note = mode === 'update'
                ? 'Auto-generated inventory number. This value cannot be changed.'
                : 'Auto-generated on save and kept sequential automatically.';

            return `
                <div class="form-col">
                    <label class="label" for="field_${name}">${label}</label>
                    <input
                        class="input"
                        id="field_${name}"
                        name="${name}"
                        type="text"
                        value="${escapeHtml(displayValue)}"
                        readonly
                        tabindex="-1"
                    >
                    <div class="field-note" id="field_${name}_note">${escapeHtml(note)}</div>
                </div>
            `;
        }

        if (column.name === 'device_age_months') {
            const calculatedValue = item ? calculateDeviceAgeMonthsFromDate(item.purchase_date) : '';
            return `
                <div class="form-col">
                    <label class="label" for="field_${name}">${label}</label>
                    <input
                        class="input"
                        id="field_${name}"
                        name="${name}"
                        type="number"
                        value="${escapeHtml(calculatedValue)}"
                        readonly
                        tabindex="-1"
                        data-auto-age-field="true"
                    >
                    <div class="field-note">Auto-calculated from Purchase Date.</div>
                </div>
            `;
        }

        if (column.name === 'age_status_id') {
            const selectedValue = normalizeValue(value);
            return `
                <div class="form-col">
                    <label class="label" for="field_${name}_display">${label}</label>
                    <input type="hidden" id="field_${name}" name="${name}" value="${escapeHtml(selectedValue)}">
                    <div class="inventory-age-status-display" id="field_${name}_display" data-auto-age-status-display="true"></div>
                    <div class="field-note">Auto-set from Device Age. Under 5 years is NEW, 5 years and above is OLD.</div>
                </div>
            `;
        }

        if (column.is_lookup) {
            return `
                <div class="form-col ${fullWidth}">
                    <label class="label" for="field_${name}">${label}</label>
                    <select class="select" id="field_${name}" name="${name}" ${required}>
                        ${lookupOptionsHtml(column.name, value, !column.required)}
                    </select>
                </div>
            `;
        }

        if (column.input_type === 'textarea') {
            return `
                <div class="form-col form-full">
                    <label class="label" for="field_${name}">${label}</label>
                    <textarea class="textarea" id="field_${name}" name="${name}" ${required}>${escapeHtml(normalizeValue(value))}</textarea>
                </div>
            `;
        }

        const type = column.input_type === 'number' ? 'number' : column.input_type;
        const extraAttributes = type === 'number' ? 'min="0"' : '';

        return `
            <div class="form-col ${fullWidth}">
                <label class="label" for="field_${name}">${label}</label>
                <input
                    class="input"
                    id="field_${name}"
                    name="${name}"
                    type="${type}"
                    value="${escapeHtml(normalizeValue(value))}"
                    ${required}
                    ${extraAttributes}
                >
            </div>
        `;
    }

    function buildItemFieldsHtml(mode, item) {
        const fields = [];
        let bulkInserted = false;

        formColumns.forEach((column) => {
            fields.push(inputFieldHtml(column, item, mode));

            if (mode === 'add' && column.name === 'inventory_no') {
                fields.push(bulkCountFieldHtml());
                bulkInserted = true;
            }
        });

        if (mode === 'add' && !bulkInserted) {
            fields.unshift(bulkCountFieldHtml());
        }

        return fields.join('');
    }

    function fillItemForm(mode, inventoryId) {
        const item = mode === 'update'
            ? items.find((entry) => Number(entry.inventory_id) === Number(inventoryId))
            : null;

        state.itemModalMode = mode;
        elements.itemModalTitle.textContent = mode === 'update' ? 'Update Item' : 'Add Item';
        elements.itemSubmitButton.textContent = mode === 'update' ? 'Update Item' : 'Add Item';
        if (elements.itemModalSubtext) {
            elements.itemModalSubtext.textContent = mode === 'update'
                ? 'Review and update the selected inventory record.'
                : 'Fill out the item once, then choose how many identical entries to create.';
        }
        elements.itemIdInput.value = item ? item.inventory_id : '';
        elements.itemFields.innerHTML = buildItemFieldsHtml(mode, item);
        syncItemFormDerivedFields();
    }

    function openItemModal(mode, inventoryId) {
        if (!config.canEdit) {
            showToast('This account is read-only.', 'info');
            return;
        }

        fillItemForm(mode, inventoryId);
        openModal('inventoryItemModal');
    }

    function syncItemFormDerivedFields() {
        const purchaseDateField = document.getElementById('field_purchase_date');
        const deviceAgeField = document.getElementById('field_device_age_months');
        const ageStatusField = document.getElementById('field_age_status_id');
        const ageStatusDisplayField = document.getElementById('field_age_status_id_display');
        const inventoryNoField = document.getElementById('field_inventory_no');
        const inventoryNoNote = document.getElementById('field_inventory_no_note');
        const bulkCountField = document.getElementById('field_bulk_count');

        if (inventoryNoField && state.itemModalMode === 'add') {
            const bulkCount = Math.max(1, Number(bulkCountField?.value) || 1);
            const startInventoryNo = formatInventoryNoPreview(0);
            const endInventoryNo = formatInventoryNoPreview(bulkCount - 1);

            inventoryNoField.value = startInventoryNo;

            if (inventoryNoNote) {
                inventoryNoNote.textContent = bulkCount > 1
                    ? `Auto-generated on save from ${startInventoryNo} to ${endInventoryNo}.`
                    : `Auto-generated on save as ${startInventoryNo}.`;
            }
        }

        if (!purchaseDateField || !deviceAgeField) {
            return;
        }

        const calculatedMonths = calculateDeviceAgeMonthsFromDate(purchaseDateField.value);
        const ageStatusId = calculateAgeStatusIdFromMonths(calculatedMonths);
        const ageStatusLabel = getDisplayValue('age_status_id', ageStatusId) || (Number(calculatedMonths || 0) >= 60 ? 'OLD' : 'NEW');

        deviceAgeField.value = calculatedMonths;

        if (ageStatusField) {
            ageStatusField.value = ageStatusId;
        }

        if (ageStatusDisplayField) {
            ageStatusDisplayField.innerHTML = `
                <span class="inventory-plain-status">
                    ${escapeHtml(ageStatusLabel || 'Age status unavailable')}
                </span>
            `;
        }
    }

    function inventoryLabel(item) {
        const parts = [
            item.inventory_no,
            item.device_name,
            item.model,
            item.serial_number,
        ].filter(Boolean);

        return parts.join(' | ');
    }

    function todayAsInputDate() {
        const now = new Date();
        const offset = now.getTimezoneOffset();
        return new Date(now.getTime() - (offset * 60000)).toISOString().slice(0, 10);
    }

    function getDeploySearchText(item) {
        return [
            item.inventory_no,
            getDisplayValue('company_id', item.company_id),
            getDisplayValue('category_id', item.category_id),
            getDisplayValue('sub_category_id', item.sub_category_id),
            getDisplayValue('brand_id', item.brand_id),
            item.model,
            item.serial_number,
            item.device_name,
            getDisplayValue('inventory_status_id', item.inventory_status_id),
        ].filter(Boolean).join(' ').toLowerCase();
    }

    function getDeploySearchResults() {
        const search = state.deploySearch.trim().toLowerCase();
        const matchingItems = search
            ? items.filter((item) => getDeploySearchText(item).includes(search))
            : items;

        return matchingItems.slice(0, 8);
    }

    function deployResultCells(item) {
        const ageDetails = getDeviceAgeDetails(item);
        const ageStatus = getAgeStatusMeta(item);
        const inventoryStatusLabel = getDisplayValue('inventory_status_id', item.inventory_status_id);
        const inventoryStatusStyle = getStatusBadgeStyle('inventory_status_id', inventoryStatusLabel);

        return `
            <td class="inventory-cell-code">${escapeHtml(item.inventory_no || '')}</td>
            <td>${escapeHtml(getDisplayValue('company_id', item.company_id) || '—')}</td>
            <td>${escapeHtml(getDisplayValue('category_id', item.category_id) || '—')}</td>
            <td>${escapeHtml(getDisplayValue('sub_category_id', item.sub_category_id) || '—')}</td>
            <td>${escapeHtml(getDisplayValue('brand_id', item.brand_id) || '—')}</td>
            <td>${escapeHtml(item.model || '—')}</td>
            <td class="inventory-cell-code">${escapeHtml(item.serial_number || '—')}</td>
            <td>
                <div class="inventory-age-cell inventory-age-cell-${Number(ageDetails.badge) >= 60 ? 'old' : 'new'}">
                    <span class="inventory-age-text">${escapeHtml(ageDetails.text)}</span>
                </div>
            </td>
            <td><span class="inventory-plain-status">${escapeHtml(ageStatus.label)}</span></td>
            <td>
                ${inventoryStatusLabel ? `
                    <span class="inventory-status-badge" style="--status-bg:${escapeHtml(inventoryStatusStyle.bg)};--status-fg:${escapeHtml(inventoryStatusStyle.fg)};">
                        ${escapeHtml(inventoryStatusLabel)}
                    </span>
                ` : '&mdash;'}
            </td>
        `;
    }

    function fillDeploySelect(selectElement, columnName, includeEmptyOption) {
        if (!selectElement) {
            return;
        }

        selectElement.innerHTML = lookupOptionsHtml(columnName, '', includeEmptyOption);
    }

    function deploySelectedSummary(item) {
        if (!item) {
            return 'No inventory item selected yet.';
        }

        const detailParts = [
            item.inventory_no,
            getDisplayValue('company_id', item.company_id),
            item.model,
            item.serial_number,
        ].filter(Boolean);

        return `Selected item: ${detailParts.join(' | ')}`;
    }

    function setDefaultDeploymentStatus() {
        if (!elements.deployDeploymentStatusId) {
            return;
        }

        const deployedOption = (Array.isArray(lookups.deployment_status_id) ? lookups.deployment_status_id : [])
            .find((option) => String(option.option_label).trim().toUpperCase() === 'DEPLOYED');

        elements.deployDeploymentStatusId.value = deployedOption
            ? normalizeValue(deployedOption.option_value)
            : '';
    }

    function renderDeployResults() {
        if (!elements.deployResults) {
            return;
        }

        const results = getDeploySearchResults();

        if (!results.length) {
            elements.deployResults.innerHTML = `
                <tr>
                    <td colspan="10" class="inventory-empty-cell">No inventory items match your search.</td>
                </tr>
            `;
            return;
        }

        elements.deployResults.innerHTML = results.map((item) => `
            <tr class="inventory-deploy-result-row ${Number(state.selectedDeployInventoryId) === Number(item.inventory_id) ? 'is-selected' : ''}" data-id="${item.inventory_id}">
                ${deployResultCells(item)}
            </tr>
        `).join('');
    }

    function syncDeployFormToItem(inventoryId) {
        const item = items.find((entry) => Number(entry.inventory_id) === Number(inventoryId));

        state.selectedDeployInventoryId = item ? normalizeValue(item.inventory_id) : '';

        if (elements.deployInventoryId) {
            elements.deployInventoryId.value = state.selectedDeployInventoryId;
        }

        if (!item) {
            state.deploySearch = '';
            if (elements.deploySelectedItem) {
                elements.deploySelectedItem.textContent = deploySelectedSummary(null);
            }
            renderDeployResults();
            return;
        }

        state.deploySearch = inventoryLabel(item);

        if (elements.deploySelectedItem) {
            elements.deploySelectedItem.textContent = deploySelectedSummary(item);
        }

        if (elements.deploySearchInput) {
            elements.deploySearchInput.value = inventoryLabel(item);
        }

        elements.deployCustodianId.value = normalizeValue(item.custodian_id);
        elements.deployDepartmentId.value = normalizeValue(item.department_id);
        if (!elements.deployDeploymentStatusId.value) {
            setDefaultDeploymentStatus();
        }
        renderDeployResults();
    }

    function openDeployModal() {
        if (!config.canEdit) {
            showToast('This account is read-only.', 'info');
            return;
        }

        fillDeploySelect(elements.deployCustodianId, 'custodian_id', true);
        fillDeploySelect(elements.deployDepartmentId, 'department_id', true);
        fillDeploySelect(elements.deployDeploymentStatusId, 'deployment_status_id', false);
        state.deploySearch = '';
        state.selectedDeployInventoryId = '';
        if (elements.deploySearchInput) {
            elements.deploySearchInput.value = '';
        }
        if (elements.deployInventoryId) {
            elements.deployInventoryId.value = '';
        }
        if (elements.deployDate) {
            elements.deployDate.value = todayAsInputDate();
        }
        if (elements.deployProcessedBy) {
            elements.deployProcessedBy.value = currentUserName;
        }
        setDefaultDeploymentStatus();
        if (elements.deploySelectedItem) {
            elements.deploySelectedItem.textContent = deploySelectedSummary(null);
        }
        renderDeployResults();
        openModal('inventoryDeployModal');
    }

    async function postForm(endpoint, formData) {
        const response = await fetch(endpoint, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Request failed.');
        }

        return result;
    }

    function exportRows() {
        const visibleColumns = getVisibleColumns();
        const filteredItems = getFilteredItems();

        if (!filteredItems.length) {
            showToast('There are no rows to export.', 'info');
            return;
        }

        const csvRows = [];
        csvRows.push(['#'].concat(visibleColumns.map((column) => getColumnLabel(column.name))).join(','));

        filteredItems.forEach((item, index) => {
            const row = [index + 1].concat(
                visibleColumns.map((column) => {
                    const exportValue = column.name === 'device_age_months'
                        ? getDeviceAgeDetails(item).text
                        : column.name === 'age_status_id'
                            ? getAgeStatusMeta(item).label
                        : getDisplayValue(column.name, item[column.name]);
                    const value = exportValue.replace(/"/g, '""');
                    return `"${value}"`;
                })
            );
            csvRows.push(row.join(','));
        });

        const blob = new Blob([csvRows.join('\n')], { type: 'text/csv;charset=utf-8;' });
        const downloadLink = document.createElement('a');
        const dateStamp = new Date().toISOString().slice(0, 10);
        downloadLink.href = URL.createObjectURL(blob);
        downloadLink.download = `inventory-export-${dateStamp}.csv`;
        downloadLink.click();
        URL.revokeObjectURL(downloadLink.href);
    }

    function closeColumnPanel() {
        if (!elements.columnFilter || !elements.columnToggle) {
            return;
        }

        elements.columnFilter.classList.remove('open');
        elements.columnToggle.setAttribute('aria-expanded', 'false');
    }

    function openColumnPanel() {
        if (!elements.columnFilter || !elements.columnToggle) {
            return;
        }

        elements.columnFilter.classList.add('open');
        elements.columnToggle.setAttribute('aria-expanded', 'true');
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
        elements.addButton?.addEventListener('click', () => openItemModal('add'));
        elements.deployButton?.addEventListener('click', openDeployModal);

        elements.columnToggle?.addEventListener('click', (event) => {
            event.stopPropagation();
            if (elements.columnFilter?.classList.contains('open')) {
                closeColumnPanel();
                return;
            }
            openColumnPanel();
        });

        elements.columnOptions?.addEventListener('change', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLInputElement)) {
                return;
            }

            const columnName = target.value;
            if (!columnName) {
                return;
            }

            if (target.checked) {
                state.visibleColumns.add(columnName);
            } else if (state.visibleColumns.size > 1) {
                state.visibleColumns.delete(columnName);
            } else {
                target.checked = true;
                showToast('At least one column must stay visible.', 'info');
                return;
            }

            state.currentPage = 1;
            renderTable();
        });

        document.addEventListener('click', (event) => {
            if (!elements.columnFilter?.contains(event.target)) {
                closeColumnPanel();
            }

            if (!elements.deploySearchShell?.contains(event.target)) {
                elements.deploySearchShell?.classList.remove('open');
            }
        });

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

        elements.tableBody?.addEventListener('click', (event) => {
            const target = event.target;
            const button = target instanceof HTMLButtonElement ? target : target.closest('button');
            if (!(button instanceof HTMLButtonElement)) {
                return;
            }

            if (button.dataset.action === 'update') {
                openItemModal('update', button.dataset.id);
                return;
            }

            if (button.dataset.action === 'delete') {
                const inventoryId = button.dataset.id;
                const item = items.find((entry) => Number(entry.inventory_id) === Number(inventoryId));
                const itemName = item ? inventoryLabel(item) : `item #${inventoryId}`;

                confirmAction(`Delete ${itemName}?`, async () => {
                    const formData = new FormData();
                    formData.append('inventory_id', inventoryId);

                    button.disabled = true;

                    try {
                        const result = await postForm(config.deleteEndpoint, formData);
                        showToast(result.message || 'Item deleted successfully.', 'success');
                        setTimeout(() => window.location.reload(), 500);
                    } catch (error) {
                        button.disabled = false;
                        showToast(error.message || 'Unable to delete item.', 'error');
                    }
                });
            }
        });

        elements.itemForm?.addEventListener('submit', async (event) => {
            event.preventDefault();

            const formData = new FormData(elements.itemForm);
            const inventoryId = formData.get('inventory_id');
            if (!inventoryId) {
                formData.delete('inventory_id');
            } else {
                formData.delete('bulk_count');
            }

            const originalText = elements.itemSubmitButton.textContent;
            const bulkCount = Math.max(1, Number(formData.get('bulk_count')) || 1);
            elements.itemSubmitButton.disabled = true;
            elements.itemSubmitButton.textContent = state.itemModalMode === 'update'
                ? 'Updating...'
                : bulkCount > 1
                    ? 'Saving Items...'
                    : 'Saving...';

            try {
                const result = await postForm(config.saveEndpoint, formData);
                showToast(result.message || 'Saved successfully.', 'success');
                setTimeout(() => window.location.reload(), 500);
            } catch (error) {
                showToast(error.message || 'Unable to save item.', 'error');
            } finally {
                elements.itemSubmitButton.disabled = false;
                elements.itemSubmitButton.textContent = originalText;
            }
        });

        elements.itemForm?.addEventListener('input', (event) => {
            const target = event.target;
            if (!(target instanceof HTMLInputElement)) {
                return;
            }

            if (target.name === 'purchase_date' || target.name === 'bulk_count') {
                syncItemFormDerivedFields();
            }
        });

        elements.deploySearchInput?.addEventListener('focus', () => {
            elements.deploySearchShell?.classList.add('open');
            renderDeployResults();
        });

        elements.deploySearchInput?.addEventListener('input', (event) => {
            state.deploySearch = event.target.value;
            elements.deploySearchShell?.classList.add('open');
            renderDeployResults();
        });

        elements.deployResults?.addEventListener('click', (event) => {
            const row = event.target instanceof HTMLElement ? event.target.closest('.inventory-deploy-result-row') : null;
            if (!(row instanceof HTMLTableRowElement)) {
                return;
            }

            syncDeployFormToItem(row.dataset.id);
            elements.deploySearchShell?.classList.remove('open');
        });

        elements.deployForm?.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (!elements.deployInventoryId?.value) {
                showToast('Select an inventory item before deploying.', 'info');
                elements.deploySearchInput?.focus();
                elements.deploySearchShell?.classList.add('open');
                return;
            }

            const submitButton = elements.deployForm.querySelector('button[type="submit"]');
            const originalText = submitButton ? submitButton.textContent : 'Deploy Item';
            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Deploying...';
            }

            try {
                const result = await postForm(config.deployEndpoint, new FormData(elements.deployForm));
                showToast(result.message || 'Item deployed successfully.', 'success');
                setTimeout(() => window.location.reload(), 500);
            } catch (error) {
                showToast(error.message || 'Unable to deploy item.', 'error');
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                }
            }
        });
    }

    renderColumnOptions();
    renderTable();
    attachEventListeners();

    if (columns.some((column) => column.name === 'device_age_months')) {
        window.setInterval(() => {
            renderTable();
        }, 60000);
    }
})();
