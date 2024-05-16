<?php

namespace App\Command;

use App\Controller\AccountController;
use App\Controller\ServiceController;
use App\Entity\Estructura\Pais;
use App\Entity\User;
use App\Repository\Estructura\PaisRepository;
use App\Services\StripeUtils;
use App\Services\Utils;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class LoadCountry extends Command
{
    protected static $defaultName = 'app-load-country';

    private $doctrine;
    private $paisRepository;
    private $csvParsingOptionsPais;

    public function __construct(EntityManagerInterface $doctrine, PaisRepository $paisRepository)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
        $this->paisRepository = $paisRepository;
        $this->csvParsingOptionsPais = array(
            'finder_in' => 'Resources/',
            'finder_name' => 'paises.csv',
            'ignoreFirstLine' => true
        );
    }

    protected function configure(): void
    {
        $this->setDescription('');
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

        $ignoreFirstLine = $this->csvParsingOptionsPais['ignoreFirstLine'];
        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptionsPais['finder_in'])
            ->name($this->csvParsingOptionsPais['finder_name']);
        $csv = null;
        foreach ($finder as $file) {
            $csv = $file;
        }
        if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
            $i = 0;
            while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                $i++;
                if ($ignoreFirstLine && $i == 1) {
                    continue;
                }
                $paises[] = $data[0];
            }
            fclose($handle);
        }
        $all = $this->paisRepository->findAll();
        foreach ($all as $value) {
            $this->doctrine->remove($value);
        }
        $this->doctrine->flush();

        foreach ($paises as $value) {
            $datosPais = explode(',', $value);
            $pais = new Pais();
            $pais->setNombre(str_replace('"', "", $datosPais[0]));
            $pais->setIso2(str_replace('"', '', $datosPais[3]));
            $pais->setIso3(str_replace('"', '', $datosPais[4]));
            $pais->setCodigoTelefonico(str_replace('"', '', $datosPais[5]));

            $this->doctrine->persist($pais);
        }
        $this->doctrine->flush();

        $this->io->success(date('d-m-Y H:i:s') . ': End Proccess');
        $duration = round((microtime(true) - $tiempo_inicial), 2) . 's';
        $this->io->success('Duration: ' . $duration, 2);
        return 1;
        //test
    }

}
