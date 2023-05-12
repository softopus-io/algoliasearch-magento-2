<?php
namespace Algolia\AlgoliaSearch\Controller\Adminhtml\QueueArchive;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var Algolia\AlgoliaSearch\Api\QueueArchiveRepositoryInterface
     */
    protected $_queueArchiveRepository;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var Algolia\AlgoliaSearch\Model\ResourceModel\QueueArchive\ColectionFactory
     */
    protected $_collectionFactory;

    /**
     * @param Action\Context                                                            $context
     * @param Filter                                                                    $filter
     * @param Algolia\AlgoliaSearch\Api\QueueArchiveRepositoryInterface                 $queueArchiveRepository
     * @param Algolia\AlgoliaSearch\Model\ResourceModel\QueueArchive\CollectionFactory   $collectionFactory
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        \Algolia\AlgoliaSearch\Api\QueueArchiveRepositoryInterface $queueArchiveRepository,
        \Algolia\AlgoliaSearch\Model\ResourceModel\QueueArchive\CollectionFactory $collectionFactory
    ) {
        $this->_queueArchiveRepository = $queueArchiveRepository;
        $this->_filter = $filter;
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * Delete Action
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $dataDeleted = 0;
        $collection = $this->_filter->getCollection($this->_collectionFactory->create());
        try {
            foreach ($collection as $item) {
                $this->_queueArchiveRepository->deleteById($item->getArchiveId());
                $dataDeleted++;
            }
            $this->messageManager->addSuccess(__('A total of %1 record(s) have been deleted.', $dataDeleted));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\RuntimeException $e) {
            $this->messageManager->addError($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong'));
        }
        return $resultRedirect->setPath('*/*/');
    }
}   