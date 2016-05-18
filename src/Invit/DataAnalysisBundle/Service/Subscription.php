<?php

namespace Invit\DataAnalysisBundle\Service;

class Subscription
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $slackMessenger;
    private $queryExecutor;
    private $router;
    private $logger;

    public function __construct($entityManager, $slackMessenger, $queryExecutor, $router, $logger)
    {
        $this->em = $entityManager;
        $this->slackMessenger = $slackMessenger;
        $this->queryExecutor = $queryExecutor;
        $this->router = $router;
        $this->logger = $logger;

        return $this;
    }

    public function sendNotifications()
    {
        $subscriptions = $qb = $this->em->getRepository(
            'InvitDataAnalysisBundle:DataAnalysisQuerySubscription'
        )->getScheduledSubscriptions();

        foreach ($subscriptions as $subscription) {
            $result = $this->queryExecutor->execute($subscription->getQuery(), $subscription->getParameterValuesArray());

            $resultHash = md5(serialize($result));

            if ($resultHash !== $subscription->getResultHash()) {
                $url = $this->router->generate('admin_invit_dataanalysis_dataanalysisquery_execute', array_merge(['id' => $subscription->getQuery()->getId()], $subscription->getParameterValuesArray()), true);
                $response = $this->slackMessenger->message(
                    $subscription->getChannel(),
                    sprintf('Änderung in "%s" - %s', $subscription->getQuery()->getTitle(), $url),
                    'Automator'
                );

                if ($response->getStatus() !== false) {
                    $subscription->setResultHash($resultHash);
                    $this->em->persist($subscription);
                    $this->em->flush();
                }
            }
        }
    }
}
