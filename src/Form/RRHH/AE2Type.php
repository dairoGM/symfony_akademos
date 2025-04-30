<?php

namespace App\Form\RRHH;

use App\Entity\RRHH\AE2;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AE2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $commonFieldOptions = [
            'required' => false,
            'html5' => true,
            'scale' => 2, // Para permitir decimales (por ser float)
            'attr' => [
                'min' => 1,
                'max' => 1000,
                'step' => 0.01, // Puedes poner '1' si quieres solo enteros
            ],
        ];

        $builder
            ->add('mes', ChoiceType::class, [
                'label' => 'Mes',
                'choices' => [
                    'Enero' => 1,
                    'Febrero' => 2,
                    'Marzo' => 3,
                    'Abril' => 4,
                    'Mayo' => 5,
                    'Junio' => 6,
                    'Julio' => 7,
                    'Agosto' => 8,
                    'Septiembre' => 9,
                    'Octubre' => 10,
                    'Noviembre' => 11,
                    'Diciembre' => 12,
                ],
                'placeholder' => 'Seleccione un mes',
                'required' => true,
//                'data' => (int)date('n'), // <-- Establece el mes actual (1-12)
            ])
            ->add('anno', ChoiceType::class, [
                'label' => 'Año',
                'choices' => array_combine(
                    range(date('Y') - 10, date('Y') + 5), // Ej: de 10 años atrás hasta 5 años adelante
                    range(date('Y') - 10, date('Y') + 5)
                ),
                'placeholder' => 'Seleccione un año',
                'required' => true,
//                'data' => (int)date('Y'), // <-- Establece el año actual (2025, 2026, etc.)
            ])
            ->add('totalPlantillaAprobada', NumberType::class, $commonFieldOptions)
            ->add('totalPlantillaCubierta', NumberType::class, $commonFieldOptions)
            ->add('totalGeneralContratos', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Total General de Contratos (4+7+14)',
            ]))
            ->add('totalContratosProfesoresTiempoDeterminado', NumberType::class, $commonFieldOptions)
            ->add('profesoresTiempoCompleto', NumberType::class, $commonFieldOptions)
            ->add('totalContratosNoDocentes', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Total de Contratos No Docentes (7+14)',
            ]))
            ->add('contratosNoDocentesConRespaldo', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Contratos No Docentes con respaldo de plazas (8 a 13)',
            ]))
            ->add('contratosPorSustitucion', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Contratos por sustitución',
            ]))
            ->add('periodoPrueba', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Período de prueba',
            ]))
            ->add('serenosAuxiliaresLimpieza', NumberType::class, $commonFieldOptions)
            ->add('laboresAgricolas', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Labores Agrícolas',
            ]))
            ->add('jubilados', NumberType::class, $commonFieldOptions)
            ->add('otrosConRespaldo', NumberType::class, $commonFieldOptions)
            ->add('contratosNoDocentesSinRespaldo', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Contratos No Docentes sin respaldo de plazas (15 a 19)',
            ]))
            ->add('serenosAuxiliaresLimpiezaSinRespaldo', NumberType::class, $commonFieldOptions)
            ->add('laboresAgricolasSinRespaldo', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Labores Agrícolas',
            ]))
            ->add('jubiladosSinRespaldo', NumberType::class, $commonFieldOptions)
            ->add('ejecucionObra', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Ejecución de Obra',
            ]))
            ->add('otrosSinRespaldo', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Otros',
            ]))
            ->add('reservaCientificaPreparacion', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Reserva Científica en Preparación',
            ]))
            ->add('recienGraduadosPreparacion', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Recién Graduados en Preparación (Nivel Sup.)',
            ]))
            ->add('reservaDireccionProvincialTrabajo', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Reserva Dirección Provincial de Trabajo',
            ]))
            ->add('tecnicosMediosPreparacion', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Técnicos Medios en Preparación',
            ]))
            ->add('totalEstudiantesUniversidadContratados', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Total de estudiantes de la Universidad de CD contratados por tiempo determinado ',
            ]))
            ->add('estudiantesAuxiliaresTecnicosDocencia', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Del total de estudiantes de CD contratados, cifras como Auxiliar Técnico de la Docencia',
            ]))
            ->add('estudiantesCargosNoDocentes', NumberType::class, array_merge($commonFieldOptions, [
                'label' => 'Del total de estudiantes de CD contratados, cifras en cargos No Docentes',
            ]));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AE2::class,
        ]);
    }
}
