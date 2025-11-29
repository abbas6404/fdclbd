# 🔔 Global Notification System Guide

## 📋 Overview
This document explains how to use the global notification system that's already set up in `resources/views/admin/layouts/app.blade.php`. The system supports Livewire components and traditional controllers.

## 🚀 Quick Start

### For Livewire Components:

#### 1. Add Helper Method to Your Livewire Component:
```php
<?php

namespace App\Livewire;

use Livewire\Component;

class YourComponent extends Component
{
    // Add this helper method to your Livewire component
    private function showNotification($type, $message)
    {
        // Convert \n to <br> tags for proper HTML line breaks
        $formattedMessage = str_replace('\n', '<br>', $message);
        
        $this->dispatch('show-alert', [
            'type' => $type,
            'message' => $formattedMessage
        ]);
    }

    // Your component methods...
    public function saveData()
    {
        // Your logic here...
        
        // Show success notification
        $this->showNotification('success', '✅ Data saved successfully!<br><br>Project ID: PRJ-123<br>Name: Green Valley');
    }

    public function handleError()
    {
        // Your error logic...
        
        // Show error notification
        $this->showNotification('error', '❌ Error occurred!<br><br>Please try again or contact support.');
    }
}
```

#### 2. Usage Examples:
```php
// Success notification
        $this->showNotification('success', '✅ Flat Sale Successful!<br><br>Sale ID: FS-2025-123<br>Customer Name: John Doe<br>Invoice No: INV-2025-456<br>Amount: $150,000.00');

// Error notification
        $this->showNotification('error', '❌ Database Error!<br><br>Error: Connection failed<br>Project ID: PRJ-2025-123<br><br>Please try again.');

// Warning notification
        $this->showNotification('warning', '⚠️ Payment Pending!<br><br>Amount: $150,000.00<br>Customer: John Doe<br><br>Please complete payment.');

// Info notification
        $this->showNotification('info', 'ℹ️ Payment Schedule Updated!<br><br>Customer: John Doe<br>Project: Green Valley<br><br>Payment schedule is ready for review.');
```

### For Traditional Controllers:

#### 1. Use Session Flash Messages:
```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class YourController extends Controller
{
    public function store(Request $request)
    {
        // Your logic here...
        
        // Success message
        return redirect()->route('admin.projects.index')
            ->with('success', '✅ Project saved successfully!<br><br>Project ID: PRJ-123<br>Name: Green Valley');
            
        // Error message
        return back()->withInput()
            ->with('error', '❌ Error occurred!<br><br>Please try again.');
    }
}
```

## 🎯 Notification Types

### Available Types:
- `success` - Green checkmark, auto-closes after 5 seconds
- `error` - Red X, requires OK button
- `warning` - Yellow warning, requires OK button  
- `info` - Blue info, requires OK button

### Message Format:
```php
$message = "✅ Title<br><br>Line 1<br>Line 2<br>Line 3<br><br>Footer message";
```

## 📝 Message Structure Examples

### Flat Sale Success:
```php
$message = "✅ Flat Sale Successful!<br><br>Sale ID: {$saleId}<br>Customer Name: {$customerName}<br>Invoice No: {$invoiceNo}<br>Project: {$projectName}<br>Amount: \${$amount}<br><br>Flat sale has been completed successfully!";
```

### Form Validation Error:
```php
$message = "❌ Form Validation Failed!<br><br>Project ID: {$projectId}<br>Invoice No: {$invoiceNo}<br><br>Errors:<br>• Project name is required<br>• Email is invalid<br>• Phone number is missing<br><br>Please fill all required fields.";
```

### Payment Warning:
```php
$message = "⚠️ Payment Pending!<br><br>Sale ID: {$saleId}<br>Customer Name: {$customerName}<br>Amount: \${$amount}<br><br>Please complete the payment process.";
```

### Payment Schedule Info:
```php
$message = "ℹ️ Payment Schedule Updated!<br><br>Sale ID: {$saleId}<br>Customer Name: {$customerName}<br>Project: {$projectName}<br><br>Payment schedule is ready for review.";
```

## 🔧 Advanced Usage

### Multiple Notifications (Sequential):
```php
public function processMultipleProjects()
{
    $projects = [
        ['id' => 'PRJ-001', 'name' => 'Green Valley', 'status' => 'success'],
        ['id' => 'PRJ-002', 'name' => 'Blue Horizon', 'status' => 'error'],
        ['id' => 'PRJ-003', 'name' => 'Sunset View', 'status' => 'warning']
    ];

    // Show first notification immediately
    $this->showNotification($projects[0]['status'], 
        "Project {$projects[0]['name']} processed!<br><br>Project ID: {$projects[0]['id']}");

    // Send remaining to JavaScript for sequential display
    $this->dispatch('process-remaining-projects', [
        'projects' => array_slice($projects, 1)
    ]);
}
```

### JavaScript Handler for Sequential Notifications:
```javascript
// Add this to your Livewire component view
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('process-remaining-projects', (data) => {
        const projects = data.projects;
        let currentIndex = 0;
        
        function showNextNotification() {
            if (currentIndex < projects.length) {
                const project = projects[currentIndex];
                Swal.fire({
                    icon: project.status,
                    html: `Project ${project.name} processed!<br><br>Project ID: ${project.id}`,
                    position: 'center',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
                currentIndex++;
                setTimeout(showNextNotification, 3000);
            }
        }
        setTimeout(showNextNotification, 3000);
    });
});
</script>
```

## 🎨 Customization

### Custom Styling:
The notifications use SweetAlert2 with custom CSS classes:
- `swal2-icon-large` - Larger icons
- `swal2-popup-with-icon` - Custom popup styling

### Timer Settings:
- **Success notifications**: Auto-close after 5 seconds
- **Other notifications**: Require manual OK button

## 🚨 Troubleshooting

### Common Issues:

1. **Notifications not showing**: Check browser console for JavaScript errors
2. **Line breaks not working**: Make sure you're using `<br>` tags in your message string
3. **Global system not working**: Verify the script is loaded in `app.blade.php`

### Debug Mode:
```php
// Add this to your Livewire component for debugging
public function debugNotification()
{
    $this->showNotification('success', '✅ Debug notification!<br><br>This is a debug message.<br><br>If you see this, the system is working!');
}
```

## 📋 Copy-Paste Templates

### Livewire Component Template:
```php
<?php

namespace App\Livewire;

use Livewire\Component;

class YourComponent extends Component
{
    public function render()
    {
        return view('livewire.your-component');
    }

    // Add this helper method
    private function showNotification($type, $message)
    {
        $formattedMessage = str_replace('\n', '<br>', $message);
        
        $this->dispatch('show-alert', [
            'type' => $type,
            'message' => $formattedMessage
        ]);
    }

    // Your methods here...
    public function save()
    {
        // Your logic...
        $this->showNotification('success', '✅ Saved successfully!');
    }
}
```

### Controller Template:
```php
public function store(Request $request)
{
    try {
        // Your logic here...
        
        return redirect()->route('index')
            ->with('success', '✅ Data saved successfully!<br><br>Details: Success message');
            
    } catch (\Exception $e) {
        return back()->withInput()
            ->with('error', '❌ Error occurred!<br><br>Error: ' . $e->getMessage());
    }
}
```

## ✅ Summary

The global notification system is already set up and working. To use it in other places:

1. **For Livewire**: Copy the `showNotification()` helper method
2. **For Controllers**: Use `->with('type', 'message')` redirects
3. **Message Format**: Use `<br>` tags for line breaks in your message string
4. **Types Available**: `success`, `error`, `warning`, `info`

The system automatically handles line breaks, styling, and display logic! 🚀 