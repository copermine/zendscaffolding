<?php

class BooksController extends Zend_Controller_Scaffolding
{

    protected $fields;

    public function init()
    {
        $this->fields = array(
            'title' => array(
                'search' => true,
                'sort'  => array('default' => 'desc'),
                'listOrder' => 1,
                'editOrder' => 1,
            ),
            'description' => array(
                'fieldType'  => 'richtextarea',
                'rows'  => 5,
                'cols'  => 40,
                'listOrder' => 2,
                'editOrder' => 2,
                'hide' => 'list'
            ),
            'available' => array(
                'fieldType' => 'checkbox',
                'search' => true,
                'listOrder' => 5,
                'editOrder' => 5,
            ),
            // We may need the ID value, so we fetch it.
            'category' => array(
                'hide' => 'list', // do not show as separate column
                'displayField' => 'Category.name', // use alternative column as displayed value for list/edit display
            ),
            // A foreign key field definition.
            // Category is the name of the reference rule from model class.
            // name is the column name that must be fetched
            'Category.name' => array(
                'title' => 'Category',
                'search' => true,
                'sort'  => true,
                'listOrder' => 3,
                'editOrder' => 3,
            ),
            'catalog' => array(
                'hide' => 'list',
                'displayField' => 'Catalog.name',
                'search' => array('empty' => true, 'emptyLabel' => 'Not catalogued'),
            ),
            'Catalog.name' => array(
                'title' => 'Catalog',
                'sort'  => true,
                'listOrder' => 4,
                'editOrder' => 4,
            ),
            'created' => array(
                'hide' => 'edit',
                'listOrder' => 7,
                'editOrder' => 7,
            ),
            'updated' => array(
                'hide' => 'edit',
                'listOrder' => 6,
                'editOrder' => 6,
            )
        );
        $this->scaffold(new Application_Model_Books(), $this->fields, array('csrfProtected' => false, 'entityTitle' => 'book'));
    }

    public function pagerAction() {
        $fields = array();

        $this->scaffold(new Application_Model_Books(), $fields,
                array(
                    'csrfProtected' => false,
                    'indexAction' => true,
                    'pagination' => array('itemsPerPage' => 2)
                ));
    }

    public function readonlyAction() {
        $fields = array();

        $this->scaffold(new Application_Model_Books(), $fields,
                array(
                    'csrfProtected' => false,
                    'indexAction' => true,
                    'readonly' => true
                ));
    }

    public function loadRichTextEditor(array $fields) {
        $this->view->headScript()->appendScript('// RTE Fields: ' . join(',', $fields));
    }

    public function beforeCreate(Zend_Form $form, array &$values) {
        $values['created'] = date('Y-m-d H:i:s');
        return true;
    }

    public function beforeUpdate(Zend_Form $form, array &$values) {
        $values['updated'] = date('Y-m-d H:i:s');
        return true;
    }

    public function beforeDelete(Zend_Db_Table_Row_Abstract $entity) {
        $this->getResponse()->setHeader('Before-Delete', 'OK');
        return true;
    }

    public function afterCreate(Zend_Form $form, $id) {
        $this->getResponse()->setHeader('After-Create', 'OK');
        return true;
    }

    public function afterUpdate(Zend_Form $form) {
        $this->getResponse()->setHeader('After-Update', 'OK');
        return true;
    }

    public function afterDelete(Zend_Db_Table_Row_Abstract $entity) {
        $this->getResponse()->setHeader('After-Delete', 'OK');
        return true;
    }

    function prepareSearchForm(array &$form) {
      // Search for available books by default.
      $form['elements']['available'][1]['value'] = 1;
      return parent::prepareSearchForm($form);
    }
}

