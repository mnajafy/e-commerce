<?php

namespace App\Command\UserCommand;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * A console command that creates users and stores them in the database.
 *
 * To use this command, open a terminal window, enter into your project
 * directory and execute the following:
 *
 *     $ php bin/console app:add-user
 * 
 *     $ php bin/console app:create-user --env=test admin admin@e-commerce.com Admin_1234 ROLE_ADMIN
 * 
 * @author Mohammad Najafy <m.najafy@hotmail.com>
 */
class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $passwordEncoder;
    private $validator;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, ValidatorInterface $validator)
    {
        parent::__construct();

        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->validator = $validator;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates users and stores them in the database')
            ->setHelp('This command allows you to create a user...')
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the new user')
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('password', InputArgument::OPTIONAL, 'The plain password of the new user')
            ->addArgument('role', InputArgument::OPTIONAL, 'The role of the new user')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('username') && null !== $input->getArgument('email') && null !== $input->getArgument('password') && null !== $input->getArgument('role')) {
            return;
        }

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:add-user username email@example.com password role',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        // create the user 
        $user = new User();
        $user->setUsername($input->getArgument('username'))
            ->setEmail($input->getArgument('email'))
            ->setRoles([$input->getArgument('role')])
            ->setPassword($input->getArgument('password'));

        $errors = $this->validator->validate($user);
        
        if (count($errors) > 0) {
            throw new RuntimeException($errors);
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $input->getArgument('password')));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        
        $this->io->success('Created user => username: ' . $user->getUsername() . ', email: ' . $user->getEmail() . ', roles: ' . implode(', ', $user->getRoles()));

        $event = $stopwatch->stop('add-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }
}