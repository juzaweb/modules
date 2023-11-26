<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\CMS\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Juzaweb\Network\Interfaces\RootNetworkModelInterface;
use Juzaweb\Network\Traits\RootNetworkModel;

/**
 * Juzaweb\CMS\Models\Job
 *
 * @property int $id
 * @property string $queue
 * @property string $payload
 * @property int $attempts
 * @property int|null $reserved_at
 * @property int $available_at
 * @property Carbon $created_at
 * @property-read mixed $command
 * @property-read mixed $name
 * @method static Builder|Job newModelQuery()
 * @method static Builder|Job newQuery()
 * @method static Builder|Job query()
 * @method static Builder|Job whereAttempts($value)
 * @method static Builder|Job whereAvailableAt($value)
 * @method static Builder|Job whereCreatedAt($value)
 * @method static Builder|Job whereId($value)
 * @method static Builder|Job wherePayload($value)
 * @method static Builder|Job whereQueue($value)
 * @method static Builder|Job whereReservedAt($value)
 * @mixin Eloquent
 */
class Job extends Model implements RootNetworkModelInterface
{
    use RootNetworkModel;

    protected $unserializedCommand;

    protected $table = 'jobs';

    public function getNameAttribute()
    {
        return $this->payload->displayName;
    }

    // Decode the content of the payload since it's stored as JSON
    public function getPayloadAttribute()
    {
        return json_decode($this->attributes['payload']);
    }

    // Since Laravel stores the command serialized in database, this undo that serialization
    // and save the result into the singleton property to prevent unserializing again
    // (since it's a hard task)
    public function getCommandAttribute()
    {
        if (!$this->unserializedCommand) {
            $this->unserializedCommand = unserialize($this->payload->data->command);
        }

        return $this->unserializedCommand;
    }
}
