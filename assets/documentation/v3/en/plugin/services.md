[auto_menu]

### Email Service

#### Config Email Service
- Config method send email. Support sync, queue, cron
```dotenv
JW_MAIL_METHOD=sync
```

- File **juzaweb.php** config
```php
return [
    // Your config
    
    'email' => [
        /**
         * Method send email
         *
         * Support: sync, queue, cron
         * Default: sync
         */
        'method' => env('JW_MAIL_METHOD', 'sync')
    ]
];
```

##### Setup send Mail in CronJob
- If you use crontab to send email, you need to set up ``The Scheduler`` on your server, hosting
- Setup ``The Scheduler``: Add command to your server
```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```
View more: [Starting The Scheduler](https://laravel.com/docs/9.x/scheduling#introduction)

#### Register Email Template
- Before sending an email, you need to register a email template. Add to init action
```php
$this->addAction(Action::INIT_ACTION, [$this, 'addEmailTemplates']);
```

```php
public function addEmailTemplates()
{
    $this->hookAction->registerEmailTemplate(
        'email-template-code',
        [
            'subject' => 'subject',
            'body' => "example::email.template",
            'params' => [
                'name' => 'User name',
            ],
        ]
    );
}
```

#### Send email with template
```php
use Juzaweb\Support\Email;

Email::make()
    ->withTemplate('email-template-code')
    ->setEmails('test@example.com')
    ->setParams([
        'name' => 'Juzaweb',
    ])
    ->send();
```

#### Email Hook
- Email Hooks like Laravel's events provide a simple observer pattern implementation that allows you to subscribe and users can set up listening for various events that occur in your application. It will listen and send all email templates with the action hook selected

##### Register Email Hook

Add to your file Action
```php
namespace Vendor\Name\Actions;

use Juzaweb\Abstracts\Action;
use Juzaweb\Backend\Facades\HookAction;

class YourAction extends Action
{
    public function handle()
    {
        // Your code
        $this->addAction(Action::INIT_ACTION, [$this, 'registerEmailHooks']);
    }

    public function registerEmailHooks()
    {
        HookAction::registerEmailHook(
            'register_success',
             [
                'label' => trans('domain::content.email_hook_label'),
                'params' => [
                    'param' => trans('domain::content.param_label'),       
                ],
            ]
        );
    }
}    
```

##### Add event in your Controller
```php
use Juzaweb\CMS\Events\EmailHook;
use Illuminate\Http\Request;

class YouController
{
    public function register(Request $request)
    {
        // Your code
        
        $name = $request->input('name');
        $email = $request->input('email');
        
        event(
            new EmailHook(
            'register_success',
                [
                    'to' => [$email],
                    'params' => [
                        'param' => [
                            'name' => $name,
                            'email' => $email
                        ],
                    ],
                ]
            )
        );
    }
}
```

- In email template manage, you can choose **Email Hook** for email templates. When the **register_success** event occurs, this email template will be sent.
![Chosse hook in email template](https://i.imgur.com/1Pr8kwI.gif)
