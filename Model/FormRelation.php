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

class FormRelation
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $master;

    /**
     * @var array
     */
    protected $slaves;

    /**
     * @var string
     */
    protected $retrieveEntity;

    /**
     * @var string
     */
    protected $findByFieldName;

    /**
     * @var array
     */
    protected $findByParams;

    /**
     * @var array
     */
    protected $orderByParams;

    /**
     * @var string
     */
    protected $resultFieldName;

    /**
     * @var string
     */
    protected $queryBuilder;

    
    public function __construct($master = null)
    {
    	$this->id = uniqid();
    	$this->master = $master;
    	$this->slaves = array();
    	$this->findByParams = array();
    	$this->orderByParams = array();
    }
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set slaves
     *
     * @param string $slave
     * @return FormRelation
     */
    public function setSlave($slave)
    {
        $this->slaves[] = $slave;
    	
    	return $this;
    }
    
    /**
     * Set slaves
     *
     * @param array $slaves
     * @return FormRelation
     */
    public function setSlaves($slaves)
    {
        $this->slaves = $slaves;
    	
    	return $this;
    }

    /**
     * Get slaves
     *
     * @return array 
     */
    public function getSlaves()
    {
        return $this->slaves;
    }
    
    /**
     * Set master
     *
     * @param string $master
     * @return FormRelation
     */
    public function setMaster($master)
    {
        $this->master = $master;
    	
    	return $this;
    }

    /**
     * Get master
     *
     * @return string 
     */
    public function getMaster()
    {
        return $this->master;
    }

    /**
     * Set retrieveEntity
     *
     * @param string $retrieveEntity
     * @return FormRelation
     */
    public function setRetrieveEntity($retrieveEntity)
    {
        $this->retrieveEntity = $retrieveEntity;
    	
    	return $this;
    }

    /**
     * Get retrieveEntity
     *
     * @return string 
     */
    public function getRetrieveEntity()
    {
        return $this->retrieveEntity;
    }

    /**
     * Set findByFieldName
     *
     * @param string $findByFieldName
     * @return FormRelation
     */
    public function setFindByFieldName($findByFieldName)
    {
        $this->findByFieldName = $findByFieldName;
    	
    	return $this;
    }

    /**
     * Get findByFieldName
     *
     * @return string 
     */
    public function getFindByFieldName()
    {
        return $this->findByFieldName;
    }

    /**
     * Set findByParams
     *
     * @param array $findByParams
     * @return FormRelation
     */
    public function setFindByParams($findByParams)
    {
        $this->findByParams = $findByParams;
    	
    	return $this;
    }

    /**
     * Get findByParams
     *
     * @return array 
     */
    public function getFindByParams()
    {
        return $this->findByParams;
    }

    /**
     * Set orderByParams
     *
     * @param array $orderByParams
     * @return FormRelation
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
     * Set resultFieldName
     *
     * @param string $resultFieldName
     * @return FormRelation
     */
    public function setResultFieldName($resultFieldName)
    {
        $this->resultFieldName = $resultFieldName;
    	
    	return $this;
    }

    /**
     * Get resultFieldName
     *
     * @return string
     */
    public function getResultFieldName()
    {
        return $this->resultFieldName;
    }

    /**
     * Set queryBuilder
     *
     * @param string $queryBuilder
     * @return FormRelation
     */
    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    	
    	return $this;
    }

    /**
     * Get queryBuilder
     *
     * @return string
     */
    public function getQueryBuilder()
    {
        return $this->queryBuilder;
    }
}
