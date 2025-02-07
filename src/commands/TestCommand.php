<?php
declare(strict_types=1);

namespace Src\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command
{
  public function __construct()
  {
    parent::__construct();
  }

  protected function configure(): void
  {
    $this->setName('app:project-test-command')
      ->setDescription('Test command for Project');
  }

  public function execute(InputInterface $input, OutputInterface $output): int
  {
    $output->write('Command Successful', true);
    return Command::SUCCESS;
  }
}
