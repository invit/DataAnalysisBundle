<?php

namespace Invit\DataAnalysisBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Invit\DataAnalysisBundle\DBAL\Types\QueryLanguageType;
use Invit\DataAnalysisBundle\Entity\DataAnalysisQuery;
use Invit\DataAnalysisBundle\Entity\DataAnalysisQueryParameter;
use Invit\DataAnalysisBundle\Exception\MissingParameterException;
use Sonata\AdminBundle\Admin\Pool;

class QueryExecutor
{
    private $connection;
    private $em;
    private $adminPool;

    public function __construct(Connection $connection, EntityManagerInterface $em, Pool $adminPool)
    {
        $this->connection = $connection;
        $this->em = $em;
        $this->adminPool = $adminPool;

        return $this;
    }

    /**
     * @param DataAnalysisQuery $dataAnalysisQuery
     * @param DataAnalysisQueryParameter[] $parameters
     *
     * @return array|void
     * @throws MissingParameterException
     * @throws \Doctrine\DBAL\DBALException
     */
    public function execute(DataAnalysisQuery $dataAnalysisQuery, array $parameters)
    {
        $query = $dataAnalysisQuery->getQuery();

        foreach ($dataAnalysisQuery->getParameters() as $queryParameter) {
            if ( ! isset($parameters[$queryParameter->getName()])) {
                throw new MissingParameterException();
            }
        }

        switch ($dataAnalysisQuery->getQueryLanguage()) {
            case QueryLanguageType::DQL:
                $doctrineQuery = $this->em->createQuery($query);

                foreach ($dataAnalysisQuery->getParameters() as $queryParameter) {
                    $doctrineQuery->setParameter($queryParameter->getName(), $parameters[$queryParameter->getName()]);
                }

                $result = $doctrineQuery->getResult();

                $formattedResult = [];
                foreach ($result as &$entry) {
                    if (is_object($entry)) {
                        $formattedResult[][] = $this->getObjectCell($entry);
                    } elseif (is_array($entry)) {
                        $entry[0] = $this->getObjectCell($entry[0]);
                        $formattedResult = $result;
                    }
                }

                return $formattedResult;
            case QueryLanguageType::SQL:
                foreach ($dataAnalysisQuery->getParameters() as $queryParameter) {
                    $query = str_replace(
                        '$$$'.$queryParameter->getName().'$$$',
                        $parameters[$queryParameter->getName()],
                        $query
                    );
                }
                return $this->connection->query($query)->fetchAll(\PDO::FETCH_ASSOC);
        }
        return;
    }

    /**
     * @param $object
     *
     * @return string
     */
    private function getObjectCell($object)
    {
        $admin = $this->adminPool->getAdminByClass(get_class($object));
        if ($admin !== null) {
           return $admin->generateUrl('edit', ['id' => $object->getId()], true);
        } else {
            return get_class($object->__toString());
        }
    }
}
