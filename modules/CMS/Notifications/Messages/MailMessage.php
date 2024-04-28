<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Notifications\Messages;

use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\File;
use Juzaweb\Backend\Models\EmailTemplate;
use Juzaweb\CMS\Contracts\HookActionContract;
use TwigBridge\Facade\Twig;

class MailMessage implements Renderable, Arrayable
{
    protected string $template;

    public string $subject;

    public string $body;

    public array $data = [];

    protected string $layout = 'cms::backend.email.layouts.default';

    /**
     * Set the message subject.
     *
     * @param  string  $subject
     * @return $this
     */
    public function subject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the message body.
     *
     * @param  string  $body
     * @return $this
     */
    public function body(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set the message data.
     *
     * @param  array  $data
     * @return $this
     */
    public function data(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Set the default markdown template.
     *
     * @param  string  $template
     * @return $this
     */
    public function template(string $template): static
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Set the message layout.
     *
     * @param  string  $layout
     * @return $this
     */
    public function layout(string $layout): static
    {
        $this->layout = $layout;

        return $this;
    }

    public function getBody(): string
    {
        if (!isset($this->template)) {
            return $this->body;
        }

        $template = EmailTemplate::where('code', $this->template)->first();

        if ($template) {
            $body = $template->body;
        } else {
            $template = app(HookActionContract::class)->getEmailTemplates($this->template);
            $body = File::get(view($template->get('body'))->getPath());
        }

        return $this->mapTwigParams($body, $this->getData());
    }

    /**
     * Get the message data.
     *
     * @return array
     */
    public function getData(): array
    {
        return array_merge($this->data, [
            'subject' => $this->subject,
            'body' => $this->body,
        ]);
    }

    public function render()
    {
        $data = $this->getData();
        $data['body'] = $this->getBody();

        return Container::getInstance()->make('mailer')?->render(
            $this->layout, $data
        );
    }

    public function toArray(): array
    {
        return array_merge(
            $this->getData(),
            [
                'subject' => $this->subject,
                'body' => $this->getBody(),
            ]
        );
    }

    protected function mapTwigParams(string $string, array $params = []): string
    {
        return Twig::createTemplate($string)->render($params);
    }
}
