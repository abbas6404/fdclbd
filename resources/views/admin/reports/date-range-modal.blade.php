<!-- Date Range Popup Modal -->
<div class="modal fade" id="dateRangeModal" tabindex="-1" aria-labelledby="dateRangeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="dateRangeModalLabel">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Select Date Range
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dateRangeForm">
                    <!-- Date Range Inputs -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">From Date</label>
                            <div class="date-input">
                                <input type="number" id="startDay" placeholder="Day" min="1" max="31" style="border-right: 1px solid rgb(146, 150, 153);">
                                <select id="startMonth" class="month-select"></select>
                                <input type="number" id="startYear" placeholder="Year" min="2000" max="2099" style="border-left: 1px solid rgb(146, 150, 153);">
                            </div>
                            <input type="hidden" id="startDate" name="start_date">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">To Date</label>
                            <div class="date-input">
                                <input type="number" id="endDay" placeholder="Day" min="1" max="31" style="border-right: 1px solid rgb(146, 150, 153);">
                                <select id="endMonth" class="month-select"></select>
                                <input type="number" id="endYear" placeholder="Year" min="2000" max="2099" style="border-left: 1px solid rgb(146, 150, 153);">
                            </div>
                            <input type="hidden" id="endDate" name="end_date">
                        </div>
                    </div>
                    
                    <!-- Dynamic Extra Fields -->
                    <div id="extraFieldsContainer"></div>
                    
                    <!-- Validation Message -->
                    <div id="dateValidationMessage" class="alert alert-danger" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="validationText"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary btn-sm" id="generateReportBtn" onclick="generateReportWithDateRange()">
                    <i class="fas fa-chart-bar me-1"></i>
                    Generate Report
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Date Input Styles -->
<style>
  .date-input {
    display: flex;
    align-items: center;
    gap: 5px;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    padding: 6px 10px;
    width: 100%;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }
  
  .date-input input,
  .date-input select {
    border: none;
    outline: none;
    font-size: 15px;
    background: transparent;
  }
  
  .date-input input[id="startDay"] , .date-input input[id="endDay"] {
    width: 40px;
    text-align: center;
  }

  .date-input input[id="startYear"] , .date-input input[id="endYear"] {
    width: 50px;
    text-align: center;
  }
  .date-input .month-select {
    min-width: 90px;
    width: auto;
    flex: 1;
    text-align: center;
    padding: 0;
    margin: 0;
    cursor: pointer;
  }
  
  /* Hide spinner arrows in Chrome/Firefox */
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }
  
  input[type=number] {
    -moz-appearance: textfield;
  }
  
  /* Hide dropdown arrow */
  .date-input select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: transparent;
    background-image: none;
    padding: 0;
    margin: 0;
  }
  
  .date-input:focus-within {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }
</style>

<!-- JavaScript for Date Range Modal -->
<script>
// Build date string from inputs (yyyy-mm-dd format)
function buildDateFromInputs(prefix) {
    const day = document.getElementById(prefix + 'Day').value;
    const monthSelect = document.getElementById(prefix + 'Month');
    const month = monthSelect ? monthSelect.value : '';
    const year = document.getElementById(prefix + 'Year').value;
    
    if (!day || !month || !year) {
        return '';
    }
    
    const dayPadded = String(day).padStart(2, '0');
    return `${year}-${month}-${dayPadded}`;
}

// Validate date from inputs
function validateDateFromInputs(prefix) {
    const day = document.getElementById(prefix + 'Day').value;
    const monthSelect = document.getElementById(prefix + 'Month');
    const month = monthSelect ? monthSelect.value : '';
    const year = document.getElementById(prefix + 'Year').value;
    
    if (!day || !month || !year) {
        return false;
    }
    
    const dayNum = parseInt(day);
    const monthNum = parseInt(month);
    const yearNum = parseInt(year);
    
    if (dayNum < 1 || dayNum > 31 || monthNum < 1 || monthNum > 12 || yearNum < 2000 || yearNum > 2099) {
        return false;
    }
    
    const date = new Date(yearNum, monthNum - 1, dayNum);
    if (date.getFullYear() !== yearNum || date.getMonth() !== monthNum - 1 || date.getDate() !== dayNum) {
        return false;
    }
    
    return true;
}

// Select month by number (1-12)
function selectMonthByNumber(monthSelect, monthNumber) {
    if (!monthSelect || monthNumber < 1 || monthNumber > 12) {
        return false;
    }
    
    const monthValue = String(monthNumber).padStart(2, '0');
    monthSelect.value = monthValue;
    monthSelect.dispatchEvent(new Event('change', { bubbles: true }));
    return true;
}

// Validation functions
function showValidationMessage(message) {
    document.getElementById('validationText').textContent = message;
    document.getElementById('dateValidationMessage').style.display = 'block';
}

function hideValidationMessage() {
    document.getElementById('dateValidationMessage').style.display = 'none';
}

// Set default dates to today
function setDefaultDates() {
    const today = new Date();
    const day = today.getDate();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const year = today.getFullYear();
    
    document.getElementById('startDay').value = day;
    document.getElementById('startMonth').value = month;
    document.getElementById('startYear').value = year;
    document.getElementById('startDate').value = buildDateFromInputs('start');
    
    document.getElementById('endDay').value = day;
    document.getElementById('endMonth').value = month;
    document.getElementById('endYear').value = year;
    document.getElementById('endDate').value = buildDateFromInputs('end');
}

// Initialize month options
function initializeMonthOptions() {
    const months = [
        { value: '01', name: 'January' },
        { value: '02', name: 'February' },
        { value: '03', name: 'March' },
        { value: '04', name: 'April' },
        { value: '05', name: 'May' },
        { value: '06', name: 'June' },
        { value: '07', name: 'July' },
        { value: '08', name: 'August' },
        { value: '09', name: 'September' },
        { value: '10', name: 'October' },
        { value: '11', name: 'November' },
        { value: '12', name: 'December' }
    ];
    
    const startMonth = document.getElementById('startMonth');
    const endMonth = document.getElementById('endMonth');
    
    const addOptionsToSelect = (selectElement) => {
        if (selectElement) {
            months.forEach(month => {
                const option = document.createElement('option');
                option.value = month.value;
                option.textContent = month.name;
                selectElement.appendChild(option);
            });
        }
    };
    
    addOptionsToSelect(startMonth);
    addOptionsToSelect(endMonth);
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeMonthOptions();
    setDefaultDates();
    
    const dateRangeModal = document.getElementById('dateRangeModal');
    if (dateRangeModal) {
        dateRangeModal.addEventListener('show.bs.modal', function() {
            setDefaultDates();
        });
        
        dateRangeModal.addEventListener('shown.bs.modal', function() {
            setTimeout(() => {
                const startDayInput = document.getElementById('startDay');
                if (startDayInput) {
                    startDayInput.focus();
                    setTimeout(() => {
                        startDayInput.select();
                    }, 50);
                }
            }, 100);
        });
    }
    
    // Setup date inputs
    ['start', 'end'].forEach(prefix => {
        const dayInput = document.getElementById(prefix + 'Day');
        const monthSelect = document.getElementById(prefix + 'Month');
        const yearInput = document.getElementById(prefix + 'Year');
        
        if (dayInput) {
            dayInput.addEventListener('input', (e) => {
                let value = dayInput.value.replace(/\D/g, '');
                if (value.length > 2) value = value.slice(0, 2);
                if (value && (parseInt(value) < 1 || parseInt(value) > 31)) {
                    if (parseInt(value) > 31) value = '31';
                    else if (parseInt(value) === 0) value = '1';
                }
                dayInput.value = value;
                if (value.length >= 2) {
                    setTimeout(() => monthSelect.focus(), 50);
                }
                document.getElementById(prefix + 'Date').value = buildDateFromInputs(prefix);
                hideValidationMessage();
            });
            
            dayInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    monthSelect.focus();
                }
            });
            
            dayInput.addEventListener('focus', function() {
                this.select();
            });
        }
        
        if (monthSelect) {
            monthSelect.addEventListener('change', () => {
                document.getElementById(prefix + 'Date').value = buildDateFromInputs(prefix);
                validateDateRange();
                hideValidationMessage();
            });
        }
        
        if (yearInput) {
            yearInput.addEventListener('input', (e) => {
                let value = yearInput.value.replace(/\D/g, '');
                if (value.length > 4) value = value.slice(0, 4);
                yearInput.value = value;
                document.getElementById(prefix + 'Date').value = buildDateFromInputs(prefix);
                if (value.length === 4) {
                    validateDateRange();
                } else {
                    hideValidationMessage();
                }
            });
            
            yearInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const dateStr = buildDateFromInputs(prefix);
                    if (dateStr && validateDateFromInputs(prefix)) {
                        if (prefix === 'start') {
                            document.getElementById('endDay').focus();
                        } else {
                            validateDateRange();
                            const startDate = buildDateFromInputs('start');
                            const endDate = buildDateFromInputs('end');
                            if (startDate && endDate && validateDateFromInputs('start') && validateDateFromInputs('end')) {
                                if (!(new Date(startDate) > new Date(endDate))) {
                                    generateReportWithDateRange();
                                }
                            }
                        }
                    }
                }
            });
            
            yearInput.addEventListener('focus', function() {
                this.select();
            });
        }
    });
    
    function validateDateRange() {
        const startDate = buildDateFromInputs('start');
        const endDate = buildDateFromInputs('end');
        
        if (startDate && endDate) {
            if (!validateDateFromInputs('start')) {
                showValidationMessage('Invalid From Date. Please check the date.');
            } else if (!validateDateFromInputs('end')) {
                showValidationMessage('Invalid To Date. Please check the date.');
            } else if (new Date(startDate) > new Date(endDate)) {
                showValidationMessage('Start date cannot be after end date.');
            } else {
                hideValidationMessage();
            }
        }
    }
});
</script>

