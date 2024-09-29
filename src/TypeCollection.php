<?php
namespace Veneridze\ModelTypes;
use Illuminate\Support\Facades\Config;
use Veneridze\ModelTypes\Exceptions\EmptyModelTypeCollection;
use Veneridze\ModelTypes\Exceptions\UnknownTypeCollection;
use Veneridze\ModelTypes\Exceptions\WrongTypeException;
use Veneridze\ModelTypes\Interfaces\TypeInterface;

class TypeCollection {
    readonly array $types;
    public function __construct(string $type) {
        $this->types = Config::get("types.{$type}");
        if(is_null($this->types)) {
            throw new UnknownTypeCollection("Коллекция типов {$type} не существует", 500);
        }
    }
    /**
     * Summary of getType
     * @param string $name
     */
    public function __isset(string $name): bool {
        $name = strtolower($name);
        return count(array_filter($this->types, fn(string $type) => strtolower(basename($type)) == $name)) > 0;
    
    }
    //TypeInterface | 
    public function __get(string $name): string {
        $name = strtolower($name);
        $search = array_values(array_filter($this->types, fn(string $type) => strtolower(basename($type)) == $name));
        if(count($search) == 0) {
            throw new WrongTypeException("Тип {$name} не существует в коллекции {$this->typeSpace}", 500);
        }

        return $search[0];
    }
}
