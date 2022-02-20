<?php


namespace Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock;

use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Support\Fluent;
use Lorisleiva\Actions\Concerns\AsJob;
use Lorisleiva\Actions\Decorators\JobDecorator;
use Lorisleiva\Actions\Decorators\UniqueJobDecorator;
use phpDocumentor\Reflection\Php\Argument;
use phpDocumentor\Reflection\Types\Boolean;
use Wulfheart\LaravelActionsIdeHelper\Service\Generator\DocBlock\Custom\Method;
use Wulfheart\LaravelActionsIdeHelper\Service\ActionInfo;

class AsJobGenerator extends DocBlockGeneratorBase implements DocBlockGeneratorInterface
{
    protected string $context = AsJob::class;

    /**
     * @inheritDoc
     */
    public function generate(ActionInfo $info): array
    {

        $method = $this->findMethod($info, 'asJob', 'handle');

        if ($method == null) {
            return [];
        }

        $args = $method->getArguments();


        return [
            new Method('makeJob', $args, $this->resolveAsUnionType(JobDecorator::class, UniqueJobDecorator::class),
                true),
            new Method('makeUniqueJob', $args, $this->resolveType(UniqueJobDecorator::class), true),
            new Method('dispatch', $args, $this->resolveType(PendingDispatch::class), true),
            new Method('dispatchIf',
                collect($args)->prepend(new Argument('boolean', new Boolean()))->toArray(),
                $this->resolveAsUnionType(PendingDispatch::class, Fluent::class),
                true),
            new Method('dispatchUnless',
                collect($args)->prepend(new Argument('boolean', new Boolean()))->toArray(),
                $this->resolveAsUnionType(PendingDispatch::class, Fluent::class),
                true),
            new Method('dispatchSync', $args, null, true),
            new Method('dispatchNow', $args, null, true),
            new Method('dispatchAfterResponse', $args, null, true),
        ];
    }


}
