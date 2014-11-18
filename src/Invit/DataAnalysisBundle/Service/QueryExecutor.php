<?php
namespace Invit\DataAnalysisBundle\Service;


use Invit\DataAnalysisBundle\Entity\DataAnalysisQuery;
use Invit\DataAnalysisBundle\Exception\MissingParameterException;

class QueryExecutor {

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    public function execute(DataAnalysisQuery $dataAnalysisQuery, array $parameters)
    {
        $query = $dataAnalysisQuery->getQuery();
        foreach($dataAnalysisQuery->getParameters() AS $queryParameter){
            if(!isset($parameters[$queryParameter->getName()])){
                throw new MissingParameterException();
            }

            $query = str_replace('$$$'.$queryParameter->getName().'$$$', $parameters[$queryParameter->getName()], $query);
        }

        return $this->connection->query($query)->fetchAll(\PDO::FETCH_ASSOC);
    }
} 