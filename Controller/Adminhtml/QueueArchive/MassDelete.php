<?php
namespace Algolia\AlgoliaSearch\Controller\Adminhtml\Queuearchive;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;
use Algolia\AlgoliaSearch\Model\ResourceModel\QueueArchive\CollectionFactory;
use Algolia\AlgoliaSearch\Model\QueueArchive;

class MassDelete extends Action
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var QueueArchive
     */
    protected $queueArchive;

    /**
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param QueueArchive $queueArchive
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
        QueueArchive $queueArchive
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->queueArchive = $queueArchive;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $jobData = $this->collectionFactory->create();

        foreach ($jobData as $value){
            $templateId[] = $value['id'];
        }
            $parameterData = $this->getRequest()->getParams('id');
            $selectedAppsid = $this->getRequest()->getParams('id');

            if(array_key_exists("selected", $parameterData)){
                $selectedAppsid = $parameterData['selected'];
            }
            if(array_key_exists("excluded", $parameterData)) {
                if ($parameterData['excluded'] == 'false') {
                    $selectedAppsid = $templateId;
                } else {
                    $selectedAppsid = array_diff($templateId, $parameterData['excluded']);
                }
            }
            $collection = $this->collectionFactory->create();
            $collection->addFieldToFilter('archive_id', ['in' => $selectedAppsid]);
            $delete = 0;
            $model = [];
            foreach ($collection as $item){
                $this->deleteById($item->getId());
                $delete++;
            }
            $this->messageManager->addSuccess(__('A total of %1 records have been deleted.', $delete));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('*/*/');
    }

    /**
     * [deleteById description]
     * $param [type] $id [description]
     * $return [type]    [description]
     */
    public function deleteById($id){
        $item = $this->queueArchive->load($id);
        $item->delete();
        return;
    }
}
