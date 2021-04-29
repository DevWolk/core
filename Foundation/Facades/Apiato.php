<?php

namespace Apiato\Core\Foundation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Apiato.
 *
 * Get the port folders names
 * @method static array getShipFoldersNames()
 * Get Ship layer directories paths
 * @method static array getShipPath()
 * @method static array getSectionContainerNames(string $sectionName)
 * Build and return an object of a class from its file path
 * @method static mixed getClassObjectFromFile(string $filePathName)
 * Get the full name (name \ namespace) of a class from its file path
 * result example: (string) "I\Am\The\Namespace\Of\This\Class"
 * @method static string getClassFullNameFromFile(string $filePathName)
 * @method static array getSectionPaths()
 * Get the last part of a camel case string.
 * Example input = helloDearWorld | returns = World
 * @method static mixed getClassType($className)
 * @method static array getAllContainerNames()
 * @method static array getAllContainerPaths()
 * @method static array getSectionNames()
 * @method static array getSectionContainerPaths(string $sectionName)
 * @method static void verifyClassExist(string $className) ClassDoesNotExistException
 *
 * @see \Apiato\Core\Foundation\Apiato
 */
class Apiato extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'Apiato';
    }
}
