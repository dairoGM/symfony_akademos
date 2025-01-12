<?php

namespace App\Command;

use App\Repository\Tramite\DocumentoSalidaRepository;
use App\Repository\Tramite\EstadoFichaSalidaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Psr\Log\LoggerInterface;

class ViajandoCommand extends Command
{
    protected static $defaultName = 'akademos-tramite-sync-viajando';

    private SymfonyStyle $io;
    private DocumentoSalidaRepository $documentoSalidaRepository;
    private EstadoFichaSalidaRepository $estadoFichaSalidaRepository;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(
        DocumentoSalidaRepository   $documentoSalidaRepository,
        EstadoFichaSalidaRepository $estadoFichaSalidaRepository,
        EntityManagerInterface      $entityManager,
        LoggerInterface             $logger
    )
    {
        parent::__construct();

        $this->documentoSalidaRepository = $documentoSalidaRepository;
        $this->estadoFichaSalidaRepository = $estadoFichaSalidaRepository;
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Actualiza los documentos de salida a "viajando" si la fecha de salida es mayor o igual a la fecha actual.')
            ->setHelp('Este comando procesa todos los documentos de salida con estado pendiente y actualiza su estado si cumplen ciertas condiciones.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->io->title('Iniciando el proceso de sincronización de documentos de salida');

        try {
            $this->procesarDocumentosSalida();
            $this->io->success('El proceso ha finalizado correctamente.');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->io->error('Se produjo un error durante la ejecución del comando: ' . $e->getMessage());
            $this->logger->error('Error en ViajandoCommand', [
                'exception' => $e,
            ]);

            return Command::FAILURE;
        }
    }

    private function procesarDocumentosSalida(): void
    {
        $this->io->section('Obteniendo documentos de salida pendientes');

        $documentos = $this->documentoSalidaRepository->findBy([
            'estadoDocumentoSalida' => 5, // Estado pendiente
        ], [
            'id' => 'DESC'
        ]);

        if (empty($documentos)) {
            $this->io->info('No se encontraron documentos de salida para procesar.');
            return;
        }

        $this->io->text(sprintf('Se encontraron %d documentos para procesar.', count($documentos)));

        $estadoViajando = $this->estadoFichaSalidaRepository->find(10);
        if (!$estadoViajando) {
            throw new \RuntimeException('No se pudo encontrar el estado "viajando" en la base de datos.');
        }

        $currentDate = new \DateTime();
        $processedCount = 0;

        foreach ($documentos as $documento) {
            $this->procesarDocumento($documento, $estadoViajando, $currentDate);
            $processedCount++;
        }

        $this->entityManager->flush();
        $this->io->success(sprintf('Se procesaron %d documentos correctamente.', $processedCount));
    }

    private function procesarDocumento($documento, $estadoViajando, \DateTime $currentDate): void
    {
        $fechaSalidaReal = $documento->getFechaSalidaReal();

        if (!$fechaSalidaReal) {
            $this->logger->warning('Documento sin fecha de salida real', [
                'documentoId' => $documento->getId(),
            ]);
            $this->io->warning(sprintf('Documento ID %d no tiene fecha de salida real.', $documento->getId()));
            return;
        }

        if ($fechaSalidaReal >= $currentDate) {
            $documento->setEstadoDocumentoSalida($estadoViajando);
            $this->io->text(sprintf('Documento ID %d actualizado a estado "viajando".', $documento->getId()));
        } else {
            $this->io->text(sprintf('Documento ID %d no cumple las condiciones para ser actualizado.', $documento->getId()));
        }
    }
}
