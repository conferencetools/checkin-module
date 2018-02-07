<?php

namespace ConferenceTools\Checkin\Controller;

use Carnage\Cqrs\Command\CommandBusInterface;
use Carnage\Cqrs\MessageBus\MessageBusInterface;
use Carnage\Cqrs\Persistence\ReadModel\RepositoryInterface;
use ConferenceTools\Checkin\Domain\Command\Delegate\CheckInDelegate;
use ConferenceTools\Checkin\Form\SearchForm;
use Doctrine\Common\Collections\Criteria;
use Zend\Form\Form;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CheckInController extends AbstractActionController
{
    /**
     * @var CommandBusInterface
     */
    private $commandBus;
    /**
     * @var RepositoryInterface
     */
    private $repository;

    public function __construct(MessageBusInterface $commandBus, RepositoryInterface $repository)
    {
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }

    public function searchAction()
    {
        $form = new SearchForm();
        $form->init();
        $results = [];

        if ($this->getRequest()->isPost()) {
            $data = $this->params()->fromPost();
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $data['purchaserEmail'] = $data['email'];

                $criteria = Criteria::create();

                foreach ($data as $key => $search) {
                    if (empty($search)) {
                        continue;
                    }

                    $criteria->orWhere(Criteria::expr()->contains($key, $search));
                }

                $results = $this->repository->matching($criteria);
            }
        }

        return new ViewModel(['form' => $form, 'results' => $results]);
    }

    public function checkinAction()
    {
        try {
            $delegateId = $this->params()->fromRoute('delegateId');
            $this->commandBus->dispatch(new CheckInDelegate($delegateId));
            $this->flashMessenger()->addInfoMessage('Delegate checked in');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage($e->getMessage());
        }

        return $this->redirect()->toRoute('checkin');
    }
}