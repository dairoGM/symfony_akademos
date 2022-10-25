<?php


namespace App\Command;


use App\Entity\Security\User;
use App\Repository\Security\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;


class AddUserCommand extends Command
{
    // to make your command lazily loaded, configure the $defaultName static property,
    // so it will be instantiated only when the command is actually called.
    protected static $defaultName = 'app:add-user';

    /**
     * @var SymfonyStyle
     */
    private $io;

    private $entityManager;
    private $passwordEncoder;
    private $users;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder, UserRepository $users)
    {
        parent::__construct();
        $this->entityManager = $em;
        $this->passwordEncoder = $encoder;
        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Creates users and stores them in the database')
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the new user')
            ->addArgument('password', InputArgument::REQUIRED, 'The plain password of the new user')
            ->addArgument('confirPassword', InputArgument::REQUIRED, 'The plain password of the new user')
            ->addArgument('isAdmin', InputArgument::REQUIRED, 'Is admin user ?');
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (null !== $input->getArgument('email') && null !== $input->getArgument('rol') && null !== $input->getArgument('password')) {
            return;
        }

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:add-user email(email@example.com) rol(ROLE_ADMIN or ROLE_USER) password',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);


        // Ask for the email if it's not defined
        $email = $input->getArgument('email');
        if (null !== $email) {
            $this->io->text(' > <info>Email</info>: ' . $email);
        } else {
            $email = $this->io->ask('Email', null, null);
            $input->setArgument('email', $email);
        }

        // Ask for the password if it's not defined
        $password = $input->getArgument('password');
        if (null !== $password) {
            $this->io->text(' > <info>Password</info>: ' . "*********");
        } else {
            $password = $this->io->askHidden('Password (your type will be hidden)', null);
            $input->setArgument('password', $password);
        }


        // Ask for the confirm password if it's not defined
        $confirPassword = $input->getArgument('confirPassword');
        if (null !== $confirPassword) {
            $this->io->text(' > <info>Confirm password</info>: ' . "*********");
        } else {
            $confirPassword = $this->io->askHidden('Confirm password (your type will be hidden)', null);
            $input->setArgument('confirPassword', $confirPassword);
        }

        // Ask for Admin user
        $isAdmin = $input->getArgument('isAdmin');
        if (null !== $isAdmin) {
            $this->io->text(' > <info>Admin</info>: ' . $isAdmin
        );
        } else {
            $confirPassword = $this->io->ask('Is admin user (yes/no) ?', null);
            $input->setArgument('isAdmin', $confirPassword);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        $email = $input->getArgument('email');
        $plainPassword = $input->getArgument('password');
        $confirPassword = $input->getArgument('confirPassword');
        $isAdmin = $input->getArgument('isAdmin');

        // make sure to validate the user data is correct
        $this->validateConfirmPassword($plainPassword, $confirPassword);

        // create the user and encode its password
        $user = new User();
        $user->setEmail($email);   
        $user->setRole('ROLE_USER');
        $role = "Basic";
        if($isAdmin == 'yes')
        {
            $user->setRole('ROLE_ADMIN');
            $role = "Administrator";
        }        
       
        $encodedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encodedPassword);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->io->success(sprintf('%s was successfully created: %s (%s)', $role.' user', $user->getUsername(), $user->getEmail()));

        $event = $stopwatch->stop('add-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return 0;
    }

    private function validateConfirmPassword($plainPassword, $confirPassword): void
    {
        if ($plainPassword != $confirPassword) {
            throw new RuntimeException('Passwords do not match.');
        }
    }

    private function validateUserData($plainPassword, $email, $rol): void
    {
        if (null == $email) {
            throw new RuntimeException('The Mail value cannot be null.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Invalid Mail address.');
        }

        if (null == $rol) {
            throw new RuntimeException('The Rol value cannot be null.');
        }

        if (null == $plainPassword) {
            throw new RuntimeException('The Password value cannot be null.');
        }

        // first check if a user with the same username already exists.
        $existingEmail = $this->users->findOneBy(['email' => $email]);
        if (null !== $existingEmail) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }
}
