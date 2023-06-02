<?php

namespace App\Command\IntegracionDRI;


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

    protected static $defaultName = 'responsabilidad-command';


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
        $this->setDescription('Procedimiento que sincroniza las responsabilidades de academos hacia la base de datos del nucleo de DRI');
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

//        $sql = "SELECT * FROM sq_estructura_composicion.tb_nresponsabilidad WHERE id_responsabilidad > 0";
//        $registrosDri = $this->connection->fetchAllAssociative($sql);
//
//        if (is_array($registrosDri)) {
//            foreach ($registrosDri as $value) {
//                if (!empty($value['id_categoria_responsabilidad'])) {
//                    $new = new Responsabilidad();
//                    $new->setNombre($value['nombre']);
//                    $new->setDescripcion($value['descripcion']);
//                    $categoria = $this->categoriaResponsabilidadRepository->find($value['id_categoria_responsabilidad']);
//                    if ($categoria instanceof CategoriaResponsabilidad){
//                        $new->setCategoriaResponsabilidad($categoria);
//                        $this->responsabilidadRepository->add($new, true);
//                    }
//                }
//            }
//        }


        $registrosLocales = $this->responsabilidadRepository->findBy(['activo' => true]);

        $this->connection->update('sq_estructura_composicion.tb_nresponsabilidad', ['activo' => 0], ['activo' => true]);
        if (count($registrosLocales) > 0) {
            foreach ($registrosLocales as $value) {
                $nombre = $value->getNombre();
                $existe = $this->connection->fetchAllAssociative("SELECT * FROM sq_estructura_composicion.tb_nresponsabilidad WHERE nombre = '$nombre'");

                $data['nombre'] = $value->getNombre();
                $data['descripcion'] = $value->getDescripcion();
                $data['id_categoria_responsabilidad'] = $value->getCategoriaResponsabilidad()->getId();
                $data['activo'] = 1;

                if (!isset($existe[0])) {
                    $this->connection->insert('sq_estructura_composicion.tb_nresponsabilidad', $data);
                } else {
                    $this->connection->update('sq_estructura_composicion.tb_nresponsabilidad', $data, ['id_responsabilidad' => $existe[0]['id_responsabilidad']]);
                }
            }
        }
        $duration = round((microtime(true) - $tiempo_inicial), 2) . 's';
        $this->io->success(date('d-m-Y H:i:s') . ': End Proccess');
        $this->io->success('Durations: ' . $duration);
        return 0;
    }

}
