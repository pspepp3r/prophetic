<?php
declare(strict_types=1);

namespace Src\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ChangeProjectName extends Command
{
  public function __construct()
  {
    parent::__construct();
  }

  protected function configure(): void
  {
    $this->setName('app:change-project-name')
      ->setDescription('Changes the name of the program')
      ->addArgument('new-project-name', InputArgument::REQUIRED, 'The new project name.');
  }

  public function execute(InputInterface $input, OutputInterface $output): int
  {
    $output->write("Project name changed to $input!", true);
    return Command::SUCCESS;
  }
}
