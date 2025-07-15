/**
 * Confirmation Modal Component
 * 
 * A reusable confirmation modal component that can be used for all types
 * of confirmations like delete, cancel, etc.
 * 
 * Author: GitHub Copilot
 */

// Initialize confirmation modal when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check if modal element exists, if not, create it
    if (!document.getElementById('confirmationModal')) {
        createConfirmationModal();
    }
});

/**
 * Create the confirmation modal HTML structure and append it to the body
 */
function createConfirmationModal() {
    const modal = document.createElement('div');
    modal.id = 'confirmationModal';
    modal.className = 'hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none';
    modal.innerHTML = `
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="flex flex-col bg-white border shadow-sm rounded-md pointer-events-auto dark:bg-gray-800 dark:border-gray-700 dark:shadow-slate-700/[.7]">
                <div id="confirmHeader" class="flex justify-between items-center py-3 px-4 border-b dark:border-gray-700">
                    <h3 id="confirmTitle" class="font-bold text-base text-gray-800 dark:text-white">
                        Confirmation
                    </h3>
                    <button type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none data-hs-overlay="#confirmationModal">
                        <span class="sr-only">Close</span>
                        <i class="ti ti-x text-xl"></i>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <p id="confirmMessage" class="mt-1 text-base text-gray-800 dark:text-gray-400">
                        Are you sure you want to proceed with this action?
                    </p>
                </div>
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-gray-700">
                    <button type="button" class="btn-md text-sm font-medium rounded-md border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-white dark:hover:bg-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" data-hs-overlay="#confirmationModal">
                        Batal
                    </button>
                    <button id="confirmActionBtn" type="button" class="btn-md text-sm font-semibold rounded-md border border-transparent bg-primary text-white hover:bg-primaryemphasis disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

/**
 * Open the confirmation modal with custom settings
 * 
 * @param {string} type - The type of confirmation ('delete', 'warning', 'info', 'success', etc.)
 * @param {string} title - The title to display in the modal header
 * @param {string} message - The message to display in the modal body
 * @param {string} confirmBtnText - The text to display on the confirm button
 * @param {Function|string} confirmAction - The action to perform when the confirm button is clicked
 * @param {Object} options - Additional options for the modal
 */
window.openConfirmationModal = function(type, title, message, confirmBtnText, confirmAction, options = {}) {
    // Get modal elements
    const modal = document.getElementById('confirmationModal');
    const header = document.getElementById('confirmHeader');
    const titleEl = document.getElementById('confirmTitle');
    const messageEl = document.getElementById('confirmMessage');
    const confirmBtn = document.getElementById('confirmActionBtn');
    
    // Set content
    titleEl.textContent = title || 'Confirmation';
    messageEl.textContent = message || 'Are you sure you want to proceed?';
    confirmBtn.textContent = confirmBtnText || 'Confirm';
    
    // Set styles based on type
    header.className = 'flex justify-between items-center py-3 px-4 border-b dark:border-gray-700';
    confirmBtn.className = 'btn-md text-sm font-semibold rounded-md border border-transparent text-white';
    
    switch(type) {
        case 'delete':
        case 'error':
            header.classList.add('bg-light', 'dark:bg-dark');
            confirmBtn.classList.add('bg-error', 'hover:bg-erroremphasis');
            break;
        case 'warning':
            header.classList.add('bg-lightwarning', 'dark:bg-darkwarning');
            confirmBtn.classList.add('bg-warning', 'hover:bg-warningemphasis');
            break;
        case 'info':
            header.classList.add('bg-lightinfo', 'dark:bg-darkinfo');
            confirmBtn.classList.add('bg-info', 'hover:bg-infoemphasis');
            break;
        case 'success':
            header.classList.add('bg-lightsuccess', 'dark:bg-darksuccess');
            confirmBtn.classList.add('bg-success', 'hover:bg-successemphasis');
            break;
        default:
            header.classList.add('bg-lightprimary', 'dark:bg-darkprimary');
            confirmBtn.classList.add('bg-primary', 'hover:bg-primaryemphasis');
    }
    
    // Set action for the confirm button
    if (typeof confirmAction === 'function') {
        confirmBtn.onclick = confirmAction;
    } else if (typeof confirmAction === 'string') {
        // If the action is a string (JavaScript code), create a function from it
        confirmBtn.setAttribute('onclick', confirmAction);
    }
    
    // Open modal
    if (typeof HSOverlay !== 'undefined') {
        HSOverlay.open(modal);
    } else {
        console.error('HSOverlay is not defined. Make sure you have loaded the required libraries.');
    }
};

/**
 * Show a toast notification
 * 
 * @param {string} type - The type of toast ('success', 'error', 'warning', 'info')
 * @param {string} message - The message to display in the toast
 * @param {Object} options - Additional options for the toast
 */
window.showToast = function(type, message, options = {}) {
    // Create toast element
    const toast = document.createElement('div');
    toast.id = 'confirmation-toast-' + Date.now();
    toast.className = 'fixed bottom-4 right-4 z-[100] p-4 rounded-md shadow-lg flex items-center gap-2 opacity-0 transition-opacity duration-300';
    
    // Set toast style based on type
    let icon = '';
    switch(type) {
        case 'success':
            toast.classList.add('bg-lightsuccess', 'text-success', 'dark:bg-darksuccess');
            icon = '<i class="ti ti-circle-check text-xl"></i>';
            break;
        case 'error':
            toast.classList.add('bg-lighterror', 'text-error', 'dark:bg-darkerror');
            icon = '<i class="ti ti-alert-circle text-xl"></i>';
            break;
        case 'warning':
            toast.classList.add('bg-lightwarning', 'text-warning', 'dark:bg-darkwarning');
            icon = '<i class="ti ti-alert-triangle text-xl"></i>';
            break;
        case 'info':
        default:
            toast.classList.add('bg-lightinfo', 'text-info', 'dark:bg-darkinfo');
            icon = '<i class="ti ti-info-circle text-xl"></i>';
    }
    
    // Set toast content
    toast.innerHTML = `
        ${icon}
        <div class="flex-1">${message}</div>
        <button type="button" class="ml-2" onclick="this.parentElement.remove()">
            <i class="ti ti-x"></i>
        </button>
    `;
    
    // Add to DOM
    document.body.appendChild(toast);
    
    // Show toast
    setTimeout(() => {
        toast.classList.add('opacity-100');
    }, 100);
    
    // Auto-remove after specified duration
    const duration = options.duration || 3000;
    setTimeout(() => {
        toast.classList.remove('opacity-100');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, duration);
};

/**
 * Replace all window.confirm with custom modal
 * This will override the default browser confirmation dialog
 */
(function() {
    // Save the original confirm method
    const originalConfirm = window.confirm;
    
    // Override the confirm method
    window.confirm = function(message) {
        return new Promise((resolve) => {
            openConfirmationModal(
                'warning',
                'Confirmation',
                message,
                'Confirm',
                () => {
                    HSOverlay.close(document.getElementById('confirmationModal'));
                    resolve(true);
                }
            );
            
            // Add event listener for cancel button
            const cancelBtn = document.querySelector('#confirmationModal button[data-hs-overlay="#confirmationModal"]');
            cancelBtn.onclick = function() {
                HSOverlay.close(document.getElementById('confirmationModal'));
                resolve(false);
            };
        });
    };
})();