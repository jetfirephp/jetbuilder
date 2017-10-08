<?php

namespace Jet\Commands;

use InvalidArgumentException;
use JetFire\Framework\Commands\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\Common\DataFixtures\Loader;
use Symfony\Component\Console\Question\ConfirmationQuestion;


class LoadFixtures extends Command
{

    protected function configure()
    {
        $this
            ->setName('load:data')
            ->setDescription('Load data into database')
            ->addOption('fixtures', null, InputArgument::OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The directory to load data fixtures from.')
            ->addOption('append', null, InputOption::VALUE_NONE, 'Append the data fixtures instead of deleting all data from the database first.')
            ->addOption('purge-with-truncate', null, InputOption::VALUE_NONE, 'Purge data by using a database-level TRUNCATE statement')
            ->setHelp(<<<EOT
The <info>jet load:data</info> command loads data fixtures from your blocks:
  <info>jet load:data</info>
You can also optionally specify the path to fixtures with the <info>--fixtures</info> option:
  <info>jet load:data --fixtures=/path/to/fixtures1 --fixtures=/path/to/fixtures2</info>
If you want to append the fixtures instead of flushing the database first you can use the <info>--append</info> option:
  <info>jet load:data --append</info>
By default Doctrine Data Fixtures uses DELETE statements to drop the existing rows from
the database. If you want to use a TRUNCATE statement instead you can use the <info>--purge-with-truncate</info> flag:
  <info>jet load:data --purge-with-truncate</info>
EOT
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = 'Data loaded successfully';

        if ($input->isInteractive() && !$input->getOption('append')) {
            if (!$this->askConfirmation($input, $output, '<question>Careful, database will be purged. Do you want to continue y/N ?</question>', false)) {
                return;
            }
        }

        $loader = new Loader();
        $call = $input->getOption('fixtures');
        $paths = [];
        if ($call) {
            $paths = is_array($call) ? $call : array($call);
        } else {
            foreach ($this->app->data['app']['fixtures'] as $dir)
                $paths[] = ROOT . DIRECTORY_SEPARATOR . ltrim($dir, '/');
        }

        foreach ($paths as $path) {
            if (is_dir($path)) {
                $loader->loadFromDirectory($path);
            } elseif (is_file($path)) {
                $loader->loadFromFile($path);
            }
        }
        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
            throw new InvalidArgumentException(
                sprintf('Could not find any fixtures to load in: %s', "\n\n- " . implode("\n- ", $paths))
            );
        }

        $purger = new ORMPurger();
        $purger->setPurgeMode($input->getOption('purge-with-truncate') ? ORMPurger::PURGE_MODE_TRUNCATE : ORMPurger::PURGE_MODE_DELETE);
        $executor = new ORMExecutor($this->app->get('database')->getProvider('doctrine')->em(), $purger);
        $executor->setLogger(function ($message) use ($output) {
            $output->writeln(sprintf('  <comment>></comment> <info>%s</info>', $message));
        });

        $executor->execute($fixtures, $input->getOption('append'));

        return $output->writeln(sprintf('<info>%s</info>', $text));
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $question
     * @param bool $default
     *
     * @return bool
     */
    private function askConfirmation(InputInterface $input, OutputInterface $output, $question, $default)
    {
        if (!class_exists('Symfony\Component\Console\Question\ConfirmationQuestion')) {
            $dialog = $this->getHelperSet()->get('dialog');
            return $dialog->askConfirmation($output, $question, $default);
        }
        $questionHelper = $this->getHelperSet()->get('question');
        $question = new ConfirmationQuestion($question, $default);
        return $questionHelper->ask($input, $output, $question);
    }

} 