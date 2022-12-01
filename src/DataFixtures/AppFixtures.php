<?php

namespace App\DataFixtures;

use App\Entity\Catalogo\Pais;
use App\Entity\Estructura\CategoriaEstructura;
use App\Entity\Estructura\CategoriaResponsabilidad;
use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Municipio;
use App\Entity\Estructura\Provincia;
use App\Entity\Estructura\Responsabilidad;
use App\Entity\Estructura\TipoEstructura;
use App\Entity\Institucion\CategoriaAcreditacion;
use App\Entity\Institucion\Institucion;
use App\Entity\Institucion\RecursosHumanos;
use App\Entity\Institucion\RedSocial;
use App\Entity\Institucion\RolRedesNacional;
use App\Entity\Institucion\Visibilidad;
use App\Entity\Institucion\TipoInstitucion;
use App\Entity\Personal\CategoriaDocente;
use App\Entity\Personal\CategoriaInvestigativa;
use App\Entity\Personal\ClasificacionPersona;
use App\Entity\Personal\GradoAcademico;
use App\Entity\Personal\NivelEscolar;
use App\Entity\Personal\Persona;
use App\Entity\Personal\Sexo;

use App\Entity\Personal\TipoOrganizacion;
use App\Entity\Planificacion\EstadoPlan;
use App\Entity\Planificacion\Formula;
use App\Entity\Planificacion\TipoPlan;
use App\Entity\Postgrado\CategoriaCategorizacion;
use App\Entity\Postgrado\EstadoPrograma;
use App\Entity\Postgrado\ModalidadPrograma;
use App\Entity\Institucion\NivelAcreditacion;
use App\Entity\Postgrado\PresencialidadPrograma;
use App\Entity\Postgrado\RamaCiencia;
use App\Entity\Postgrado\RolComision;
use App\Entity\Postgrado\TipoPrograma;
use App\Entity\Security\Rol;
use App\Entity\Security\RolEstructura;
use App\Entity\Security\User;
use App\Entity\Traza\Accion;
use App\Entity\Traza\Objeto;
use App\Entity\Traza\TipoTraza;
use App\Repository\Personal\SexoRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $em;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->passwordEncoder = $encoder;
    }

    private $csvParsingOptionsProvince = array(
        'finder_in' => 'Resources/',
        'finder_name' => 'provincias.csv',
        'ignoreFirstLine' => true
    );
    private $csvParsingOptionsMunicipaly = array(
        'finder_in' => 'Resources/',
        'finder_name' => 'municipios.csv',
        'ignoreFirstLine' => true
    );

    public function load(ObjectManager $manager)
    {
        $ignoreFirstLine = $this->csvParsingOptionsProvince['ignoreFirstLine'];
        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptionsProvince['finder_in'])
            ->name($this->csvParsingOptionsProvince['finder_name']);

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
                $provincias[] = $data[0];
            }
            fclose($handle);
        }

        $provinciaInial = null;
        foreach ($provincias as $value) {
            $datosProvincia = explode(',', $value);
            $provincia = new Provincia();
            $provincia->setNombre(str_replace('"', "", $datosProvincia[1]));
            $provincia->setCodigo($datosProvincia[0]);
            $provincia->setSiglas(str_replace('"', "", $datosProvincia[2]));
            $manager->persist($provincia);

            if ($datosProvincia[0] == 23) {
                $provinciaInial = $provincia;
            }

        }
        $manager->flush();


        $ignoreFirstLine = $this->csvParsingOptionsMunicipaly['ignoreFirstLine'];
        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptionsMunicipaly['finder_in'])
            ->name($this->csvParsingOptionsMunicipaly['finder_name']);

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
                $municipios[] = $data[0];
            }
            fclose($handle);
        }

        $municipioInial = null;
        foreach ($municipios as $value) {
            $datosMunicipios = explode(',', $value);


            $municipio = new Municipio();
            $municipio->setNombre(str_replace('"', '', $datosMunicipios[1]));
            $municipio->setCodigo($datosMunicipios[0]);
            $municipio->setProvincia($this->em->getRepository(Provincia::class)->findOneBy(['codigo' => intval($datosMunicipios[3])]));
            $manager->persist($municipio);

            if ($datosMunicipios[0] == 2312) {
                $municipioInial = $municipio;
            }
        }

        $sexos[] = [
            'nombre' => 'Masculino',
            'siglas' => 'M',
        ];
        $sexos[] = [
            'nombre' => 'Femenino',
            'siglas' => 'F',
        ];
        foreach ($sexos as $value) {
            $item = new Sexo();
            $item->setNombre($value['nombre']);
            $item->setSiglas($value['siglas']);
            $manager->persist($item);
        }

        $tbn_accion = ['Crear', 'Modificar', 'Eliminar', 'Inicio de sesión', 'Cierre de sesión'];
        foreach ($tbn_accion as $value) {
            $accion = new Accion();
            $accion->setNombre($value);
            $manager->persist($accion);
        }
        $tbn_objeto = ['Autenticación'];
        foreach ($tbn_objeto as $value) {
            $objeto = new Objeto();
            $objeto->setNombre($value);
            $manager->persist($objeto);
        }
        $tbn_tipo_traza = ['Sesión', 'Negocio'];
        foreach ($tbn_tipo_traza as $value) {
            $tipo_traza = new TipoTraza();
            $tipo_traza->setNombre($value);
            $manager->persist($tipo_traza);
        }

        $rol = new Rol();
        $rol->setNombre('Administrador');
        $rol->setRoleKey('ROLE_ADMIN');
        $rol->setActivo(true);
        $manager->persist($rol);

        $user = new User();
        $user->setEmail('admin@admin.cu');
        $user->setRole('ROLE_ADMIN');
        $user->setActivo(true);
        $user->setPasswordPlainText('123');
        $encodedPassword = $this->passwordEncoder->encodePassword($user, '123');
        $user->setPassword($encodedPassword);
        $user->setPasswordChangeFirstTime(true);
        $user->setUserRoles([$rol]);
        $manager->persist($user);


        $datos_generales = ['Inicial'];
        foreach ($datos_generales as $value) {
            $categoriaEstructura = new CategoriaEstructura();
            $categoriaEstructura->setNombre($value);
            $categoriaEstructura->setColor('#000');
            $manager->persist($categoriaEstructura);

            $tipoEstructura = new TipoEstructura();
            $tipoEstructura->setNombre($value);
            $manager->persist($tipoEstructura);

            $catResp = new CategoriaResponsabilidad();
            $catResp->setNombre($value);
            $catResp->setColor('#000');
            $manager->persist($catResp);

            $resp = new Responsabilidad();
            $resp->setNombre($value);
            $resp->setCategoriaResponsabilidad($catResp);
            $manager->persist($resp);

            $estructura = new Estructura();
            $estructura->setNombre($value);
            $estructura->setCategoriaEstructura($categoriaEstructura);
            $estructura->setTipoEstructura($tipoEstructura);
            $estructura->setTelefono('+53 55555555');
            $estructura->setEmail('admin@est-inicial.com');
            $estructura->setDireccion($value);
            $estructura->setSiglas('INI');
            $estructura->setProvincia($provinciaInial);
            $estructura->setMunicipio($municipioInial);
            $estructura->setFechaActivacion(new \DateTime());
            $manager->persist($estructura);

            $rolEstructura = new RolEstructura();
            $rolEstructura->setRol($rol);
            $rolEstructura->setEstructura($estructura);
            $manager->persist($rolEstructura);

            $clasificacionPersona = new ClasificacionPersona();
            $clasificacionPersona->setNombre($value);
            $manager->persist($clasificacionPersona);
        }


        $persona = new Persona();
        $persona->setEstructura($estructura);
        $persona->setCategoriaEstructura($categoriaEstructura);
        $persona->setResponsabilidad($resp);

        $persona->setActivo(true);
        $persona->setEmail('admin@admin.cu');
        $persona->setCarnetIdentidad('00000000000');
        $persona->setNumeroSerieCarnetIdentidad('FDA000');
        $persona->setProvincia($provinciaInial);
        $persona->setMunicipio($municipioInial);
        $persona->setClasificacionPersona($clasificacionPersona);
        $persona->setUsuario($user);
        $persona->setPrimerNombre('Admin');
        $persona->setPrimerApellido('Inial');
        $persona->setSegundoApellido('123');
        $manager->persist($persona);


        $tbn_categoria_categorizacion = ['A', 'B', 'C', 'D'];
        foreach ($tbn_categoria_categorizacion as $value) {
            $categoria_categorizacion = new CategoriaCategorizacion();
            $categoria_categorizacion->setNombre($value);
            $categoria_categorizacion->setSiglas($value);
            $manager->persist($categoria_categorizacion);
        }

        $tbn_estado_programa = ['Nuevo', 'En revisión', 'Analizado por comisión', 'Revisado', 'Aprobado', 'Rechazado'];
        foreach ($tbn_estado_programa as $value) {
            $estado_programa = new EstadoPrograma();
            $estado_programa->setNombre($value);
            $manager->persist($estado_programa);
        }

        $tbn_modalidad_programa = ['Tiempo completo', 'Tiempo parcial'];
        foreach ($tbn_modalidad_programa as $value) {
            $modalidad_programa = new ModalidadPrograma();
            $modalidad_programa->setNombre($value);
            $manager->persist($modalidad_programa);
        }

        $tbn_presencialidad_programa = ['Presencial', 'Semipresencial', 'A distancia'];
        foreach ($tbn_presencialidad_programa as $value) {
            $presencialidad_programa = new PresencialidadPrograma();
            $presencialidad_programa->setNombre($value);
            $manager->persist($presencialidad_programa);
        }

        $tbn_tipo_programa = ['Curso', 'Entrenamiento', 'Diplomado', 'Maestría', 'Especialidad', 'Doctorado'];
        foreach ($tbn_tipo_programa as $value) {
            $tipo_programa = new TipoPrograma();
            $tipo_programa->setNombre($value);
            $manager->persist($tipo_programa);
        }

        $universidad = new Institucion();
        $universidad->setNombre('Universidad de las Ciencias Informatica');
        $universidad->setSiglas('UCI');
        $universidad->setLema('Lema');
        $universidad->setMision('Mision');
        $universidad->setVision('Vision');
        $universidad->setRector('Rector');
        $universidad->setLogo('Logo.png');
        $universidad->setOrganigrama('Organigrama.png');
        $universidad->setFechaFundacion(new \DateTime());
        $manager->persist($universidad);


        $tipoInstitucion[] = [
            'nombre' => 'Entidad de Ciencia Tecnología e Innovación',
            'siglas' => 'ECTI',
        ];
        $tipoInstitucion[] = [
            'nombre' => 'Institución de Educación Superior',
            'siglas' => 'IES',
        ];
        foreach ($tipoInstitucion as $value) {
            $item = new TipoInstitucion();
            $item->setNombre($value['nombre']);
            $item->setSiglas($value['siglas']);
            $manager->persist($item);
        }


        $categoriaAcreditacion[] = [
            'nombre' => 'Calificada',
            'duracion' => '4',
        ];
        $categoriaAcreditacion[] = [
            'nombre' => 'Certificada',
            'duracion' => '5',
        ];
        $categoriaAcreditacion[] = [
            'nombre' => 'De excelencia',
            'duracion' => '7',
        ];
        foreach ($categoriaAcreditacion as $value) {
            $item = new CategoriaAcreditacion();
            $item->setNombre($value['nombre']);
            $item->setDuracion($value['duracion']);
            $manager->persist($item);
        }


        $tbn_rama_ciencia = [
            'CNE-Ciencias Naturales y Exactas',
            'CT-Ciencias Técnicas',
            'CA-Ciencias Agropecuarias',
            'CE-Ciencias Económicas',
            'CBM-Ciencias Biomédicas',
            'CSH- Ciencias Sociales y Humanidades',
            'CP- Ciencias Pedagógicas',
            'CM- Ciencias Militares'
        ];
        foreach ($tbn_rama_ciencia as $value) {
            $rama_ciencia = new RamaCiencia();
            $temp = explode('-', $value);
            $rama_ciencia->setNombre($temp[1]);
            $rama_ciencia->setSiglas($temp[0]);
            $manager->persist($rama_ciencia);
        }

        $tbn_categoria_docente = ['Instructor', 'Asistente', 'Auxiliar', 'Titular'];
        foreach ($tbn_categoria_docente as $value) {
            $categoria_docente = new CategoriaDocente();
            $categoria_docente->setNombre($value);
            $manager->persist($categoria_docente);
        }
        $tbn_grado_academico[] = [
            'nombre' => 'Máster en artes',
            'siglas' => 'Ms.'
        ];
        $tbn_grado_academico[] = [
            'nombre' => 'Doctor',
            'siglas' => 'Dr. C.'
        ];
        $tbn_grado_academico[] = [
            'nombre' => 'Especialidad de postgrado',
            'siglas' => 'Esp.'
        ];
        $tbn_grado_academico[] = [
            'nombre' => 'Máster en ciencias',
            'siglas' => 'M.Sc.'
        ];
        $tbn_grado_academico[] = [
            'nombre' => 'Doctora',
            'siglas' => 'Dr. C.'
        ];
        $tbn_grado_academico[] = [
            'nombre' => 'Doctora en ciencias',
            'siglas' => 'Dr. C.'
        ];
        $tbn_grado_academico[] = [
            'nombre' => 'Doctor en ciencias',
            'siglas' => 'Dr. C.'
        ];
        $tbn_grado_academico[] = [
            'nombre' => 'Máster',
            'siglas' => 'M.Sc.'
        ];

        foreach ($tbn_grado_academico as $value) {
            $grado_academico = new GradoAcademico();
            $grado_academico->setNombre($value['nombre']);
            $grado_academico->setSiglas($value['siglas']);
            $manager->persist($grado_academico);
        }

        $tbn_nivel_escolar = ['Universitario', 'Preuniversitario', 'Técnico Medio', 'Docente Nivel Medio', 'Secundaria', 'Primaria', 'Obrero Calificado'];
        foreach ($tbn_nivel_escolar as $value) {
            $nivel_escolar = new NivelEscolar();
            $nivel_escolar->setNombre($value);
            $manager->persist($nivel_escolar);
        }

        $tbn_categoria_investigativa = ['Investigador Titular', 'Investigador Auxiliar', 'Investigador Agregado', 'Aspirante a Investigador'];
        foreach ($tbn_categoria_investigativa as $value) {
            $categoria_investigativa = new CategoriaInvestigativa();
            $categoria_investigativa->setNombre($value);
            $manager->persist($categoria_investigativa);
        }

        $tbn_tipo_organizacion = ['Organizaciones políticas', 'Organizaciones de masa'];
        foreach ($tbn_tipo_organizacion as $value) {
            $tipo_organizacion = new TipoOrganizacion();
            $tipo_organizacion->setNombre($value);
            $manager->persist($tipo_organizacion);
        }


        $tbn_redes_sociales = [
            'Facebook',
            'Youtube',
            'Whatsapp',
            'Instagram',
            'WeChat',
            'TikTok',
            'Facebook Messenger',
            'Telegram',
            'Snapchat',
            'Douyin',
            'Kuaishou',
            'Sina Weibo',
            'QQ',
            'Twitter',
            'Pinterest',
            'Reddit',
            'Quora'
        ];
        foreach ($tbn_redes_sociales as $value) {
            $redSocial = new RedSocial();
            $redSocial->setNombre($value);
            $manager->persist($redSocial);
        }

        $tbn_rol_redes = [
            'Coordina',
            'Participa',
        ];
        foreach ($tbn_rol_redes as $value) {
            $rolRedes = new RolRedesNacional();
            $rolRedes->setNombre($value);
            $manager->persist($rolRedes);
        }

        $tbn_visibilidad = [
            'Reduni',
            'Nacional',
            'Internacional',
        ];
        foreach ($tbn_visibilidad as $value) {
            $visibilidad = new Visibilidad();
            $visibilidad->setNombre($value);
            $manager->persist($visibilidad);
        }


        $tbn_recursos_humanos = [
            'Trabajadores',
            'Profesores',
            'Estudiantes',
            'Doctores',
            'Masters',
            'Profesores Titulares',
            'Profesores Auxiliares',
        ];
        foreach ($tbn_recursos_humanos as $value) {
            $recursos_humanos = new RecursosHumanos();
            $recursos_humanos->setNombre($value);
            $manager->persist($recursos_humanos);
        }


        $rolComision = [
            'Jefe de comisión',
            'Secretario(a)',
            'Miembro'
        ];
        foreach ($rolComision as $value) {
            $item = new RolComision();
            $item->setNombre($value);
            $manager->persist($item);
        }


        $manager->flush();
    }

}