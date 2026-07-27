<?php

namespace Coco\SourceWatcher\Core\Transformers;

use Coco\SourceWatcher\Core\Data\Row;
use Coco\SourceWatcher\Core\Exception\SourceWatcherException;
use Coco\SourceWatcher\Core\Step\Transformer;

/**
 * Create or replace a field using a constrained expression.
 */
class DeriveFieldTransformer extends Transformer
{
    protected string $targetField = "";

    protected string $expression = "";

    protected array $availableOptions = [ "targetField", "expression" ];

    /**
     * @throws SourceWatcherException
     */
    public function options ( array $options ) : void
    {
        parent::options( $options );
        $this->validateConfiguration();
    }

    /**
     * @throws SourceWatcherException
     */
    public function transform ( Row $row ) : void
    {
        $this->validateConfiguration();
        $evaluator = new ExpressionEvaluator();
        $row->set( trim( $this->targetField ), $evaluator->evaluate( $this->expression, $row ) );
    }

    /**
     * @throws SourceWatcherException
     */
    private function validateConfiguration () : void
    {
        if ( trim( $this->targetField ) === "" ) {
            throw new SourceWatcherException( "Derive Field requires a target field." );
        }

        ( new ExpressionEvaluator() )->validate( $this->expression );
    }
}
