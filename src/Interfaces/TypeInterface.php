<?php
namespace Veneridze\ModelTypes\Interfaces;
interface TypeInterface {
    public static function fields(): array;

    public static function rules(): array;
}