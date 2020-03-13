<?php

namespace Coco\SourceWatcher\Core;

abstract class Transformer extends Step
{
    abstract public function transform ( Row $row );
}
