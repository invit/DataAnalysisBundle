<?php

namespace Invit\DataAnalysisBundle\Controller;

use Exporter\Source\DoctrineDBALConnectionSourceIterator;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Sonata\AdminBundle\Export\Exporter;

class DataAnalysisQueryAdminController extends CRUDController
{

    /**
     * @param $id
     * @return array
     * @throws AccessDeniedException
     */
    public function executeQueryAction($id)
    {
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $dataAnalysisQuery = $this->admin->getObject($id);

        if (false === $this->admin->isGranted('EDIT')) {
            throw new AccessDeniedException();
        }
        if (!$dataAnalysisQuery) {
            return new $this->createNotFoundException();
        }

        $conn = $this->container->get('doctrine.dbal.select_only_connection');
        $result = $conn->query($dataAnalysisQuery->getQuery())->fetchAll(\PDO::FETCH_ASSOC);

        return $this->render("InvitDataAnalysisBundle:Query:result.html.twig", array(
            'action' => 'executeQuery',
            'object' => $dataAnalysisQuery,
            'result' => $result
        ));
    }

    public function exportQueryAction($id, $format){
        $id = $this->get('request')->get($this->admin->getIdParameter());
        $dataAnalysisQuery = $this->admin->getObject($id);

        if (false === $this->admin->isGranted('EDIT')) {
            throw new AccessDeniedException();
        }
        if (!$dataAnalysisQuery) {
            return new $this->createNotFoundException();
        }

        $iterator = new DoctrineDBALConnectionSourceIterator($this->container->get('doctrine.dbal.select_only_connection'), $dataAnalysisQuery->getQuery());

        $exporter = new Exporter();
        return $response = $exporter->getResponse($format, date('Y-m-d').'_'.$dataAnalysisQuery->getId().'.'.$format, $iterator);
    }
}
