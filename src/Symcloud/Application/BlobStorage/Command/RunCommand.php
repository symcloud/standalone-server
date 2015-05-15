<?php

namespace Symcloud\Application\BlobStorage\Command;

use Ratchet;
use React;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /**
     * @var Ratchet\MessageComponentInterface
     */
    private $messageComponent;

    /**
     * RunCommand constructor.
     * @param Ratchet\MessageComponentInterface $messageComponent
     */
    public function __construct(Ratchet\MessageComponentInterface $messageComponent)
    {
        parent::__construct('run');

        $this->messageComponent = $messageComponent;
    }

    protected function configure()
    {
        $this->setName('run')
            ->addArgument('port', null, '', 9876);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument('port');
        $output->writeln('Connect to ws://localhost:' . $port);

        $loop = React\EventLoop\Factory::create();
        $app = new Ratchet\App('localhost', $port, '127.0.0.1', $loop);
        $app->route('/blob', $this->messageComponent, array('*'));
        $app->route('/echo', new Ratchet\Server\EchoServer, array('*'));
        $app->run();
    }
}
