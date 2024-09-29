<?php
namespace Veneridze\ModelTypes\Traits;

use Veneridze\ModelTypes\Exceptions\WrongTypeException;
use Veneridze\ModelTypes\Interfaces\TypeInterface;
use Veneridze\ModelTypes\TypeCollection;


trait HasType {

    public function typeCollection(): TypeCollection | null {
        return $this->currentType() ? new TypeCollection($this->currentType()) : null;
    }

    private function currentType(): string | null {
        return $this->typeSpace;
    }
}