<?php

namespace Veneridze\ModelTypes;

use Closure;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Config;
use ReflectionClass;
use Veneridze\ModelTypes\Exceptions\EmptyModelTypeCollection;
use Veneridze\ModelTypes\Exceptions\UnknownTypeCollection;
use Veneridze\ModelTypes\Exceptions\WrongTypeException;

class TypeCollection implements Arrayable, ValidationRule
{
    readonly array $types;
    public function __construct(private string $type)
    {
        $this->types = Config::get("model-types.{$type}");
        if (is_null($this->types)) {
            throw new UnknownTypeCollection("Коллекция типов {$type} не существует", 500);
        }
    }

    /**
     * Summary of getType
     * @param string $name
     */
    public function __isset(string $name): bool
    {
        $name = strtolower($name);
        return count(array_filter($this->types, fn(string $type) => strtolower((new ReflectionClass($type))->getShortName()) == $name)) > 0;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $value = strtolower(basename($value));
        if (!isset($this->$value)) {
            $fail("Тип {$value} не существует в коллекции {$this->type}");
        }
    }

    public function toSelect(bool $lower = true): array
    {
        return array_map(function ($type) use ($lower): array {
            if ($lower) {
                $short = strtolower((new ReflectionClass($type))->getShortName());
            } else {
                $short = $type;//strtolower((new ReflectionClass($type))->getShortName());
            }
            return [
                "label" => property_exists($type, 'label') ? $type::$label : $short,
                "value" => $short
            ];
        }, $this->types);
    }

    //public function toValidationRules() {
    //
    //}

    public function toForm(string $key, string $property = 'fields', array $visibleif = []): array
    {
        $result = [];
        foreach ($this->types as $type) {
            if (method_exists($type, $property)) {
                $result = [
                    ...$result,
                    ...array_map(
                        fn($row) =>
                        array_map(function ($field) use ($type, $visibleif, $key) {
                            $field->visibleif = [
                                ...($field->visibleif ?? []),
                                ...($visibleif ?? []),
                                ...[
                                    $key => strtolower((new ReflectionClass($type))->getShortName())
                                ]
                            ];
                            return $field;
                        }, $row),
                        $type::$property()
                    )
                ];
            }
        }
        return $result;
    }

    public function toArray(): array
    {
        return $this->types;
    }
    //TypeInterface | 
    public function __get(string $name): string
    {
        $name = strtolower($name);
        $search = array_values(array_filter($this->types, fn(string $type) => strtolower((new ReflectionClass($type))->getShortName()) == strtolower($name)));
        if (count($search) == 0) {
            //throw new Exception(strtolower(basename($this->types[0])));
            throw new WrongTypeException("Тип {$name} не существует в коллекции {$this->type}" . PHP_EOL . "Доступно типов:" . count($this->types), 500);
        }

        return $search[0];
    }
}
