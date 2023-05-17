<?php

namespace App\Library;

use Closure;

class ArrayTraversalContext {
    public ?Closure $function = null;
    private array $prefix = [];
    public ?Closure $dontRecurse = null;
    public ?Closure $preprocess = null;
    public ?Closure $postprocess = null;
    public ?Closure $reducer = null;
    public mixed $data = null;

    function __construct(...$args) {
        if (array_key_exists('prototype', $args)) {
            $prototype = $args['prototype'];
            $this->function = $prototype->function;
            $this->prefix = $prototype->prefix;
            $this->dontRecurse = $prototype->dontRecurse;
            $this->preprocess = $prototype->preprocess;
            $this->postprocess = $prototype->postprocess;
            $this->reducer = $prototype->reducer;
            $this->data = $prototype->data;
        }
        unset($args['prototype']);
        foreach ($args as $key => $value) {
            $this->{$key} = $value;
        }
    }

    function getPrefix(): array {
        return $this->prefix;
    }

    function accumulate(mixed $key): self {
        $oldPrefix = $this->prefix;
        $this->prefix = [...$this->prefix, $key];
        $clone = clone $this;
        $this->prefix = $oldPrefix;
        if ($clone->reducer) {
            return ($clone->reducer)($clone, $key);
        } else return $clone;
    }
}
