# Yii2 Alert Modal Widget

A standalone, reusable confirmation modal widget for the Yii2 Framework with SweetAlert-like UI/UX. This widget is completely independent of Bootstrap and includes its own CSS and JavaScript.

## Features

- **No Bootstrap Dependency**: Uses custom CSS and JavaScript - no Bootstrap required
- **SweetAlert2-like UI**: Clean, modern interface with centered layout and icons
- **Standalone Implementation**: All styles and scripts are self-contained within the widget
- **Icon Support**: Five icon types (question, warning, success, error, info) with customizable colors
- **Dynamic Content**: Modal content can be customized per trigger via data attributes
- **POST Method Support**: Handles POST requests with CSRF token integration
- **Responsive Design**: Mobile-friendly layout with proper breakpoints
- **Multiple Instances**: Support for different modal types with separate configurations

## Installation

This is an internal widget for your Yii2 application. To use it:

1. Copy the `AlertModalWidget.php` file to your `frontend/components/widgets/` directory
2. Ensure Font Awesome is included in your project (for icons)

**No Bootstrap required!** The widget includes all necessary CSS and JavaScript.

## Basic Usage

### Step 1: Define Your Modals

Place modal widgets in your view or layout file. Note the button class format:

```php
<?= \frontend\components\widgets\AlertModalWidget::widget([
    'triggerSelector' => '.modal-delete-trigger',
    'title' => 'Delete Confirmation',
    'body' => 'This item will be deleted permanently.',
    'iconType' => 'warning',
    'iconColor' => '#f8bb86',
    'confirmButtonLabel' => 'Delete',
    'confirmButtonClass' => 'alert-modal-btn-confirm btn-danger', // Base class + color class
]) ?>

<?= \frontend\components\widgets\AlertModalWidget::widget([
    'triggerSelector' => '.modal-view-trigger',
    'title' => 'View Details',
    'body' => 'You are about to view the details.',
    'iconType' => 'info',
    'iconColor' => '#3498db',
    'confirmButtonLabel' => 'View',
    'confirmButtonClass' => 'alert-modal-btn-confirm btn-success', // Base class + color class
]) ?>
```

### Step 2: Create Trigger Buttons

Create buttons/links with the corresponding CSS class. Use standard Bootstrap classes for your buttons, but add the modal trigger class:

```php
<?= Html::a('<i class="fa fa-eye"></i> View', ['/item/view', 'id' => 1], [
    'class' => 'btn btn-info modal-view-trigger', // Your button styles + trigger class
    'data' => [
        'alert-modal-title' => 'View Item Details',
        'alert-modal-body' => 'Are you sure you want to view this item?',
        'alert-modal-confirm-label' => 'Yes, View Details',
    ],
]) ?>

<?= Html::a('<i class="fa fa-trash"></i> Delete', ['/item/delete', 'id' => 1], [
    'class' => 'btn btn-danger modal-delete-trigger', // Your button styles + trigger class
    'data' => [
        'method' => 'post',
        'alert-modal-body' => 'This record will be permanently deleted.',
        'alert-modal-confirm-label' => 'Yes, Delete Permanently',
    ],
]) ?>
```

## Complete Working Example

Here's exactly how you should implement it:

```php
<!-- 1. Define your modals -->
<?= \frontend\components\widgets\AlertModalWidget::widget([
    'triggerSelector' => '.modal-delete-trigger',
    'title' => 'Delete Confirmation',
    'confirmButtonLabel' => 'Delete',
    'confirmButtonClass' => 'alert-modal-btn-confirm btn-danger', // REQUIRED FORMAT
    'iconType' => 'warning',
    'iconColor' => '#f8bb86',
]) ?>

<?= \frontend\components\widgets\AlertModalWidget::widget([
    'triggerSelector' => '.modal-view-trigger',
    'title' => 'View Details',
    'confirmButtonLabel' => 'View',
    'confirmButtonClass' => 'alert-modal-btn-confirm btn-success', // REQUIRED FORMAT
    'iconType' => 'info',
    'iconColor' => '#3498db',
]) ?>

<!-- 2. Create your action buttons -->
<div class="action-buttons">
    <?= Html::a('<i class="fa fa-eye"></i> View', ['/item/view', 'id' => 1], [
        'class' => 'btn btn-info modal-view-trigger',
        'data' => [
            'alert-modal-title' => 'View Item #1',
            'alert-modal-body' => 'You are about to view item details.',
            'alert-modal-confirm-label' => 'Open Item',
            'alert-modal-icon' => 'info',
        ],
    ]) ?>

    <?= Html::a('<i class="fa fa-trash"></i> Delete', ['/item/delete', 'id' => 1], [
        'class' => 'btn btn-danger modal-delete-trigger',
        'data' => [
            'method' => 'post',
            'alert-modal-body' => 'Item #1 will be permanently deleted.',
            'alert-modal-confirm-label' => 'Confirm Delete',
            'alert-modal-icon' => 'error',
        ],
    ]) ?>
</div>
```

## Your Specific Use Case Example

Based on your code, here's exactly how to implement it:

```php
<!-- Modal Widgets -->
<?= \frontend\components\widgets\AlertModalWidget::widget([
    'triggerSelector' => '.modal-delete-trigger',
    'title' => 'Delete Confirmation',
    'confirmButtonLabel' => 'Delete',
    'confirmButtonClass' => 'alert-modal-btn-confirm btn-danger',
    'iconType' => 'warning',
    'iconColor' => '#f8bb86',
]) ?>

<?= \frontend\components\widgets\AlertModalWidget::widget([
    'triggerSelector' => '.modal-view-trigger',
    'title' => 'View Abstract of Bid',
    'confirmButtonLabel' => 'View',
    'confirmButtonClass' => 'alert-modal-btn-confirm btn-success',
    'iconType' => 'info',
    'iconColor' => '#3498db',
]) ?>

<!-- Action Buttons -->
<td class="text-center">
    <?= Html::a('<i class="fa fa-eye"></i> View', ['/bid-invitation-lot/view', 'id' => $model->id], [
        'class' => 'btn bg-gradient-warning btn-xs view-lot modal-view-trigger',
        'data' => [
            'alert-modal-title' => 'View Abstract of Bid',
            'alert-modal-body' => 'This is a sample message for the view modal.',
            'alert-modal-confirm-label' => 'View Details',
            'alert-modal-confirm-class' => 'btn-success',
        ],
    ]) ?>

    <?php if (!$model->bidInvitation->hasSupplierBids()): ?>
        <?= Html::a('<i class="fa fa-pen"></i> Update', ['/bid-invitation-lot/update', 'id' => $model->id], [
            'class' => 'btn bg-gradient-primary btn-xs update-lot ' . $displayNone,
        ]) ?>

        <?= Html::a('<i class="fa fa-trash"></i> Delete', ['/bid-invitation-lot/delete', 'id' => $model->id], [
            'class' => 'btn bg-gradient-danger btn-xs modal-delete-trigger ' . $displayNone,
            'data' => [
                'method' => 'post',
                'alert-modal-body' => 'This record will be deleted. Do you want to continue?',
                'alert-modal-confirm-label' => 'Yes, Delete',
            ],
        ]) ?>
    <?php endif; ?>
</td>
```

## Configuration Options

### Widget Properties

| Property | Type | Default | Description |
| --- | --- | --- | --- |
| `triggerSelector` | string | **required** | CSS selector for trigger elements |
| `title` | string | 'Confirmation' | Default modal title |
| `body` | string | 'Are you sure you want to proceed?' | Default modal body text |
| `iconType` | string | 'question' | Icon type: 'question', 'warning', 'success', 'error', 'info' |
| `iconColor` | string | '#87adbd' | Custom icon color (hex) |
| `confirmButtonLabel` | string | 'Confirm' | Confirmation button label |
| `confirmButtonClass` | string | 'alert-modal-btn-confirm' | **Must start with 'alert-modal-btn-confirm'** |
| `cancelButtonLabel` | string | 'Cancel' | Cancel button label |
| `cancelButtonClass` | string | 'alert-modal-btn-cancel' | CSS classes for cancel button |
| `modalSize` | string | 'modal-md' | Modal size: 'modal-sm', 'modal-md', 'modal-lg', 'modal-xl' |
| `modalOptions` | array | [] | Additional modal options |

### Button Classes Format

**Important**: The `confirmButtonClass` must follow this format:

```php
'confirmButtonClass' => 'alert-modal-btn-confirm btn-danger' // Base class + color class
```

Available color classes (add after the base class):

- `btn-danger` (Red) - For delete/destructive actions
- `btn-success` (Green) - For success/positive actions
- `btn-primary` (Blue) - For primary actions
- `btn-warning` (Yellow) - For warning actions
- `btn-info` (Teal) - For informational actions

### Data Attributes for Dynamic Overrides

Use these attributes on your trigger buttons/links:

| Attribute | Example | Description |
| --- | --- | --- |
| `data-alert-modal-title` | `data-alert-modal-title="Custom Title"` | Override modal title |
| `data-alert-modal-body` | `data-alert-modal-body="Custom message"` | Override modal body text |
| `data-alert-modal-confirm-label` | `data-alert-modal-confirm-label="Yes, Proceed"` | Override confirm button label |
| `data-alert-modal-confirm-class` | `data-alert-modal-confirm-class="btn-warning"` | Add CSS classes to confirm button |
| `data-alert-modal-icon` | `data-alert-modal-icon="warning"` | Override icon type |
| `data-alert-modal-icon-color` | `data-alert-modal-icon-color="#ff0000"` | Override icon color |
| `data-method` | `data-method="post"` | HTTP method for the action |
| `data-params` | `data-params='{"key":"value"}'` | Additional POST parameters |
| `data-title` (legacy) | `data-title="Legacy Title"` | Legacy title override (backward compatible) |
| `data-confirm` (legacy) | `data-confirm="Legacy message"` | Legacy body override (backward compatible) |
| `data-confirm-label` (legacy) | `data-confirm-label="Legacy Label"` | Legacy label override (backward compatible) |

## Important Notes for Your Implementation

1. **Button Class Format is CRITICAL**:

   ```php
   // ✅ Correct:
   'confirmButtonClass' => 'alert-modal-btn-confirm btn-danger'

   // ❌ Wrong (will break styling):
   'confirmButtonClass' => 'btn btn-danger'
   ```

2. **Trigger Buttons Use Regular Bootstrap Classes**:

   ```php
   // Your trigger buttons use normal Bootstrap classes:
   'class' => 'btn bg-gradient-danger btn-xs modal-delete-trigger'
   // The modal trigger class is just added to the end
   ```

3. **Font Awesome Required**: The widget uses Font Awesome for icons:

   ```html
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
   ```

4. **CSRF Protection**: For POST requests, ensure CSRF meta tags are in your layout:

   ```php
   <?= Html::csrfMetaTags() ?>
   ```

5. **Multiple Widgets Work Fine**: The latest version has fixed the JavaScript class conflict issue.

## Troubleshooting Common Issues

### Modal Doesn't Show

- Check browser console for JavaScript errors
- Ensure `confirmButtonClass` starts with `alert-modal-btn-confirm`
- Verify trigger selector matches your button class

### Styling Issues

- Make sure you're not loading conflicting CSS
- Check that button classes follow the correct format
- Verify Font Awesome is loaded for icons

### POST Requests Not Working

- Ensure CSRF meta tags are present
- Verify `data-method="post"` is set on trigger links

The README is now updated to accurately reflect your current standalone widget implementation.
