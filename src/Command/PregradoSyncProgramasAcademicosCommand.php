<?php


namespace App\Command;


use App\Controller\MerchantInformationController;
use App\Controller\ServiceController;


use App\Controller\SubscriptionController;
use App\Entity\Pregrado\EstadoProgramaAcademico;
use App\Entity\Pregrado\HistoricoEstadoProgramaAcademico;
use App\Entity\Pregrado\SolicitudProgramaAcademico;
use App\Repository\Pregrado\ProgramaAcademicoDesactivadoRepository;
use App\Repository\Pregrado\SolicitudProgramaAcademicoRepository;
use App\Services\Utils;
use Doctrine\ORM\EntityManagerInterface;
use Recurly\Client;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;


class PregradoSyncProgramasAcademicosCommand extends Command
{

    protected static $defaultName = 'akademos-pregrado-sync-programas-academicos';
    private $env;
    private $container;
    private $em;
    private $solicitudProgramaRepository;
    private $programaAcademicoDesactivadoRepository;


    public function __construct(ContainerBagInterface $container, EntityManagerInterface $em, ProgramaAcademicoDesactivadoRepository $programaAcademicoDesactivadoRepository, SolicitudProgramaAcademicoRepository $solicitudProgramaRepository)
    {
        parent::__construct();
        $this->env = $container->get('env_config');
        $this->container = $container;
        $this->em = $em;
        $this->solicitudProgramaRepository = $solicitudProgramaRepository;
        $this->programaAcademicoDesactivadoRepository = $programaAcademicoDesactivadoRepository;

    }

    protected function configure(): void
    {
        $this->setDescription('Actualizar el estado de los programas academicos');
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
        $this->io->success(date('d-m-Y H:i:s') . ': Iniciando Proceso');
        $progAcadConDesac = $this->programaAcademicoDesactivadoRepository->getSolicitudProgramaAcademicoAprobadoDesactivado([2, 4, 5, 6, 7]);
        $cantidadProcesadas = 0;

        if (is_array($progAcadConDesac)) {
            $estado = $this->em->getRepository(EstadoProgramaAcademico::class)->find(8);
            date_default_timezone_set("America/New_York");
            foreach ($progAcadConDesac as $value) {
                $fechaEliminacion = $value->getFechaEliminacion();
                $fechaActual = new \DateTime('now');
//                echo '<pre>';
//                print_r($fechaActual >= $fechaEliminacion);
//                die;
                if ($fechaActual >= $fechaEliminacion) {
                    $value->getSolicitudProgramaAcademico()->setEstadoProgramaAcademico($estado);
                    $this->solicitudProgramaRepository->edit($value->getSolicitudProgramaAcademico(), true);

                    $historico = new HistoricoEstadoProgramaAcademico();
                    $historico->setSolicitudProgramaAcademico($value->getSolicitudProgramaAcademico());
                    $historico->setEstadoProgramaAcademico($estado);
                    $this->em->persist($historico);
                    $this->em->remove($value);
                    $this->em->flush();
                    $cantidadProcesadas++;
                }
            }
        }


        $duration = round((microtime(true) - $tiempo_inicial), 2) . 's';
        $this->io->success('Cantidad de registros procesados: ' . $cantidadProcesadas, 2);
        $this->io->success(date('d-m-Y H:i:s') . ': Fin del Proceso');
        $this->io->success('Duration: ' . $duration, 2);
        return 0;
    }

}
