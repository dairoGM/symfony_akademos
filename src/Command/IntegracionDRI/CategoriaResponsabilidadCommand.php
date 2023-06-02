<?php

namespace App\Command\IntegracionDRI;


use App\Repository\Estructura\CategoriaResponsabilidadRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class CategoriaResponsabilidadCommand extends Command
{

    protected static $defaultName = 'categoria-responsabilidad-command';


    private $dbname;
    private $user;
    private $password;
    private $host;
    private $driver;
    private $connection;
    private $port;
    private $env;
    private $categoriaResponsabilidadRepository;


    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function __construct(ContainerBagInterface $container, $dbname, $user, $password, $host, $driver, $port, CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository)
    {
        parent::__construct();
        $this->env = $container->get('env_config');


        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->driver = $driver;
        $this->port = $port;

        $this->categoriaResponsabilidadRepository = $categoriaResponsabilidadRepository;

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
        $this->setDescription('Procedimiento sincroniza las categorias de responsabilidad de academos hacia la base de datos del nucleo de DRI');
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

        $registrosLocales = $this->categoriaResponsabilidadRepository->findBy(['activo' => true]);
        if (count($registrosLocales) > 0) {
            foreach ($registrosLocales as $value) {
                $nombre = $value->getNombre();
                $existe = $this->connection->fetchAllAssociative("SELECT * FROM sq_estructura_composicion.tb_ncategoria_responsabilidad WHERE nombre_categoria_responsabilidad = '$nombre'");
                if (!isset($existe[0])) {
                    $data['nombre_categoria_responsabilidad'] = $value->getNombre();
                    $data['descripcion'] = $value->getDescripcion();
                    $this->connection->insert('sq_estructura_composicion.tb_ncategoria_responsabilidad', $data);
                }
            }
        }

        $duration = round((microtime(true) - $tiempo_inicial), 2) . 's';
        $this->io->success(date('d-m-Y H:i:s') . ': End Proccess');
        $this->io->success('Durations: ' . $duration);
        return 0;
    }

}
