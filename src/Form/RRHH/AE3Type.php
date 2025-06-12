<?php

namespace App\Form\RRHH;

use App\Entity\RRHH\AE3;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

class AE3Type extends AbstractType
{
    private const INDICADORES = [
        'total_cuadros' => '1. TOTAL CUADROS (Suma 2-5)',
        'cuadros_docentes' => '2. Cuadros Docentes',
        'cuadros_administrativos' => '3. Cuadros Administrativos',
        'cuadros_investigacion' => '4. Cuadros Investigación',
        'otros_cuadros' => '5. Otros Cuadros',
        'total_tecnicos' => '6. TOTAL TÉCNICOS (Suma 7-10)',
        'profesores_tiempo_completo' => '7. Profesores a Tiempo Completo',
        'asesores_metodologos' => '8. Asesores o Metodólogos',
        'investigadores' => '9. Investigadores',
        'otros_tecnicos' => '10. Otros Técnicos',
        'administrativos' => '11. ADMINISTRATIVOS',
        'servicio' => '12. SERVICIO',
        'operarios' => '13. OPERARIOS',
        'total' => '14. TOTAL',
        'profesores_tipo_parcial' => 'Profesores a Tiempo Parcial',
    ];

    private const VARIABLES = [
        'total_cubierta' => 'Total Cubierta',
        'de_ellos_fem' => 'De ellos Fem',
        'jovenes_total' => 'Jóvenes Total',
        'jovenes_fem' => 'Jóvenes Fem',
//        'fem' => 'Fem.',
        'pt' => 'PT',
        'pa' => 'PA',
        'as' => 'As',
        'i' => 'I',
        'it' => 'IT',
        'ia' => 'IA',
        'iag' => 'IAg',
        'ai' => 'AI',
        'aux_tec_doc' => 'Aux.Téc.Doc',
        'msc' => 'MsC',
        'dr' => 'Dr',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mes', ChoiceType::class, [
                'label' => 'Mes',
                'choices' => [
//                    'Enero' => 1,
//                    'Febrero' => 2,
                    'Marzo' => 3,
//                    'Abril' => 4,
//                    'Mayo' => 5,
                    'Junio' => 6,
//                    'Julio' => 7,
//                    'Agosto' => 8,
                    'Septiembre' => 9,
//                    'Octubre' => 10,
//                    'Noviembre' => 11,
                    'Diciembre' => 12,
                ],
                'placeholder' => 'Seleccione un mes',
                'required' => true,
            ])
            ->add('anno', ChoiceType::class, [
                'label' => 'Año',
                'choices' => array_combine(
                    range(date('Y') - 10, date('Y') + 5), // Ej: de 10 años atrás hasta 5 años adelante
                    range(date('Y') - 10, date('Y') + 5)
                ),
                'placeholder' => 'Seleccione un año',
                'required' => true,
            ])
            ->add('documento', FileType::class, [
                'label' => 'AE-3 firmado',
                'mapped' => false,
                'required' => $options['action'] == 'registrar',
            ]);

        foreach (self::INDICADORES as $indicador => $indicadorLabel) {
            foreach (self::VARIABLES as $variable => $variableLabel) {
                $builder->add("{$indicador}_{$variable}", IntegerType::class, [
                    'required' => false,
                    'label' => "{$indicadorLabel} - {$variableLabel}",
                    'attr' => ['class' => 'form-control text-right']
                ]);
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AE3::class,
        ]);
    }
}
