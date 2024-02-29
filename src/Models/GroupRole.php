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

use Zemit\Models\Abstracts\AbstractGroupRole;
use Zemit\Models\Interfaces\GroupRoleInterface;

/**
 * @property Group $Group
 * @method Group getGroup(?array $params = null)
 *
 * @property Role $Role
 * @method Role getRole(?array $params = null)
 */
class GroupRole extends AbstractGroupRole implements GroupRoleInterface
{
    protected $deleted = self::NO;
    protected $position = self::NO;
    
    public function initialize(): void
    {
        parent::initialize();
        
        $this->hasOne('groupId', Group::class, 'id', ['alias' => 'Group']);
        $this->hasOne('roleId', Role::class, 'id', ['alias' => 'Role']);
    }
    
    public function validation(): bool
    {
        $validator = $this->genericValidation();
        
        $this->addUnsignedIntValidation($validator, 'groupId', false);
        $this->addUnsignedIntValidation($validator, 'roleId', false);
        $this->addUniquenessValidation($validator, ['groupId', 'roleId'], false);
        
        return $this->validate($validator);
    }
}
