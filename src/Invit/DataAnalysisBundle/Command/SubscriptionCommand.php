<?php

namespace Invit\DataAnalysisBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubscriptionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('invit:data-analysis:subscription')
            ->setDescription('runs subscribed jobs and sends notifications')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $subscription = $this->getContainer()->get('invit_data_analysis.subscription');
        $subscription->sendNotifications();
    }
}