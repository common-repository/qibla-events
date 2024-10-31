<?php
/**
 * AbstractRequestFormAjax
 *
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @copyright  Copyright (c) 2018, Guido Scialfa
 * @license   GNU General Public License, version 2
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

namespace QiblaEvents\Request;

use QiblaEvents\Form\Interfaces\Forms;
use QiblaEvents\Functions as F;

/**
 * Class AbstractRequestFormAjax
 *
 * @since   1.0.0
 * @author     Guido Scialfa <dev@guidoscialfa.com>
 * @package QiblaEvents\Request
 */
abstract class AbstractRequestFormAjax extends AbstractRequestAjax
{
    /**
     * Form
     *
     * @since  1.0.0
     *
     * @var Forms The forms to handle with the submission
     */
    protected $form;

    /**
     * Action Name
     *
     * @since  1.0.0
     *
     * @var string The name of the Action to perform
     */
    protected $actionName;

    /**
     * AbstractRequestFormAjax constructor
     *
     * @since 1.0.0
     *
     * @param Forms  $form       The instance of the form.
     * @param string $actionName The action name value.
     */
    public function __construct(Forms $form, $actionName)
    {
        $this->form       = $form;
        $this->actionName = $actionName;
    }

    /**
     * @inheritDoc
     */
    public function isValidRequest()
    {
        // @codingStandardsIgnoreLine
        $action  = F\filterInput($_POST, self::$actionKey, FILTER_SANITIZE_STRING);
        $isValid = $this->actionName === $action;
        // Validate Nonce. Valid or die.
        $nonce = new Nonce($this->form->getArg('name'), $this->form->getArg('method'), false);

        return $isValid && $nonce->verify();
    }
}
