<?php


namespace App\Command\Install;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;


class AppInstallRoleCommand extends Command
{
    // to make your command lazily loaded, configure the $defaultName static property,
    // so it will be instantiated only when the command is actually called.
    protected static $defaultName = 'app:roles';    

    /**
     * @var SymfonyStyle
     */
    private $io;

    private InstallRoleInterface $install;

    private $roles = array();

    public function __construct(InstallRoleInterface $install)
    {
        parent::__construct();
        $this->install = $install;       
    }

    /**
     * {@inheritdoc}
     * 
     * Mode: create / update / disable / delete
     * create/update  -> Instala un nuevo modulo
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Install roles')
            ->addArgument('mode', InputArgument::REQUIRED, 'Action');            
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

        //Load functionalities
        $this->loadRoles();
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
        // Ask for the mode if it's not defined
        $mode = $input->getArgument('mode');
        
        while($mode != 'create'  && $mode != 'update'){
            $mode = $this->io->ask('Mode (create / update)', null, null);
            $input->setArgument('mode', $mode);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('install-role');

        $mode = $input->getArgument('mode');  
        $this->io->text(' > <info>Install mode</info>: ' . $mode);

        switch($mode){
            case "create":
                    $cant = $this->create();
                break;  
            case "update":
                    $cant = $this->update();
                break;                    
        }   

        $event = $stopwatch->stop('install-role');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('Proceso terminado. / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        return Command::SUCCESS;
    }

    /**
     * Comando crear
     * Instala solamente las nuevas funcionalidades
     *
     * @return void
     */
    private function create() : void
    {
        $new = 0;       
        foreach($this->roles as $key => $role)
        {               
            $key = $role->getRoleKey();
            $name = $role->getName();
            $cant = count($role->getFunctionalitiesKey());

            if(!$this->install->getExistRole($key))
            {            
                $this->io->comment("<info>Instalando nuevo rol</info> $key => $name ($cant funcionalidades)");
                $this->install->create($role);
                $new++;
            }
        }

        $this->io->comment("<info>Total de roles nuevos instalados: </info> $new");
    }

    /**
     * Comando create
     * Instala los nuevos modulos
     * Actualiza moduloas sin los encuentra
     *
     * @return void
     */
    private function update() : void
    {
        $new = 0;
        $upd = 0;
        foreach($this->roles as $key => $role)
        {               
            $key = $role->getRoleKey();
            $name = $role->getName(); 
            $cant = count($role->getFunctionalitiesKey());

            if(!$this->install->getExistRole($key))
            {            
                $this->io->comment("<info>Instalando nuevo rol</info> $key => $name ($cant funcionalidades)");
                $this->install->create($role);
                $new++;
            }
            else
            {
                $this->io->comment("<info>Actualizando rol</info> $key => $name ($cant funcionalidades)");
                $this->install->update($role);
                $upd++;
            }
        }

        $this->io->comment("<info>Total de roles nuevos instalados: </info> $new");
        $this->io->comment("<info>Total de roles actualizados: </info> $upd");
    }

    
    /**
     * Load defined modules
     *
     * @return void
     */
    private function loadRoles()
    {
        $list = $this->install->defineRoles();  
        foreach($list as $role)
        {
            $this->addRole($role);
        }
    }

    private function addRole($role): void
    {        
        $roleKey = $role->getRoleKey();              

        if(array_key_exists($roleKey, $this->roles))
        {
            throw new RuntimeException("El roleKey '$roleKey'  ya está en uso.");
        }      
        
        $this->roles[$roleKey] = $role;
    }
}
