<?php
namespace QiblaEvents\Form\Factories;

use QiblaEvents\Form\Interfaces\Fields;
use QiblaEvents\Form\Interfaces\Types;

/**
 * Field Factory
 *
 * @since      1.0.0
 * @package    QiblaEvents\Form\Factories
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license    http://opensource.org/licenses/gpl-2.0.php GPL v2
 *
 * Copyright (C) 2018 Guido Scialfa <dev@guidoscialfa.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

/**
 * FieldFactory
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 */
class FieldFactory
{
    /**
     * Base Namespace
     *
     * @since  1.0.0
     *
     * @var string The base namespace
     */
    const NS = 'QiblaEvents';

    /**
     * Field Namespace
     *
     * @since  1.0.0
     *
     * @var string The field namespace fragment
     */
    const FIELD_NS = 'Form\\Fields';

    /**
     * Type Namespace
     *
     * @since  1.0.0
     *
     * @var string The type namespace fragment
     */
    const TYPE_NS = 'Form\\Types';

    /**
     * Build Namespace
     *
     * @since 1.0.0
     *
     * @param string $class The classname to append
     * @param string $typo  The
     *
     * @return string The namespace string
     */
    protected function buildNamespace($class, $typo = 'type')
    {
        $ns = self::NS . '\\';

        switch ($typo) {
            case 'field':
                $ns .= self::FIELD_NS;
                break;
            default:
                $ns .= self::TYPE_NS;
                break;
        }

        $class = ucwords(preg_replace('/[^a-z0-9]/', ' ', $class));
        $ns    .= '\\' . str_replace(' ', '', $class);

        return $ns;
    }

    /**
     * Create Type
     *
     * @since  1.0.0
     *
     * @param string $type The type string relative to the input to create.
     *
     * @return Types The new input type
     */
    public function createType($type, $args)
    {
        // Create the class name.
        $type = $this->buildNamespace($type);

        // Create the type.
        return new $type($args);
    }

    /**
     * Create Field
     *
     * @since  1.0.0
     *
     * @param string $fieldType The type of the field.
     * @param array  $args      The arguments to create the field.
     *
     * @return Fields A class instance that implement the IFields instance
     */
    private function createField($fieldType, array $args)
    {
        // Get the field type class.
        $fieldClass = $this->buildNamespace($fieldType, 'field') . 'Field';
        // Get the Type.
        $type = $this->createType($args['type'], $args);
        // Get the Field.
        $field = new $fieldClass($type, $args);

        return $field;
    }

    /**
     * Call
     *
     * We can create the correct field type.
     * For available field types see Form/Fields directory. The name of the method must not include 'Field' text.
     *
     * @param string $name The method name.
     * @param array  $args The arguments to pass to the method.
     *
     * @return mixed
     */
    public function __call($name, $args)
    {
        return call_user_func_array(array($this, 'createField'), array($name, $args[0]));
    }
}
