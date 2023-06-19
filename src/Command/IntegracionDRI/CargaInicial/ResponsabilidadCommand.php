<?php

namespace App\Command\IntegracionDRI\CargaInicial;


use App\Entity\Estructura\CategoriaResponsabilidad;
use App\Entity\Estructura\Responsabilidad;
use App\Repository\Estructura\CategoriaResponsabilidadRepository;
use App\Repository\Estructura\ResponsabilidadRepository;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;


class ResponsabilidadCommand extends Command
{

    protected static $defaultName = 'carga-inicial-responsabilidad-command';


    private $dbname;
    private $user;
    private $password;
    private $host;
    private $driver;
    private $connection;
    private $port;
    private $env;
    private $responsabilidadRepository;
    private $categoriaResponsabilidadRepository;

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws Exception
     */
    public function __construct(ContainerBagInterface $container, $dbname, $user, $password, $host, $driver, $port, ResponsabilidadRepository $responsabilidadRepository, CategoriaResponsabilidadRepository $categoriaResponsabilidadRepository)
    {
        parent::__construct();
        $this->env = $container->get('env_config');


        $this->dbname = $dbname;
        $this->user = $user;
        $this->password = $password;
        $this->host = $host;
        $this->driver = $driver;
        $this->port = $port;

        $this->responsabilidadRepository = $responsabilidadRepository;
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

        $sql = "SELECT * FROM sq_estructura_composicion.tb_nresponsabilidad WHERE id_responsabilidad > 0";
        $registrosDri = $this->connection->fetchAllAssociative($sql);
        if (is_array($registrosDri)) {
            foreach ($registrosDri as $value) {
                if (!empty($value['id_categoria_responsabilidad'])) {
                    $new = new Responsabilidad();
                    $new->setNombre($value['nombre']);
                    $new->setDescripcion($value['descripcion']);
                    $categoria = $this->categoriaResponsabilidadRepository->find($value['id_categoria_responsabilidad']);
                    if ($categoria instanceof CategoriaResponsabilidad){
                        $new->setCategoriaResponsabilidad($categoria);
                    }


                    $responsabilidad = $this->responsabilidadRepository->findBy(['nombre' => $value['nombre'], 'categoriaResponsabilidad' => $value['id_categoria_responsabilidad']]);
                    if (isset($responsabilidad[0])) {
                        $this->responsabilidadRepository->edit($new, true);
                    } else {
                        $this->responsabilidadRepository->add($new, true);
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
