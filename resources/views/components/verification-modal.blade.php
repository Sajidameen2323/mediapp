<!-- Verification Modal Component -->
<div x-data="{ 
    showModal: false, 
    modalData: {},
    openModal(data) {
        this.modalData = data;
        this.showModal = true;
    },
    closeModal() {
        this.showModal = false;
        this.modalData = {};
    },
    confirmAction() {
        if (this.modalData.form) {
            this.modalData.form.submit();
        } else if (this.modalData.url) {
            window.location.href = this.modalData.url;
        }
        this.closeModal();
    }
}" 
x-on:open-verification-modal.window="openModal($event.detail)"
class="relative z-50">
    <!-- Modal Backdrop -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4"
         x-cloak>
        
        <!-- Modal Content -->
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.away="closeModal()"
             class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
            
            <!-- Modal Header -->
            <div class="sm:flex sm:items-start">
                <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                     :class="modalData.type === 'danger' ? 'bg-red-100 dark:bg-red-900' : 
                             modalData.type === 'warning' ? 'bg-yellow-100 dark:bg-yellow-900' : 
                             'bg-blue-100 dark:bg-blue-900'">
                    <i :class="modalData.type === 'danger' ? 'fas fa-exclamation-triangle text-red-600 dark:text-red-400' : 
                               modalData.type === 'warning' ? 'fas fa-exclamation-circle text-yellow-600 dark:text-yellow-400' : 
                               'fas fa-info-circle text-blue-600 dark:text-blue-400'"
                       class="text-lg"></i>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                    <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white" 
                        x-text="modalData.title || 'Confirm Action'"></h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400" 
                           x-text="modalData.message || 'Are you sure you want to proceed?'"></p>
                    </div>
                </div>
            </div>
            
            <!-- Additional Information -->
            <div x-show="modalData.details" class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                <div class="text-sm text-gray-600 dark:text-gray-300" x-html="modalData.details"></div>
            </div>
            
            <!-- Modal Actions -->
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        @click="confirmAction()"
                        :class="modalData.type === 'danger' ? 'bg-red-600 hover:bg-red-500 focus:ring-red-500' : 
                                modalData.type === 'warning' ? 'bg-yellow-600 hover:bg-yellow-500 focus:ring-yellow-500' : 
                                'bg-blue-600 hover:bg-blue-500 focus:ring-blue-500'"
                        class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto transition-colors">
                    <span x-text="modalData.confirmText || 'Confirm'"></span>
                </button>
                <button type="button" 
                        @click="closeModal()"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-600 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 sm:mt-0 sm:w-auto transition-colors">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
