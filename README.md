Form schema bundle 1 (build relations between entity selectboxes)
========

 This bundle allow to add schema to any form, which has relative entities, described as "entity" types.
 This service just need to access formBuilder of appropriate form.
 At view level slave selectboxes will only contain values, constrained by master selectbox.
 Form will pass validation and value of slave select boxes will be changed automatically when master selectbox changing.
 There is a controller to retrieve values for slaves and it is done automatically.
 Parameters for each ajax request is stored in current user session, and each form element has unique html class name.
 
 This is most actual in Sonata Admin, when user edit entity and it has depended properties related as Country -> Region -> City -> Subway station
 in this case user may have many cities and metro stations, so more useful to show only actual stations if some city is selected,
 instead of showing all list.


Authority
---------

 Gleb Tiltikov <gleb@netstar.od.ua>
 
 https://github.com/GlebNetstar


License
-------

 By MIT License


Version
-------

 1 alpha


Dependencies
------------

 - Symfony **2.1** or newer
 - jQuery
 - FOS\JsRoutingBundle
 - Doctrine


Installation
------------

  - Enable bundle in kernel:
``` php
 	new Netstar\FormSchemaBundle\NetstarFormSchemaBundle(),
```
 
  - Add to autoload;
 
  - Add following lines to twig section in config.yml:
``` php
 	twig:
	    form:
        	resources:
	            - 'NetstarFormSchemaBundle:Form:fields.html.twig'
```
  - Don't forget to regenerate and include JsRoutingBundle routes
``` html
  <script type="text/javascript" src="{{ asset('js/fos_js_routes.js') }}"></script> 
```
            
  - If you want to use FormRelationSonataAdminFilter you need to override template
    ``standard_layout.html.twig`` in SonataAdmin and add to javascript block:
``` html
  {% include 'NetstarFormSchemaBundle::sonata_filters.html.twig' %}
```

Usage
-----

 Have a look at Admin/ExampleAdmin.php
 
 Get schema service:
 
``` php
 $formSchema = $this->container->get('form.schema')->newSchema($formMapper->getFormBuilder()); // Creation of FormSchema object
 
 // As the parameter you have to pass formBuilder of appropriate form.
 
 $form_relation1 = $formSchema->relationBuilder()
	->setMaster('city')
	->setSlaves(array('district', 'district2')) // Related select boxes
	->setRetrieveEntity('MasterCoreBundle:Districts')
	->setFindByFieldName('city') // Used in controller to make correct requests
	->setOrderByParams(array('name' => 'ASC'))
	->setQueryBuilder('city') // Name of related field in orm.yml schema
	->setResultFieldName('name'); // Name of field which contain value showing in slave selectbox after update
	
 $formSchema->addRelation($form_relation1); // You can add as many relatins as you wish, they also can contain subrelations.
 
 $this->container->get('form.schema')->set($formSchema); // Process schema
```
 
 
 
 To make FormRelationSonataAdminFilter you have to do following:
 
``` php
 $filterSchema = $this->container->get('form.schema')->newSonataFilter(); // Creation of SonataFilter object
		
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
 
 $this->container->get('form.schema')->set($filterSchema, 'sonata_filter'); // 'sonata_filter' string in parameter is to switch Sonata Filter processing instead of Form Relation
```


Screenshots
------------

 - **Form Schema** in Sonata Admin there is one city selected, only related regions is listed http://symfony.netstar.odessa.ua/FormSchema1.jpg
 - **SonataAdminFilter** cities and regions dependency http://symfony.netstar.odessa.ua/FormRelationSonataAdminFilter1.jpg , http://symfony.netstar.odessa.ua/FormRelationSonataAdminFilter2.jpg
 

Known issues
------------

 - In Sonata, if object with relation is using as collection, problem with label field translation in dev environment.
 
 Now to solve this issue, you have to override SonataDoctrineORMAdminBundle by creating for example ApplicationSonataDoctrineORMAdminBundle.
 
 Then make changes at config.yml :
``` php
 sonata_doctrine_orm_admin:
    templates:
        form:
            - ApplicationSonataDoctrineORMAdminBundle:Form:form_admin_fields.html.twig 
```
 and create and override template DoctrineORMAdminBundle / Resources / views / CRUD / edit_orm_one_to_many.html.twig
 then modify string; number with error you can see in Symfony 2 debug page.



TODO
----

 - Check and improve to Symfony 2.2 support (& Sonata 2.1), perhaps it already in accordance 
 


Please don't hesitate to contact me by any question or with the bug reports!

Email: gleb@netstar.od.ua

Facebook: http://www.facebook.com/glebnetstar


