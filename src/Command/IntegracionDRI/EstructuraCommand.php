<?php

namespace App\Command\IntegracionDRI;


use App\Entity\Estructura\CategoriaEstructura;
use App\Entity\Estructura\Estructura;
use App\Repository\Estructura\CategoriaEstructuraRepository;
use App\Repository\Estructura\EstructuraRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class EstructuraCommand extends Command
{

    protected static $defaultName = 'estructura-command';


    private $dbname;
    private $user;
    private $password;
    private $host;
    private $driver;
    private $connection;
    private $port;
    private $env;
    private $estructuraRepository;
    private $categoriaEstructuraRepository;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function __construct(ContainerBagInterface $container, $dbname, $user, $password, $host, $driver, $port, EstructuraRepository $estructuraRepository, CategoriaEstructuraRepository $categoriaEstructuraRepository)
    {
        parent::__construct();
        $this->env = $container->get('env_config');


        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->driver = $driver;
        $this->port = $port;

        $this->estructuraRepository = $estructuraRepository;
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
        $this->setDescription('Procedimiento que sincroniza las estructuraes de academos hacia la base de datos del nucleo de DRI');
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

        $registrosLocales = $this->estructuraRepository->findBy(['activo' => true]);

        $this->connection->update('sq_estructura_composicion.tb_destructura', ['activo' => 0], ['activo' => true]);
        if (count($registrosLocales) > 0) {
            foreach ($registrosLocales as $value) {
                $nombre = $value->getNombre();
                $existe = $this->connection->fetchAllAssociative("SELECT * FROM sq_estructura_composicion.tb_destructura WHERE nombre_estructura = '$nombre'");
                $data['nombre_estructura'] = $value->getNombre();
                $data['siglas'] = $value->getSiglas();
                $data['telefono'] = $value->getTelefono();
                $data['ubicacion'] = $value->getUbicacion();
                $data['correo_electronico'] = $value->getEstructura();
                $data['id_categoria_estructura'] = $value->getCategoriaEstructura()->getId();
                $data['codigo_estructura'] = $value->getSiglas();
                $data['fecha_activacion'] = $value->getFechaActivacion()->format('Y-m-d');
                $data['activo'] = 1;

                if (!isset($existe[0])) {
                    $this->connection->insert('sq_estructura_composicion.tb_destructura', $data);
                } else {
                    $this->connection->update('sq_estructura_composicion.tb_destructura', $data, ['id_estructura' => $existe[0]['id_estructura']]);
                }
            }
        }
        $duration = round((microtime(true) - $tiempo_inicial), 2) . 's';
        $this->io->success(date('d-m-Y H:i:s') . ': End Proccess');
        $this->io->success('Durations: ' . $duration);
        return 0;
    }

}
