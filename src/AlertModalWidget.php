<?php

namespace rft\alertmodalwidget;

use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;

/**
 * AlertModalWidget renders a reusable confirmation modal dialog with SweetAlert-like UI.
 */
class AlertModalWidget extends Widget
{
    public string $triggerSelector;
    public string $title = 'Confirmation';
    public string $body = 'Are you sure you want to proceed?';

    // Icon settings
    public string $iconType = 'question'; // question, warning, success, error, info
    public string $iconColor = '#87adbd'; // Default icon color

    public string $confirmButtonLabel = 'Confirm';
    public string $confirmButtonClass = 'alert-modal-btn-confirm';
    public string $cancelButtonLabel = 'Cancel';
    public string $cancelButtonClass = 'alert-modal-btn-cancel';
    public string $modalSize = 'modal-md';
    public array $modalOptions = [];

    private string $_modalId;
    private string $_jsClassId;

    // Icon mapping to Font Awesome classes
    private array $iconMapping = [
        'question' => 'fas fa-question-circle',
        'warning' => 'fas fa-exclamation-triangle',
        'success' => 'fas fa-check-circle',
        'error' => 'fas fa-times-circle',
        'info' => 'fas fa-info-circle',
    ];

    // Default icon colors for each type
    private array $defaultIconColors = [
        'question' => '#87adbd',
        'warning' => '#f8bb86',
        'success' => '#a5dc86',
        'error' => '#f27474',
        'info' => '#3fc3ee',
    ];

    public function init()
    {
        parent::init();
        $this->_modalId = 'alert-modal-' . $this->getId();
        $this->_jsClassId = 'AlertModal_' . $this->getId();

        // Set default icon color based on type if not specified
        if (!isset($this->defaultIconColors[$this->iconType])) {
            $this->iconColor = $this->defaultIconColors[$this->iconType];
        }
    }

    public function run()
    {
        $this->registerCss();
        $this->registerJs();
        return $this->renderModal();
    }

    protected function renderModal()
    {
        // Build modal HTML
        $modalHtml = '
        <div id="' . $this->_modalId . '" class="alert-modal-overlay" style="display: none;">
            <div class="alert-modal-container ' . $this->modalSize . '">
                <div class="alert-modal-content">';

        // Render icon if type is set
        if (isset($this->iconMapping[$this->iconType])) {
            $modalHtml .= Html::tag(
                'div',
                Html::tag('i', '', [
                    'class' => $this->iconMapping[$this->iconType] . ' alert-modal-icon',
                    'style' => "color: {$this->iconColor};"
                ]),
                ['class' => 'alert-modal-icon-container']
            );
        }

        $modalHtml .= '
                    <h4 class="alert-modal-title">' . Html::encode($this->title) . '</h4>
                    <div class="alert-modal-body">
                        <p class="alert-modal-body-text">' . Html::encode($this->body) . '</p>
                    </div>
                    <div class="alert-modal-footer">
                        <button type="button" class="' . $this->cancelButtonClass . '" data-dismiss="modal">' .
            Html::encode($this->cancelButtonLabel) .
            '</button>
                        <button type="button" class="' . $this->confirmButtonClass . ' alert-modal-confirm" data-href="#" data-method="">' .
            Html::encode($this->confirmButtonLabel) .
            '</button>
                    </div>
                </div>
            </div>
        </div>';

        return $modalHtml;
    }

    protected function registerCss()
    {
        // CSS remains the same as before
        $css = <<<CSS
/* Alert Modal Styles */
.alert-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: alert-modal-fadeIn 0.3s ease-out;
}

.alert-modal-container {
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    overflow: hidden;
    animation: alert-modal-slideIn 0.3s ease-out;
    max-width: 500px;
    width: 90%;
    margin: 0 20px;
}

.alert-modal-container.modal-sm {
    max-width: 400px;
}

.alert-modal-container.modal-md {
    max-width: 500px;
}

.alert-modal-container.modal-lg {
    max-width: 800px;
}

.alert-modal-container.modal-xl {
    max-width: 1140px;
}

.alert-modal-content {
    text-align: center;
    padding: 30px;
}

.alert-modal-icon-container {
    margin-bottom: 20px;
}

.alert-modal-icon {
    font-size: 60px;
    line-height: 1;
}

.alert-modal-title {
    color: #595959;
    font-size: 24px;
    font-weight: 600;
    margin: 0 0 15px 0;
    padding: 0;
}

.alert-modal-body {
    margin-bottom: 25px;
    color: #545454;
    font-size: 16px;
    line-height: 1.5;
}

.alert-modal-body-text {
    margin: 0;
    padding: 0;
}

.alert-modal-footer {
    display: flex;
    justify-content: center;
    gap: 10px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.alert-modal-btn-cancel,
.alert-modal-btn-confirm {
    padding: 10px 24px;
    font-size: 15px;
    font-weight: 500;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: all 0.2s ease;
    min-width: 100px;
    display: inline-block;
    text-align: center;
    font-family: inherit;
    box-sizing: border-box;
    line-height: 1.5;
    margin: 0;
    outline: none;
}

.alert-modal-btn-cancel {
    background-color: #f0f0f0;
    color: #555;
}

.alert-modal-btn-cancel:hover {
    background-color: #e0e0e0;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.alert-modal-btn-confirm {
    background-color: #dc3545;
    color: white;
}

.alert-modal-btn-confirm:hover {
    background-color: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.alert-modal-btn-confirm:active,
.alert-modal-btn-cancel:active {
    transform: translateY(0);
}

.alert-modal-btn-confirm:focus,
.alert-modal-btn-cancel:focus {
    box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5);
}

/* Custom button classes */
.alert-modal-btn-confirm.btn-success {
    background-color: #28a745;
}

.alert-modal-btn-confirm.btn-success:hover {
    background-color: #218838;
}

.alert-modal-btn-confirm.btn-primary {
    background-color: #007bff;
}

.alert-modal-btn-confirm.btn-primary:hover {
    background-color: #0069d9;
}

.alert-modal-btn-confirm.btn-warning {
    background-color: #ffc107;
    color: #212529;
}

.alert-modal-btn-confirm.btn-warning:hover {
    background-color: #e0a800;
}

.alert-modal-btn-confirm.btn-info {
    background-color: #17a2b8;
}

.alert-modal-btn-confirm.btn-info:hover {
    background-color: #138496;
}

.alert-modal-btn-confirm.btn-danger {
    background-color: #dc3545;
}

.alert-modal-btn-confirm.btn-danger:hover {
    background-color: #c82333;
}

/* Animations */
@keyframes alert-modal-fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes alert-modal-slideIn {
    from {
        opacity: 0;
        transform: translateY(-30px) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Responsive */
@media (max-width: 576px) {
    .alert-modal-container {
        margin: 10px;
        width: calc(100% - 20px);
    }
    
    .alert-modal-content {
        padding: 20px;
    }
    
    .alert-modal-footer {
        flex-direction: column;
    }
    
    .alert-modal-btn-cancel,
    .alert-modal-btn-confirm {
        width: 100%;
        margin: 5px 0;
    }
    
    .alert-modal-icon {
        font-size: 50px;
    }
    
    .alert-modal-title {
        font-size: 20px;
    }
}
CSS;

        $this->getView()->registerCss($css, [], 'alert-modal-styles-' . $this->getId());
    }

    protected function registerJs()
    {
        $defaultTitle = addslashes($this->title);
        $defaultBody = addslashes($this->body);
        $defaultConfirmLabel = addslashes($this->confirmButtonLabel);
        $defaultIconType = addslashes($this->iconType);
        $defaultIconColor = addslashes($this->iconColor);
        $widgetId = $this->getId();
        $modalId = $this->_modalId;
        $triggerSelector = $this->triggerSelector;

        // Icon mapping for JavaScript
        $iconMappingJson = json_encode($this->iconMapping);

        $js = <<<JS
// Create a unique class name for this widget instance
class AlertModal_{$widgetId} {
    constructor(modalId) {
        this.modal = document.getElementById(modalId);
        this.overlay = this.modal;
        this.confirmButton = this.modal.querySelector('.alert-modal-confirm');
        this.cancelButton = this.modal.querySelector('.alert-modal-btn-cancel');
        this.init();
    }

    init() {
        // Close modal when clicking overlay
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.hide();
            }
        });

        // Close modal when clicking cancel button
        this.cancelButton.addEventListener('click', () => {
            this.hide();
        });

        // Close modal with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isVisible()) {
                this.hide();
            }
        });

        // Handle confirm button click
        this.confirmButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            const href = this.confirmButton.getAttribute('data-href');
            const method = this.confirmButton.getAttribute('data-method');
            
            console.log('Confirm button clicked');
            console.log('href:', href);
            console.log('method:', method);
            
            if (!href || href === '#' || href === 'javascript:void(0)') {
                console.log('No valid href');
                this.hide();
                return;
            }

            if (method && method.trim().toLowerCase() === 'post') {
                console.log('Submitting POST request to:', href);
                this.submitPostForm(href);
            } else {
                console.log('Navigating via GET to:', href);
                window.location.href = href;
            }
        });
    }

    show() {
        this.modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    hide() {
        this.modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    isVisible() {
        return this.modal.style.display === 'flex';
    }

    submitPostForm(href) {
        console.log('Creating POST form for:', href);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = href;
        form.style.display = 'none';
        document.body.appendChild(form);

        // Add CSRF token if available (Yii2 standard)
        // Try multiple ways to find CSRF token
        let csrfToken = null;
        let csrfParam = '_csrf';
        
        // Method 1: Check meta tags
        const csrfMetaParam = document.querySelector('meta[name="csrf-param"]');
        const csrfMetaToken = document.querySelector('meta[name="csrf-token"]');
        
        if (csrfMetaParam && csrfMetaToken) {
            csrfParam = csrfMetaParam.getAttribute('content');
            csrfToken = csrfMetaToken.getAttribute('content');
        }
        
        // Method 2: Check for hidden input
        if (!csrfToken) {
            const csrfInput = document.querySelector('input[name="_csrf"]');
            if (csrfInput) {
                csrfToken = csrfInput.value;
            }
        }
        
        // Method 3: Check for meta tag with csrf-token name
        if (!csrfToken) {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (csrfMeta) {
                csrfToken = csrfMeta.getAttribute('content');
            }
        }
        
        console.log('CSRF Param:', csrfParam);
        console.log('CSRF Token:', csrfToken);
        
        if (csrfToken) {
            console.log('Adding CSRF token to form');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = csrfParam;
            input.value = csrfToken;
            form.appendChild(input);
        } else {
            console.warn('No CSRF token found! POST request may fail.');
        }

        // Add additional parameters from data-params attribute
        const paramsAttr = this.confirmButton.getAttribute('data-params');
        if (paramsAttr) {
            try {
                const params = JSON.parse(paramsAttr);
                console.log('Additional params:', params);
                Object.keys(params).forEach(key => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = params[key];
                    form.appendChild(input);
                });
            } catch (e) {
                console.error('Failed to parse data-params:', e);
            }
        }

        console.log('Form HTML:', form.outerHTML);
        console.log('Submitting form...');
        
        // Hide modal before submitting
        this.hide();
        
        // Submit the form
        form.submit();
    }

    updateContent(data) {
        if (data.title !== undefined) {
            const titleEl = this.modal.querySelector('.alert-modal-title');
            if (titleEl) titleEl.textContent = data.title;
        }

        if (data.body !== undefined) {
            const bodyEl = this.modal.querySelector('.alert-modal-body-text');
            if (bodyEl) bodyEl.textContent = data.body;
        }

        if (data.confirmLabel !== undefined) {
            this.confirmButton.textContent = data.confirmLabel;
        }

        if (data.confirmClass !== undefined) {
            this.confirmButton.className = 'alert-modal-btn-confirm alert-modal-confirm ' + data.confirmClass;
        } else {
            this.confirmButton.className = 'alert-modal-btn-confirm alert-modal-confirm {$this->confirmButtonClass}';
        }

        if (data.icon !== undefined || data.iconColor !== undefined) {
            const iconContainer = this.modal.querySelector('.alert-modal-icon-container');
            const icon = iconContainer ? iconContainer.querySelector('.alert-modal-icon') : null;
            if (icon) {
                const iconMapping = {$iconMappingJson};
                
                if (data.icon !== undefined) {
                    const newIconClass = iconMapping[data.icon] || iconMapping['question'];
                    icon.className = newIconClass + ' alert-modal-icon';
                }
                
                if (data.iconColor !== undefined) {
                    icon.style.color = data.iconColor;
                }
            }
        }

        if (data.href !== undefined) {
            this.confirmButton.setAttribute('data-href', data.href);
        } else {
            this.confirmButton.setAttribute('data-href', '#');
        }

        if (data.method !== undefined && data.method.trim() !== '') {
            this.confirmButton.setAttribute('data-method', data.method);
        } else {
            this.confirmButton.setAttribute('data-method', '');
        }

        if (data.params !== undefined) {
            this.confirmButton.setAttribute('data-params', JSON.stringify(data.params));
        } else {
            this.confirmButton.removeAttribute('data-params');
        }
    }
}

// Initialize modal instance
const alertModal_{$widgetId} = new AlertModal_{$widgetId}('{$modalId}');

// Handle trigger clicks
document.addEventListener('click', function(e) {
    // Check if clicked element or its parent has the trigger selector
    let trigger = e.target;
    while (trigger && trigger !== document) {
        if (trigger.matches && trigger.matches('{$triggerSelector}')) {
            // Found a trigger element
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Modal trigger clicked:', trigger);
            
            // Get href from the trigger
            let href = trigger.getAttribute('href');
            if (!href || href === '#') {
                href = trigger.getAttribute('data-href');
            }
            
            console.log('Original href:', href);
            
            if (!href || href === '#' || href === 'javascript:void(0)') {
                console.warn('No valid href found for modal trigger');
                return;
            }
            
            // Decode URL if it's encoded
            try {
                href = decodeURIComponent(href);
            } catch (e) {
                // If decoding fails, use original
                console.log('URL decode failed, using original');
            }
            
            console.log('Decoded href:', href);
            
            // Get data from trigger
            const getData = (attr) => trigger.getAttribute('data-' + attr);
            
            const data = {
                title: getData('alert-modal-title') || '{$defaultTitle}',
                body: getData('alert-modal-body') || getData('confirm') || '{$defaultBody}',
                confirmLabel: getData('alert-modal-confirm-label') || getData('confirm-label') || '{$defaultConfirmLabel}',
                confirmClass: getData('alert-modal-confirm-class') || '{$this->confirmButtonClass}',
                icon: getData('alert-modal-icon') || '{$defaultIconType}',
                iconColor: getData('alert-modal-icon-color') || '{$defaultIconColor}',
                href: href,
                method: getData('method'),
                params: JSON.parse(getData('params') || '{}')
            };
            
            console.log('Modal data:', data);
            
            // Legacy support
            if (!data.title && getData('title')) {
                data.title = getData('title');
            }
            
            // Update modal content
            alertModal_{$widgetId}.updateContent(data);
            
            // Show modal
            alertModal_{$widgetId}.show();
            
            return; // Stop processing
        }
        trigger = trigger.parentNode;
    }
}, true); // Use capture phase to catch events early
JS;

        $this->getView()->registerJs($js, View::POS_READY, 'alert-modal-script-' . $this->getId());
    }
}