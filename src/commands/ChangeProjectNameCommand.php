<?php
declare(strict_types=1);

namespace Src\Commands;

use Src\Services\ConfigService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// TODO: Make changes to class input
class ChangeProjectNameCommand extends Command
{
  public function __construct(private ConfigService $config)
  {
    parent::__construct();
  }

  protected function configure(): void
  {
    $this->setName('app:change-project-name')
      ->setDescription('Changes the name of the application')
      ->addArgument('new-project-name', InputArgument::REQUIRED, 'The new project name.');
  }

  public function execute(InputInterface $input, OutputInterface $output): int
  {
    // The environment file path
    $envFilePath = __DIR__ . '/../../.env';
    // The console config file path
    $consoleFilePath = __DIR__ . '/../../';
    // The name of the project from user input
    $projectName = $input->getArgument('new-project-name');

    //The contents of the current .env file 
    $envFileContent = file_get_contents($envFilePath);

    // The pattern to be searched for - 'APP_NAME=' 
    $pattern = '/^APP_NAME=.*/m';

    // If the project name is null (no input was given, throw an error) - redundant as symfony already handles this.
    if ($projectName === null) {
      throw new \RuntimeException("Argument 'new-project-name' is required in order to execute this command correctly.");
    }

    // If .env file or console config file doesn't exist, throw error
    if (!file_exists($envFilePath)) {
      throw new \RuntimeException('.env file not found');
    } else if (!file_exists($consoleFilePath . strtolower($this->config->get('app.app_name')))) {
      throw new \RuntimeException("Console Configuration file $consoleFilePath not found");
    }

    // If pattern matches, change app name in .env file else attach app_name setting
    if (preg_match($pattern, $envFileContent)) {
      $envFileContent = preg_replace($pattern, "APP_NAME=$projectName", $envFileContent);
      file_put_contents($envFilePath, $envFileContent);
    } else {
      $envFileContent .= PHP_EOL . 'APP_NAME=' . $projectName;
    }

    // store the current console file name to variable
    $consoleFile = $consoleFilePath . $this->config->get('app.app_name');
    // Copy content of current console file to new console file
    copy($consoleFile, (string) $consoleFilePath . strtolower($projectName));
    // If current and new console file names don't match, delete the current one
    if ($consoleFile !== "$consoleFilePath$projectName")
      unlink($consoleFile);

    $output->write("Project name changed to $projectName, please call all commands as `php " . strtolower($projectName) . " <command>` now!", true);

    return Command::SUCCESS;
  }
}
