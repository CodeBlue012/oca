<?php
class Magecomp_Fileupload_Block_Adminhtml_Fileupload_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
 	public function __construct()
  	{
      	parent::__construct();
      	$this->setId('fileuploadGrid');
      	$this->setDefaultSort('fileupload_id');
      	$this->setDefaultDir('ASC');
      	$this->setSaveParametersInSession(true);
  	}

  	protected function _prepareCollection()
  	{
      	$collection = Mage::getModel('fileupload/fileupload')->getCollection();
      	$this->setCollection($collection);
      	return parent::_prepareCollection();
  	}

  	protected function _prepareColumns()
  	{
		$this->addColumn('fileupload_id', array(
			'header'    => Mage::helper('fileupload')->__('ID'),
			'align'     =>'right',
			'width'     => '50px',
			'index'     => 'fileupload_id',
		));
  
		$this->addColumn('title', array(
			'header'    => Mage::helper('fileupload')->__('Title'),
			'align'     =>'left',
			'index'     => 'title',
		));
		
		 $this->addColumn('downloads', array(
			'header'    => Mage::helper('fileupload')->__('Total Downloads'),
			'align'     =>'center',
			'index'     => 'downloads',
			'type'      => 'text',
			'width'     => '100px',
		));
		
		 $this->addColumn('file_icon', array(
			'header'    => Mage::helper('fileupload')->__('File Type'),
			'align'     =>'center',
			'index'     => 'file_icon',
			'type'      => 'text',
			'width'     => '50px',
		));
		
		$this->addColumn('file_size', array(
			'header'    => Mage::helper('fileupload')->__('File Size'),
			'align'     =>'left',
			'width'     => '100px',
			'index'     => 'file_size',
		));
		
		$this->addColumn('status', array(
			'header'    => Mage::helper('fileupload')->__('Status'),
			'align'     => 'left',
			'width'     => '80px',
			'index'     => 'status',
			'type'      => 'options',
			'options'   => array(
				1 => 'Enabled',
				2 => 'Disabled',
			),
		));
		
	   $this->addColumn('download_link', array(
			'header'    => Mage::helper('fileupload')->__('Download'),
			'align'     =>'center',
			'index'     => 'download_link',
			'type'      => 'text',
			'width'     => '50px',
		));
		
		$this->addColumn('action',
			array(
				'header'    =>  Mage::helper('fileupload')->__('Action'),
				'width'     => '100',
				'type'      => 'action',
				'getter'    => 'getId',
				'actions'   => array(
					array(
						'caption'   => Mage::helper('fileupload')->__('Edit'),
						'url'       => array('base'=> '*/*/edit'),
						'field'     => 'id'
					)
				),
				'filter'    => false,
				'sortable'  => false,
				'index'     => 'stores',
				'is_system' => true,
		));
		  
		  
		$this->addExportType('*/*/exportCsv', Mage::helper('fileupload')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('fileupload')->__('XML'));
	  
		return parent::_prepareColumns();
  	}

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('fileupload_id');
        $this->getMassactionBlock()->setFormFieldName('fileupload');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('fileupload')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('fileupload')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('fileupload/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('fileupload')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('fileupload')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  	public function getRowUrl($row)
  	{
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  	}
}