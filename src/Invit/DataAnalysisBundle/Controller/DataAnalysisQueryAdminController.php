<?php

namespace Invit\DataAnalysisBundle\Controller;

use Exporter\Source\DoctrineDBALConnectionSourceIterator;
use Invit\DataAnalysisBundle\Exception\MissingParameterException;
use Invit\DataAnalysisBundle\Form\Type\DataAnalysisQueryParameterType;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sonata\AdminBundle\Export\Exporter;

class DataAnalysisQueryAdminController extends CRUDController
{
    /**
     * @param $id
     *
     * @return array
     *
     * @throws AccessDeniedException
     */
    public function executeQueryAction(Request $request, $id)
    {
        $id = $request->get($this->admin->getIdParameter());
        $dataAnalysisQuery = $this->admin->getObject($id);

        if (false === $this->admin->isGranted('EDIT')) {
            throw new AccessDeniedException();
        }
        if (!$dataAnalysisQuery) {
            throw $this->createNotFoundException();
        }

        try {
            $executor = $this->get('invit_data_analysis.query_executor');
            $result = $executor->execute($dataAnalysisQuery, $request->query->all());
        } catch (\Exception $e) {
            if (!$e instanceof MissingParameterException) {
                $this->get('logger')->addCritical('data-analysis: '.$e->getMessage(), (array) $e->getTrace());
                $this->addFlash('error', $e->getMessage());
            }

            return $this->redirect($this->admin->generateObjectUrl('setQueryParameter', $dataAnalysisQuery, ['error' => true]));
        }

        return $this->render('InvitDataAnalysisBundle:Query:result.html.twig', array(
            'action' => 'executeQuery',
            'object' => $dataAnalysisQuery,
            'result' => $result,
        ));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function setQueryParameterAction(Request $request, $id)
    {
        $id = $request->get($this->admin->getIdParameter());
        $dataAnalysisQuery = $this->admin->getObject($id);

        if (false === $this->admin->isGranted('EDIT')) {
            throw new AccessDeniedException();
        }
        if (!$dataAnalysisQuery) {
            throw $this->createNotFoundException();
        }
        if ($dataAnalysisQuery->getParameters()->count() < 1) {
            if ($request->get('error')) {
                return $this->redirect($this->admin->generateObjectUrl('edit', $dataAnalysisQuery));
            }

            return $this->redirect($this->admin->generateObjectUrl('executeQuery', $dataAnalysisQuery));
        }

        $form = $this->createForm(new DataAnalysisQueryParameterType(), [], ['queryObject' => $dataAnalysisQuery]);

        $form->handleRequest($this->getRequest());

        if ($form->isSubmitted() && $form->isValid()) {
            $parameters = [];
            foreach ($form->getData() as $field => $value) {
                if ($value instanceof \DateTime) {
                    $parameters[$field] = $value->format('Y-m-d');
                } elseif (is_object($value)) {
                    $parameters[$field] = $value->getId();
                } else {
                    $parameters[$field] = $value;
                }
            }

            return $this->redirect($this->admin->generateObjectUrl('executeQuery', $dataAnalysisQuery, $parameters));
        }

        return $this->render('InvitDataAnalysisBundle:Query:parameter_form.html.twig', array(
            'form' => $form->createView(),
            'action' => 'setQueryParameter',
            'object' => $dataAnalysisQuery,
        ));
    }

    /**
     * @param $id
     * @param $format
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     *
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function exportQueryAction(Request $request, $id, $format)
    {
        $id = $request->get($this->admin->getIdParameter());
        $dataAnalysisQuery = $this->admin->getObject($id);

        if (false === $this->admin->isGranted('EDIT')) {
            throw new AccessDeniedException();
        }
        if (!$dataAnalysisQuery) {
            throw $this->createNotFoundException();
        }

        $query = $dataAnalysisQuery->getQuery();
        foreach ($dataAnalysisQuery->getParameters() as $queryParameter) {
            if (null !== $this->getRequest()->get($queryParameter->getName())) {
                $query = str_replace('$$$'.$queryParameter->getName().'$$$', $this->getRequest()->get($queryParameter->getName()), $query);
            } else {
                return $this->redirect($this->admin->generateObjectUrl('setQueryParameter', $dataAnalysisQuery));
            }
        }

        $iterator = new DoctrineDBALConnectionSourceIterator($this->container->get('doctrine.dbal.select_only_connection'), $query);

        $exporter = new Exporter();

        return $response = $exporter->getResponse($format, date('Y-m-d').'_'.$dataAnalysisQuery->getId().'.'.$format, $iterator);
    }
}
