<?php


namespace App\Command\Install;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Stopwatch\Stopwatch;


class AppInstallFunCommand extends Command
{
    // to make your command lazily loaded, configure the $defaultName static property,
    // so it will be instantiated only when the command is actually called.
    protected static $defaultName = 'app:functionalities';    

    /**
     * @var SymfonyStyle
     */
    private $io;

    private InstallFunctionalityInterface $install;

    private $funcionalities = array();

    public function __construct(InstallFunctionalityInterface $install)
    {
        parent::__construct();
        $this->install = $install;       
    }

    /**
     * {@inheritdoc}
     * 
     * Mode: create / update / disable / delete
     * create  -> Instala una nueva funcionaldad
     * update  -> Actualiza la funcionalidad     
     * disable -> Deshabilita la funcionalidad
     * delete  -> Elimina la funciolnalidad
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Install functionalities')
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
        $this->loadFunctionalities();
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
        
        while($mode != 'create' && $mode != 'update' && $mode != 'disable' && $mode != 'delete'){
            $mode = $this->io->ask('Mode (create / update / disable / delete)', null, null);
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
        $stopwatch->start('install-funct');

        $mode = $input->getArgument('mode');  
        $this->io->text(' > <info>Install mode</info>: ' . $mode);

        switch($mode){
            case "create":
                    $cant = $this->create();
                break;
            case "update":
                    $cant = $this->update();
                break;
            case "delete":
                    $cant = $this->delete();
                break;
            case "disable":
                    $cant = $this->disable();
                break;          
        }   

        $event = $stopwatch->stop('install-funct');
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
        foreach($this->funcionalities as $key => $functionality)
        {               
            $key = $functionality->getRoleKey();
            $name = $functionality->getName(); 

            if(!$this->install->getExistFuncionality($functionality->getRoleKey()))
            {            
                $this->io->comment("<info>Instalando nueva funcionalidad</info> $key => $name");
                $this->install->create($functionality);
                $new++;
            }
        }

        $this->io->comment("<info>Total de funcionalidades nuevas instaladas: </info> $new");
    }

    /**
     * Comando update
     * Instala las nuevas funcionalidades
     * Actualiza funcionalidades sin las encuentra
     *
     * @return void
     */
    private function update() : void
    {
        $new = 0;
        $upd = 0;
        foreach($this->funcionalities as $key => $functionality)
        {               
            $key = $functionality->getRoleKey();
            $name = $functionality->getName(); 

            if(!$this->install->getExistFuncionality($functionality->getRoleKey()))
            {            
                $this->io->comment("<info>Instalando nueva funcionalidad</info> $key => $name");
                $this->install->create($functionality);
                $new++;
            }
            else
            {
                $this->io->comment("<info>Actualizando funcionalidad</info> $key => $name");
                $this->install->update($functionality);
                $upd++;
            }
        }

        $this->io->comment("<info>Total de funcionalidades nuevas instaladas: </info> $new");
        $this->io->comment("<info>Total de funcionalidades actualizadas: </info> $upd");
    }

    /**
     * Comando delete
     * Elimina las funcionalidades obsoletas (las no encontradas en la definicion o carga inicial)
     *
     * @return void
     */
    private function delete()
    {       
        $cant = 0;
        $currentInstall = $this->install->getAllRoleKeyInstaledFuncionalities();
        $roleKeys = array();
        foreach($this->funcionalities  as $key => $functionality)
        {             
            $roleKeys[] = $functionality->getRoleKey();
        }

        foreach($currentInstall as $roleKey)
        {               
            if(!in_array($roleKey, $roleKeys))
            {                
                $this->install->delete(($roleKey));
                $cant++;
               
                $this->io->comment("<info>Desinstalando (eliminado) funcionalidad</info> $roleKey");   
            }
        } 

        $this->io->comment("<info>Total de funcionalidades eliminadas: </info> $cant");
    }

    /**
     * Comando disable
     * Desahiblita las funcionalidades obsoletas (las no encontradas en la definicion o carga inicial)
     *
     * @return void
     */
    private function disable()
    {     
        $cant = 0;
        $currentInstall = $this->install->getAllRoleKeyInstaledFuncionalities();
        $roleKeys = array();
        foreach($this->funcionalities  as $key => $functionality)
        {             
            $roleKeys[] = $functionality->getRoleKey();
        }

        foreach($currentInstall as $roleKey)
        {               
            if(!in_array($roleKey, $roleKeys))
            {                
                $this->install->disable(($roleKey));
                $cant++;

                $this->io->comment("<info>Desinstalando (deshabilitando) funcionalidad</info> $key");      
            }     
        }               

        $this->io->comment("<info>Total de funcionalidades deshabilitadas: </info> $cant");
    }
    
    /**
     * Load defined functionalities
     *
     * @return void
     */
    private function loadFunctionalities()
    {
        $list = $this->install->defineFunctionalities();        

        foreach($list as $functionality)
        {
            $this->addFunctionality($functionality);
        }
    }

    private function addFunctionality($functionality): void
    {        
        $roleKey = $functionality->getRoleKey();       

        if(strlen($roleKey) < 6 && substr($roleKey, 0, 5) != "ROLE_")
        {
            throw new RuntimeException("El roleKey '$roleKey' debe iniciar con 'ROLE_'");
        }

        if(array_key_exists($roleKey, $this->funcionalities))
        {
            throw new RuntimeException("El roleKey '$roleKey'  ya está en uso.");
        }      
        
        $this->funcionalities[$functionality->getRoleKey()] = $functionality;
    }
}
