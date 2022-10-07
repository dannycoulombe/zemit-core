<?php
/**
 * This file is part of the Zemit Framework.
 *
 * (c) Zemit Team <contact@zemit.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Zemit\Models;

use Zemit\Models\Base\AbstractFlag;
use Phalcon\Filter\Validation\Validator\PresenceOf;
use Phalcon\Filter\Validation\Validator\StringLength\Max;

/**
 * Class Flag
 *
 * @package Zemit\Models
 */
class Flag extends AbstractFlag
{
    protected $deleted = self::NO;

    public function initialize()
    {
        parent::initialize();
        // @todo relationships
    }

    public function validation()
    {
        $validator = $this->genericValidation();
        
        // @todo validations
        
        return $this->validate($validator);
    }
}
