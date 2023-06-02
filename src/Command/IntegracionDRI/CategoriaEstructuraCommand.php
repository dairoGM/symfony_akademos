<?php

namespace App\Command\IntegracionDRI;


use App\Repository\Estructura\CategoriaEstructuraRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class CategoriaEstructuraCommand extends Command
{

    protected static $defaultName = 'categoria-estructura-command';


    private $dbname;
    private $user;
    private $password;
    private $host;
    private $driver;
    private $connection;
    private $port;
    private $env;
    private $categoriaEstructuraRepository;


    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function __construct(ContainerBagInterface $container, $dbname, $user, $password, $host, $driver, $port, CategoriaEstructuraRepository $categoriaEstructuraRepository)
    {
        parent::__construct();
        $this->env = $container->get('env_config');


        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->driver = $driver;
        $this->port = $port;

        $this->categoriaEstructuraRepository = $categoriaEstructuraRepository;

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
        $this->setDescription('Procedimiento que sincroniza las categorias de estructuras de academos hacia la base de datos del nucleo de DRI');
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

        $registrosLocales = $this->categoriaEstructuraRepository->findBy(['activo' => true]);
        $this->connection->update('sq_estructura_composicion.tb_ncategoria_estructura', ['activo' => 0], ['activo' => 1]);

        if (count($registrosLocales) > 0) {
            foreach ($registrosLocales as $value) {
                $nombre = $value->getNombre();
                $existe = $this->connection->fetchAllAssociative("SELECT * FROM sq_estructura_composicion.tb_ncategoria_estructura WHERE nombre_categoria_estructura = '$nombre'");
                if (!isset($existe[0])) {
                    $data['nombre_categoria_estructura'] = $value->getNombre();
                    $data['descripcion_categoria_estructura'] = $value->getDescripcion();
                    $data['color'] = $value->getColor();
                    $data['activo'] = 1;
                    $this->connection->insert('sq_estructura_composicion.tb_ncategoria_estructura', $data);
                } else {
                    $this->connection->update('sq_estructura_composicion.tb_ncategoria_estructura', ['activo' => 1], ['id_categoria_estructura' => $existe[0]['id_categoria_estructura']]);
                }
            }
        }

        $duration = round((microtime(true) - $tiempo_inicial), 2) . 's';
        $this->io->success(date('d-m-Y H:i:s') . ': End Proccess');
        $this->io->success('Durations: ' . $duration);
        return 0;
    }

}
