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

use Zemit\Models\Abstracts\AbstractMeta;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength\Max;
use Zemit\Models\Interfaces\MetaInterface;

class Meta extends AbstractMeta implements MetaInterface
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
