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

use Symfony\Component\Form\FormBuilderInterface;
use Netstar\FormSchemaBundle\Model\FormSchemaInterface;
use Netstar\FormSchemaBundle\Model\FormRelationSonataAdminFilter;

class FormSchemaSonataAdminFilter implements FormSchemaInterface
{
	protected $relations;
	
    public function __construct()
    {
        $this->relations = array();
    }
    
    public function getRelations()
    {
    	return $this->relations;
    }

    public function addRelation(FormRelationSonataAdminFilter $relation)
    {
    	$this->relations[] = $relation;
    }
    
    public function relationBuilder()
    {
    	return new FormRelationSonataAdminFilter();
    }
}
