<?php

namespace App\Command\IntegracionDRI\CargaPeriodica;


use App\Repository\Estructura\CategoriaEstructuraRepository;
use App\Repository\Personal\ProfesionRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class ProfesionCommand extends Command
{

    protected static $defaultName = 'profesiones-command';


    private $dbname;
    private $user;
    private $password;
    private $host;
    private $driver;
    private $connection;
    private $port;
    private $env;
    private $profesionRepository;


    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function __construct(ContainerBagInterface $container, $dbname, $user, $password, $host, $driver, $port, ProfesionRepository $profesionRepository)
    {
        parent::__construct();
        $this->env = $container->get('env_config');


        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->driver = $driver;
        $this->port = $port;

        $this->profesionRepository = $profesionRepository;

        $connectionParams = array(
            'dbname' => $this->dbname,
            'user' => $this->user,
            'password' => $this->password,
            'host' => $this->host,
            'port' => $this->port,
            'driver' => $this->driver,
            'charset' => 'UTF8'
        );
        $this->connection = DriverManager::getConnection($connectionParams);
    }

    protected function configure(): void
    {
        $this->setDescription('Procedimiento que sincroniza las profesiones de academos hacia la base de datos del nucleo de DRI');
    }


    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {

    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tiempo_inicial = microtime(true);
        $this->io->success(date('d-m-Y H:i:s') . ': Start Proccess');

        $registrosLocales = $this->profesionRepository->findBy(['activo' => true]);
        $this->connection->update('sq_gestion_personal.tb_nprofesion', ['activo' => 0], ['activo' => 1]);

        if (count($registrosLocales) > 0) {
            foreach ($registrosLocales as $value) {
                $nombre = $value->getNombre();
                $existe = $this->connection->fetchAllAssociative("SELECT * FROM sq_gestion_personal.tb_nprofesion WHERE nombre_profesion = '$nombre'");
                if (!isset($existe[0])) {
                    $data['nombre_profesion'] = $value->getNombre();
                    $data['descripcion'] = $value->getDescripcion();
                    $data['activo'] = 1;
                    $this->connection->insert('sq_gestion_personal.tb_nprofesion', $data);
                } else {
                    $this->connection->update('sq_gestion_personal.tb_nprofesion', ['activo' => 1], ['id_profesion' => $existe[0]['id_profesion']]);
                }
            }
        }

        $duration = round((microtime(true) - $tiempo_inicial), 2) . 's';
        $this->io->success(date('d-m-Y H:i:s') . ': End Proccess');
        $this->io->success('Durations: ' . $duration);
        return 0;
    }

}
