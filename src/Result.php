<?php
/**
 * PHP version 5
 *
 * @category Classes
 * @package  LayerShifter/TLDExtract
 * @author   Alexander Fedyashov <a@fedyashov.com>
 * @author   W-Shadow <whiteshadow@w-shadow.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/layershifter/TLDExtract
 */

namespace LayerShifter\TLDExtract;

use ArrayAccess;
use LogicException;
use OutOfRangeException;

/**
 * This class holds the components of a domain name.
 *
 * You can access the components using either property syntax or array syntax. For example, "echo $result->tld" and
 * "echo $result['tld']" will both work and output the same string.
 *
 * All properties are read-only.
 *
 * @category Classes
 * @package  LayerShifter/TLDExtract
 * @author   Alexander Fedyashov <a@fedyashov.com>
 * @author   W-Shadow <whiteshadow@w-shadow.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://github.com/layershifter/TLDExtract
 *
 */
class Result implements ArrayAccess
{
    /**
     * The subdomain. For example, the subdomain of "a.b.google.com" is "a.b".
     *
     * @var string
     */
    private $subdomain = null;
    /**
     * The registered domain. For example, in "a.b.google.com" the registered domain is "google".
     *
     * @var string
     */
    private $domain = null;
    /**
     * The top-level domain / public suffix. For example: "com", "co.uk", "act.edu.au".
     *
     * @var string
     */
    private $tld = null;

    /**
     * Constructor of class.
     *
     * @param $subdomain
     * @param $domain
     * @param $tld
     */
    public function __construct($subdomain, $domain, $tld)
    {
        $this->subdomain = $subdomain;
        $this->domain = $domain;
        $this->tld = $tld;
    }

    /**
     * Magic method for run isset on private params.
     *
     * @param string $name Field name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return property_exists($this, $name);
    }

    /**
     * Converts class fields to string.
     *
     * @return string
     */
    public function __toString()
    {
        return sprintf(
            "%s(subdomain='%s', domain='%s', tld='%s')",
            __CLASS__,
            $this->subdomain,
            $this->domain,
            $this->tld
        );
    }

    /**
     * Whether or not an offset exists.
     *
     * @param mixed $offset An offset to check for
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->__isset($offset);
    }

    /**
     * Returns the value at specified offset.
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Magic method, controls access to private params.
     *
     * @param string $name Name of params to retrieve
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (!property_exists($this, $name)) {
            throw new OutOfRangeException(sprintf('Unknown field "%s"', $name));
        }

        return $this->{$name};
    }

    /**
     * Magic method, makes params read-only.
     *
     * @param string $name Name of params to retrieve
     * @param mixed $value Value to set
     *
     * @return void
     */
    public function __set($name, $value)
    {
        throw new LogicException("Can't modify an immutable object.");
    }

    /**
     * Disables assigns a value to the specified offset.
     *
     * @param mixed $offset The offset to assign the value to
     * @param mixed $value Value to set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        throw new LogicException(sprintf("Can't modify an immutable object. You tried to set '%s'.", $offset));
    }

    /**
     * Disables unset of an offset
     *
     * @param mixed $offset The offset for unset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        throw new LogicException(sprintf("Can't modify an immutable object. You tried to unset '%s.'", $offset));
    }

    /**
     * Get the domain name components as a native PHP array. The returned array will contain these keys: 'subdomain',
     * 'domain' and 'tld'.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'subdomain' => $this->subdomain,
            'domain' => $this->domain,
            'tld' => $this->tld
        ];
    }

    /**
     * Get the domain name components as a JSON.
     *
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }
}
