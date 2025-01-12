<?php

namespace App\Command;

use App\Repository\Tramite\DocumentoSalidaRepository;
use App\Repository\Tramite\EstadoFichaSalidaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ViajandoCommand extends Command
{
    protected static $defaultName = 'akademos-tramite-sync-viajando';

    private SymfonyStyle $io;
    private DocumentoSalidaRepository $documentoSalidaRepository;
    private EstadoFichaSalidaRepository $estadoFichaSalidaRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(
        DocumentoSalidaRepository   $documentoSalidaRepository,
        EstadoFichaSalidaRepository $estadoFichaSalidaRepository,
        EntityManagerInterface      $entityManager
    )
    {
        parent::__construct();
        $this->documentoSalidaRepository = $documentoSalidaRepository;
        $this->estadoFichaSalidaRepository = $estadoFichaSalidaRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Procedimiento que actualiza a viajando todos los documentos de salida que estén con fecha mayor o igual a la fecha actual.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        try {
            $this->procesarDocumentosSalidaViajando();
            $this->io->success('Proceso completado exitosamente.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->io->error('Ocurrió un error durante el proceso: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function procesarDocumentosSalidaViajando(): void
    {
        $this->io->title('Inicio del procesamiento de documentos de salida');

        // Buscar documentos con estado 5
        $documentos = $this->documentoSalidaRepository->findBy([
            'estadoDocumentoSalida' => 5,
        ], ['id' => 'DESC']);

        $this->io->section("Registros a procesar: " . count($documentos));

        foreach ($documentos as $documento) {
            $this->procesarDocumentoSalida($documento);
        }

        $this->entityManager->flush(); // Guarda todos los cambios al final
        $this->io->title('Fin del procesamiento de documentos de salida');
    }

    private function procesarDocumentoSalida($documento): void
    {
        $currentDate = new \DateTime();
        if ($documento->getFechaSalidaReal() >= $currentDate) {
            $documento->setEstadoDocumentoSalida($this->estadoFichaSalidaRepository->find(10));
        }
    }
}
