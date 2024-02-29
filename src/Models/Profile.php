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

use Zemit\Models\Abstracts\AbstractProfile;
use Zemit\Models\Interfaces\ProfileInterface;

class Profile extends AbstractProfile implements ProfileInterface
{
    protected $deleted = self::NO;

    public function initialize(): void
    {
        parent::initialize();
        // @todo relationships
    }

    public function validation(): bool
    {
        $validator = $this->genericValidation();

        // @todo validations
        
        return $this->validate($validator);
    }
}