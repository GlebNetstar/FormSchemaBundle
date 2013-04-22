<?php

/*
 * This file is part of the Netstar Form Schema package.
 *
 * (c) Gleb Tiltikov <gleb@netstar.od.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Netstar\FormSchemaBundle\Model;

use Netstar\FormSchemaBundle\Model\FormRelation;

interface FormSchemaInterface
{
    /**
     * @return array
     */
    public function getRelations();
    
    public function relationBuilder();
}
