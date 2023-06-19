<?php

namespace App\Command\IntegracionDRI\CargaInicial;


use App\Entity\Estructura\CategoriaEstructura;
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

    protected static $defaultName = 'carga-inicial-categoria-estructura-command';


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
        $this->setDescription('Procedimiento que trae todos los registros de DRI y los actualiza en el sistema');
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

        $sql = "SELECT * FROM sq_estructura_composicion.tb_ncategoria_estructura WHERE id_categoria_estructura > 0";
        $registrosDri = $this->connection->fetchAllAssociative($sql);
        if (is_array($registrosDri)) {
            foreach ($registrosDri as $value) {
                if (!empty($value['id_categoria_estructura'])) {
                    $new = new CategoriaEstructura();
                    $new->setNombre($value['nombre_categoria_estructura']);
                    $new->setDescripcion($value['descripcion_categoria_estructura']);
                    $new->setColor($value['color']);
                    $categoria = $this->categoriaEstructuraRepository->findBy(['nombre'=>$value['nombre_categoria_estructura']]);
                    if (isset($categoria[0])) {
                        $this->categoriaEstructuraRepository->edit($new, true);
                    } else {
                        $this->categoriaEstructuraRepository->add($new, true);
                    }
                }
            }
        }

        $duration = round((microtime(true) - $tiempo_inicial), 2) . 's';
        $this->io->success(date('d-m-Y H:i:s') . ': End Proccess');
        $this->io->success('Durations: ' . $duration);
        return 0;
    }

}
