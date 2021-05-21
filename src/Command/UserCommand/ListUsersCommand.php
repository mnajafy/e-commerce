<?php

namespace App\Command\UserCommand;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * A console command that lists all the existing users.
 *
 * To use this command, open a terminal window, enter into your project directory
 * and execute the following:
 *
 *     $ php bin/console app:list-users
 *
 *     $ php bin/console app:list-users --env=test
 *
 * @author Mohammad Najafy <m.najafy@hotmail.com>
 */
class ListUsersCommand extends Command
{
    // a good practice is to use the 'app:' prefix to group all your custom application commands
    protected static $defaultName = 'app:list-users';

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Lists all the existing users')
            ->setHelp('List Users')
            ->addOption('max-results', null, InputOption::VALUE_OPTIONAL, 'Limits the number of users listed', 50)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $maxResults = $input->getOption('max-results');
        // Use ->findBy() instead of ->findAll() to allow result sorting and limiting
        $allUsers = $this->userRepository->findBy([], ['id' => 'DESC'], $maxResults);

        // Doctrine query returns an array of objects and we need an array of plain arrays
        $usersAsPlainArrays = array_map(function (User $user) {
            return [
                $user->getId(),
                $user->getUsername(),
                $user->getEmail(),
                implode(', ', $user->getRoles()),
            ];
        }, $allUsers);
        
        $bufferedOutput = new BufferedOutput();

        (new SymfonyStyle($input, $bufferedOutput))->table(
            ['ID', 'Username', 'Email', 'Roles'],
            $usersAsPlainArrays
        );

        // instead of just displaying the table of users, store its contents in a variable
        $usersAsATable = $bufferedOutput->fetch();
        $output->write($usersAsATable);

        return Command::SUCCESS;
    }
}
