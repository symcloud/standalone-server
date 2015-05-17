<?php

namespace Symcloud\Application\BlobStorage\Command;

use Ratchet;
use React;
use Symcloud\Component\Standalone\Silex\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    /**
     * @var Application
     */
    private $app;

    /**
     * RunCommand constructor.
     * @param string $name
     * @param Application $app
     */
    public function __construct($name, Application $app)
    {
        parent::__construct($name);

        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->addArgument('port', null, '', 9876);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $port = $input->getArgument('port');
        $output->writeln('Connect to ws://localhost:' . $port);

        $loop = React\EventLoop\Factory::create();
        $socket = new React\Socket\Server($loop);
        $http = new React\Http\Server($socket);

        $app = $this->app;
        $http->on(
            'request',
            function ($request, $response) use ($app) {
                $app($request, $response);
            }
        );
        $socket->listen($port);
        $loop->run();
    }
}
