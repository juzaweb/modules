<?php

namespace Juzaweb\Backend\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Juzaweb\CMS\Contracts\HookActionContract;
use Juzaweb\CMS\Models\Model;
use Juzaweb\Network\Interfaces\RootNetworkModelInterface;
use Juzaweb\Network\Traits\RootNetworkModel;
use TwigBridge\Facade\Twig;

/**
 * Juzaweb\Backend\Models\EmailList
 *
 * @property int $id
 * @property string $email
 * @property int|null $template_id
 * @property string|null $template_code
 * @property array|null $params
 * @property string $status pending => processing => (success || error)
 * @property int $priority
 * @property array|null $error
 * @property array|null $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read EmailTemplate|null $template
 * @method static Builder|EmailList newModelQuery()
 * @method static Builder|EmailList newQuery()
 * @method static Builder|EmailList query()
 * @method static Builder|EmailList whereCreatedAt($value)
 * @method static Builder|EmailList whereData($value)
 * @method static Builder|EmailList whereEmail($value)
 * @method static Builder|EmailList whereError($value)
 * @method static Builder|EmailList whereId($value)
 * @method static Builder|EmailList whereParams($value)
 * @method static Builder|EmailList wherePriority($value)
 * @method static Builder|EmailList whereStatus($value)
 * @method static Builder|EmailList whereTemplateId($value)
 * @method static Builder|EmailList whereUpdatedAt($value)
 * @method static Builder|EmailList whereSiteId($value)
 * @method static Builder|EmailList whereTemplate($code)
 * @property int|null $site_id
 * @method static Builder|EmailList whereTemplateCode($value)
 * @mixin Eloquent
 */
class EmailList extends Model implements RootNetworkModelInterface
{
    use RootNetworkModel;

    protected $table = 'email_lists';

    protected $fillable = [
        'template_id',
        'template_code',
        'email',
        'priority',
        'params',
        'status',
        'error',
        'data',
    ];

    protected $casts = [
        'params' => 'array',
        'data' => 'array',
        'error' => 'array',
    ];

    public const STATUS_SUCCESS = 'success';
    public const STATUS_PENDING = 'pending';
    public const STATUS_CANCEL = 'cancel';
    public const STATUS_ERROR = 'error';

    public static function mapParams($string, $params = []): string
    {
        return Twig::createTemplate($string)->render($params);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(EmailTemplate::class, 'template_id', 'id');
    }

    public function scopeWhereTemplate(Builder $builder, string $code): Builder
    {
        return $builder->whereHas(
            'template',
            function ($q) use ($code) {
                $q->where('code', '=', $code);
            }
        );
    }

    public function getSubject(): string
    {
        $subject = Arr::get($this->data, 'subject');
        if (empty($subject)) {
            if ($this->template) {
                $subject = $this->template->subject;
            } else {
                $template = app(HookActionContract::class)
                    ->getEmailTemplates($this->template_code);
                $subject = $template->get('subject');
            }
        }

        if ($siteName = get_config('sitename')) {
            $subject = "{$siteName}: {$subject}";
        }

        return static::mapParams($subject, $this->params);
    }

    public function getBody(): string
    {
        $body = Arr::get($this->data, 'body');

        if (empty($body)) {
            if ($this->template) {
                $body = $this->template->body;
            } else {
                $template = app(HookActionContract::class)->getEmailTemplates($this->template_code);
                $body = File::get(view($template->get('body'))->getPath());
            }
        }

        return static::mapParams($body, $this->params);
    }
}
