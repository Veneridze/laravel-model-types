<?php
namespace Veneridze\ModelTypes\Traits;

use Veneridze\ModelTypes\Exceptions\WrongTypeException;
use Veneridze\ModelTypes\Interfaces\TypeInterface;
use Veneridze\ModelTypes\TypeCollection;


trait HasType {
    protected string $typeSpace = 'example';
    public function type(): TypeInterface  {
        $current_type = $this->type;
        
        return $this->typeCollection()->$current_type;
    }

    private function typeCollection(): TypeCollection | null {
        return $this->getCurrentType() ? new TypeCollection($this->getCurrentType()) : null;
    }

    private function currentType(): string | null {
        return $this->typeSpace;
    }
}