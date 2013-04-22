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

class FormRelationSonataAdminFilter
{
    /**
     * @var string
     */
    protected $filterName;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var string
     */
    protected $parentRelation;

    /**
     * @var array
     */
    protected $orderByParams;

    /**
     * @var \Netstar\FormSchemaBundle\Model\FormRelationSonataAdminFilter
     */
    protected $slave;

    
    public function __construct()
    {
    	$this->orderByParams = array();
    }
    
    
    /**
     * Set filterName
     *
     * @param string $filterName
     * @return FormRelationSonataAdminFilter
     */
    public function setFilterName($filterName)
    {
        $this->filterName = $filterName;
    	
    	return $this;
    }

    /**
     * Get slaves
     *
     * @return string 
     */
    public function getFilterName()
    {
        return str_replace('.', '__', $this->filterName);
    }
    
    /**
     * Set master
     *
     * @param string $entity
     * @return FormRelationSonataAdminFilter
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;
    	
    	return $this;
    }

    /**
     * Get master
     *
     * @return string 
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set parentRelation
     *
     * @param string $parentRelation
     * @return FormRelationSonataAdminFilter
     */
    public function setParentRelation($parentRelation)
    {
        $this->parentRelation = $parentRelation;
    	
    	return $this;
    }

    /**
     * Get parentRelation
     *
     * @return string 
     */
    public function getParentRelation($id = null)
    {
    	if($id) return array($this->parentRelation => $id);
    	
    	else return array();
    }
    
    /**
     * Set orderByParams
     *
     * @param array $orderByParams
     * @return FormRelationSonataAdminFilter
     */
    public function setOrderByParams($orderByParams)
    {
        $this->orderByParams = $orderByParams;
    	
    	return $this;
    }

    /**
     * Get orderByParams
     *
     * @return array 
     */
    public function getOrderByParams()
    {
        return $this->orderByParams;
    }

    /**
     * Set slave
     *
     * @param string $slave
     * @return FormRelationSonataAdminFilter
     */
    public function setSlave($slave)
    {
        $this->slave = $slave;
    	
    	return $this;
    }

    /**
     * Get slaves
     *
     * @return string 
     */
    public function getSlave()
    {
        return $this->slave;
    }
}
