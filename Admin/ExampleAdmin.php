<?php

namespace Master\CoreBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class MetrosAdmin extends Admin
{
	private $container = null;
	private $em = null;
	
	public function __construct($code, $class, $baseControllerName, $container = null)
	{
	    parent::__construct($code, $class, $baseControllerName);
	    $this->container = $container;
	    $this->em = $this->container->get('doctrine.orm.entity_manager');
	}
	
	protected function configureFormFields(FormMapper $formMapper)
	{
		$district1 = (($this->getSubject()->getDistrict()) ? $this->getSubject()->getDistrict(): null);
		$district2 = (($this->getSubject()->getDistrict2()) ? $this->getSubject()->getDistrict2() : null);
		$city = (($this->getSubject()->getDistrict()) ? $this->getSubject()->getDistrict()->getCity(): null);
		
		$formMapper
			->with('Станция метро')
				->add('name', null, array('label' => 'Название'))
				->add('city', 'entity', 
					array(
						'label' => 'Город',
						'required' => false,
					 	'class' => 'MasterCoreBundle:Cities',
						'query_builder' => function($repository) use ($district1) {
					 		return $repository->createQueryBuilder('p')->where('p.isActive = :active')->setParameter('active', true)->orderBy('p.name', 'ASC')->orderBy('p.position', 'asc')->addOrderBy('p.name', 'asc'); 
						},
						'property' => 'name',
						'data' => $city,
						'empty_value' => '',
					)
				)
				->add('district', 'entity', 
					array(
						'label' => 'Район №1',
						'required' => false,
					 	'class' => 'MasterCoreBundle:Districts',
						'query_builder' => function($repository) use ($district1) {
					 		if($district1) return $repository->createQueryBuilder('p')->where('p.city = :city_id')->setParameter('city_id', $district1->getCity()->getId())->orderBy('p.name', 'ASC')->/*orderBy('p.position', 'asc')->*/addOrderBy('p.name', 'asc'); 
					 		else return $repository->createQueryBuilder('p')->setMaxResults(0);
						},
						'property' => 'name',
						'data' => $district1,
						'empty_value' => '',
					)
				)
				->add('district2', 'entity',
					 array(
					 	'label' => 'Район №2', 
					 	'required' => false,
					 	'class' => 'MasterCoreBundle:Districts',
						'query_builder' => function($repository) use ($district1) {
					 		if($district1) return $repository->createQueryBuilder('p')->where('p.city = :city_id')->setParameter('city_id', $district1->getCity()->getId())->orderBy('p.name', 'ASC')->/*orderBy('p.position', 'asc')->*/addOrderBy('p.name', 'asc'); 
					 		else return $repository->createQueryBuilder('p')->setMaxResults(0);
						},
						'property' => 'name',
						'data' => $district2,
						'empty_value' => '',
					 )
				)
				->add('metro', 'entity', 
					array(
						'label' => 'Метро - тест связи',
						'required' => false,
					 	'class' => 'MasterCoreBundle:Metros',
						'query_builder' => function($repository) use ($district1) {
							if($district1) return $repository->createQueryBuilder('p')->where('p.district = :district_id')->setParameter('district_id', $district1->getId())->orderBy('p.name', 'ASC')->/*orderBy('p.position', 'asc')->*/addOrderBy('p.name', 'asc');
							else return $repository->createQueryBuilder('p')->setMaxResults(0);
						},
						'property' => 'name',
					)
				)
				->add('position', null, array('label' => 'Приоритет'))
				->add('comment', null, array('label' => 'Комментарий'))
				->add('isActive', null, array('label' => 'Активность'))
			->end()
			->with('Гео координаты')
				->add('latitude', null, array('label' => 'Широта', 'attr' => array('class' => 'metro_latitdude')))
				->add('longitude', null, array('label' => 'Долгота', 'attr' => array('class' => 'metro_longitude')))
			->end()
		;
		
		
		$formSchema = $this->container->get('form.schema')->newSchema($formMapper->getFormBuilder()); // Creation of FormSchema object
		
		$form_relation1 = $formSchema->relationBuilder()
			->setMaster('city')
			->setSlaves(array('district', 'district2')) // Related select boxes
			->setRetrieveEntity('MasterCoreBundle:Districts')
			->setFindByFieldName('city') // Used in controller to make correct requests
			->setOrderByParams(array('name' => 'ASC'))
			->setQueryBuilder('city') // Name of related field in orm.yml schema
			->setResultFieldName('name'); // Name of field which contain value showing in slave selectbox on update
		
		$form_relation2 = $formSchema->relationBuilder()
			->setMaster('district')
			->setSlave('metro')
			->setRetrieveEntity('MasterCoreBundle:Metros')
			->setFindByFieldName('district')
			->setOrderByParams(array('name' => 'ASC'))
			->setQueryBuilder('district')
			->setResultFieldName('name');
		
		$formSchema->addRelation($form_relation1); // You can add as many relatins as you wish
		$formSchema->addRelation($form_relation2);
		
        $this->container->get('form.schema')->set($formSchema); // Process schema
	}
	
	protected function configureDatagridFilters(DatagridMapper $datagridMapper)
	{
		$datagridMapper
			->add('district.city', null, array('label' => 'Город'))
			->add('district', null, array('label' => 'Район'))
			->add('name', null, array('label' => 'Название'))
			->add('isActive', null, array('label' => 'Активность'))
		;
		
		
		$filterSchema = $this->container->get('form.schema')->newSonataFilter();
		
		$form_relation2 = $filterSchema->relationBuilder()
			->setFilterName('district') // Name of existing filter
			->setEntity('MasterCoreBundle:Districts')
			->setParentRelation('city') // Name of related field in orm.yml schema
			->setOrderByParams(array('name' => 'ASC'));
		
		$form_relation = $filterSchema->relationBuilder()
			->setFilterName('district.city')
			->setEntity('MasterCoreBundle:Cities')
			->setOrderByParams(array('position' => 'ASC', 'name' => 'ASC'))
			->setSlave($form_relation2); // Slave relation object created above
		
		$filterSchema->addRelation($form_relation);
		
		$this->container->get('form.schema')->set($filterSchema, 'sonata_filter'); // 'sonata_filter' in parameter switching to Sonata Filter processing instead of Form Relation
	}

	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('id', null, array('label' => '#'))
			->addIdentifier('name', null, array('label' => 'Название'))
			//->add('district.area', null, array('label' => 'АО'))
			->add('district.city', null, array('label' => 'Город'))
			->add('district', null, array('label' => 'Район'))
			->addIdentifier('isActive', null, array('label' => 'Активность'))
		;
	}
	
	public function getTemplate($name)
	{
		switch ($name) {
        	case 'edit':
	            return 'ApplicationSonataAdminBundle:CRUD:metro_edit.html.twig';
            	break;
        	default:
	            return parent::getTemplate($name);
            	break;
    	}
	}
	
	private function getDistricts($district)
	{
		return array();
	}
}


?>