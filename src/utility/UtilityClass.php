<?php
/**
 * Utility class
 */
namespace Itb\utility;

/**
 * utility class
 * Class UtilityClass
 * @package Itb\utility
 */
class UtilityClass
{
    /**
     * helper class
     * @param $namespace
     * @param $shortName
     * @return string
     */
    public static function controller($namespace, $shortName)
    {
        list($shortClass, $shortMethod) = explode('/', $shortName, 2);

        $shortClassCapitlise = ucfirst($shortClass);

        $namespaceClassAction = sprintf($namespace . '\\' . $shortClassCapitlise . 'Controller::' . $shortMethod . 'Action');

        return $namespaceClassAction;
    }
}
