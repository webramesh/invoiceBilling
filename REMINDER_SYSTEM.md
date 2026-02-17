# Invoice Reminder System - Implementation Guide

## 🎯 Overview

This system automatically sends email reminders to clients before their invoices are due. It's optimized for **shared hosting environments** with limited resources.

## ✨ Features Implemented

### Phase 1: Reminder Schedule System
- ✅ Configurable reminder schedules (30, 15, 5, 1 days before due date)
- ✅ Enable/disable individual reminder tiers
- ✅ Per-subscription reminder preferences
- ✅ Grace period configuration
- ✅ Customizable email templates

### Phase 2: Service Line Items & Notification Logging
- ✅ Subscription items (track individual service components)
- ✅ Detailed notification logs with audit trail
- ✅ Automatic retry mechanism for failed emails
- ✅ Monthly cleanup of old logs

## 📊 Database Schema

### New Tables

1. **subscription_items** - Track service line items
   - name, description, quantity, unit_price
   - metadata (JSON for custom fields)
   - Linked to subscriptions

2. **notification_logs** - Audit trail for all reminders
   - subscription_id, type, channel, status
   - sent_at, error_message
   - Indexed for fast queries

### Updated Tables

1. **subscriptions**
   - `reminders_enabled` (boolean)
   - `last_reminders_sent` (JSON)
   - `custom_reminder_days` (JSON)
   - Optimized indexes for reminder queries

2. **settings** (key-value pairs)
   - reminder_1_days, reminder_2_days, reminder_3_days, reminder_final_days
   - reminder_1_enabled, reminder_2_enabled, etc.
   - grace_period_days
   - reminder_email_subject, reminder_email_body

## 🚀 How It Works

### 1. Daily Reminder Check (9:00 AM)
```bash
php artisan reminders:send --limit=100
```

- Checks all active subscriptions
- Identifies subscriptions due in 30, 15, 5, or 1 days
- Queues reminder emails (max 100 per run)
- Prevents duplicate sends using `last_reminders_sent`

### 2. Queue Processing (Every 5 Minutes)
```bash
php artisan queue:work database --stop-when-empty --max-time=240
```

- Processes queued reminder emails
- Runs for max 4 minutes (240 seconds)
- Stops when queue is empty (resource-efficient)
- Automatic retry on failure (3 attempts)

### 3. Email Sending
- Uses customizable templates from settings
- Replaces placeholders: {client_name}, {amount}, {due_date}, etc.
- Logs success/failure to notification_logs
- Updates last_reminders_sent to prevent duplicates

## 📧 Email Template Placeholders

Available placeholders for email templates:
- `{client_name}` - Client's name
- `{service_name}` - Service name or alias
- `{amount}` - Invoice amount
- `{currency}` - Currency symbol
- `{due_date}` - Formatted due date
- `{days_until_due}` - Days remaining
- `{billing_cycle}` - Billing cycle name

## ⚙️ Configuration

### Settings (via Settings Page)

1. **Reminder Days**
   - reminder_1_days: 30 (default)
   - reminder_2_days: 15 (default)
   - reminder_3_days: 5 (default)
   - reminder_final_days: 1 (default)

2. **Enable/Disable**
   - reminder_1_enabled: true
   - reminder_2_enabled: true
   - reminder_3_enabled: true
   - reminder_final_enabled: true

3. **Grace Period**
   - grace_period_days: 3 (days after due date before suspension)

4. **Email Templates**
   - reminder_email_subject
   - reminder_email_body

### Per-Subscription Settings

Each subscription can:
- Enable/disable reminders (`reminders_enabled`)
- Override global reminder days (`custom_reminder_days`)

## 🔧 cPanel Setup

### 1. Create Cron Job

Add this to cPanel Cron Jobs (every 5-15 minutes):

```bash
*/15 * * * * cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1
```

**Replace:**
- `/home/username/public_html` with your actual path
- `*/15` with `*/5` for every 5 minutes (recommended)

### 2. Queue Configuration

Ensure `config/queue.php` uses database driver:

```php
'default' => env('QUEUE_CONNECTION', 'database'),
```

### 3. Create Queue Table (if not exists)

```bash
php artisan queue:table
php artisan migrate
```

## 📈 Performance Optimizations

### For Shared Hosting

1. **Chunking** - Processes 10 subscriptions at a time
2. **Limiting** - Max 100 reminders per run (configurable)
3. **Indexes** - Optimized database queries
4. **Queue** - Database queue (no Redis/Beanstalk needed)
5. **Timeout** - Jobs timeout after 30 seconds
6. **Retry** - 3 attempts with exponential backoff

### Memory Management

- Eager loading to prevent N+1 queries
- Chunk processing to avoid loading all data
- Automatic cleanup of old logs

## 🧪 Testing

### Test Reminder Command

```bash
php artisan reminders:send --limit=10
```

### Test Queue Processing

```bash
php artisan queue:work database --stop-when-empty
```

### Check Scheduled Tasks

```bash
php artisan schedule:list
```

### View Queue Jobs

```bash
php artisan queue:monitor
```

## 📊 Monitoring

### Check Notification Logs

```php
use App\Models\NotificationLog;

// Recent reminders
$recent = NotificationLog::latest()->limit(10)->get();

// Failed reminders
$failed = NotificationLog::where('status', 'failed')->get();

// Success rate
$total = NotificationLog::count();
$sent = NotificationLog::where('status', 'sent')->count();
$rate = ($sent / $total) * 100;
```

### Log Files

Check `storage/logs/laravel.log` for:
- Reminder processing summary
- Failed email attempts
- Performance metrics

## 🎨 Subscription Items Usage

### Adding Items to Subscription

```php
use App\Models\SubscriptionItem;

// Example: Business Email Service
$subscription->items()->create([
    'name' => 'john@company.com',
    'description' => 'Primary business email',
    'quantity' => 1,
    'unit_price' => 10.00,
    'metadata' => ['storage' => '50GB'],
    'sort_order' => 1,
]);

$subscription->items()->create([
    'name' => 'jane@company.com',
    'description' => 'Secondary business email',
    'quantity' => 1,
    'unit_price' => 10.00,
    'sort_order' => 2,
]);
```

### Displaying Items

```blade
@foreach($subscription->items as $item)
    <div>
        {{ $item->name }} - ${{ $item->unit_price }} × {{ $item->quantity }}
        = ${{ $item->total_price }}
    </div>
@endforeach

<div>
    Total with items: ${{ $subscription->total_with_items }}
</div>
```

## 🔐 Security

- All emails sent through authenticated SMTP
- Rate limiting to prevent spam
- Tenant isolation (multi-tenant support)
- SQL injection prevention (Eloquent ORM)
- XSS protection in email templates

## 🐛 Troubleshooting

### Reminders Not Sending

1. Check cron job is running:
   ```bash
   grep CRON /var/log/syslog
   ```

2. Check queue has jobs:
   ```bash
   php artisan queue:monitor
   ```

3. Check notification logs:
   ```php
   NotificationLog::where('status', 'failed')->latest()->get();
   ```

### High Memory Usage

- Reduce `--limit` parameter (default: 100)
- Increase chunk size in command (default: 10)
- Check for N+1 queries

### Emails in Spam

- Configure SPF/DKIM records
- Use reputable SMTP service
- Warm up IP address gradually

## 📝 Next Steps (Future Enhancements)

- [ ] WhatsApp reminder integration
- [ ] SMS reminders
- [ ] Client portal for viewing upcoming invoices
- [ ] A/B testing for reminder timing
- [ ] Analytics dashboard
- [ ] Overdue reminders
- [ ] Automatic service suspension after grace period

## 🆘 Support

For issues or questions:
1. Check logs: `storage/logs/laravel.log`
2. Review notification_logs table
3. Test command manually: `php artisan reminders:send --limit=1`

---

**Last Updated:** February 17, 2026
**Version:** 1.0.0
