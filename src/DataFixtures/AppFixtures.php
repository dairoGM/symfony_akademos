<?php

namespace App\DataFixtures;

use App\Entity\Estructura\CategoriaEstructura;
use App\Entity\Estructura\CategoriaResponsabilidad;
use App\Entity\Estructura\Estructura;
use App\Entity\Estructura\Municipio;
use App\Entity\Estructura\Provincia;
use App\Entity\Estructura\Responsabilidad;
use App\Entity\Estructura\TipoEstructura;
use App\Entity\Personal\ClasificacionPersona;
use App\Entity\Personal\Persona;
use App\Entity\Personal\Sexo;

use App\Entity\Planificacion\EstadoPlan;
use App\Entity\Planificacion\Formula;
use App\Entity\Planificacion\TipoPlan;
use App\Entity\Postgrado\CategoriaCategorizacion;
use App\Entity\Postgrado\EstadoPrograma;
use App\Entity\Postgrado\ModalidadPrograma;
use App\Entity\Postgrado\PresencialidadPrograma;
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

    public function load(ObjectManager $manager)
    {


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

        $provincia = new Provincia();
        $provincia->setNombre('Provincia Inicial');
        $provincia->setSiglas('PI1');
        $provincia->setCodigo('001');
        $manager->persist($provincia);

        $municipio = new Municipio();
        $municipio->setNombre('Provincia Inicial');
        $municipio->setProvincia($provincia);
        $municipio->setCodigo('001');
        $manager->persist($municipio);


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
            $estructura->setProvincia($provincia);
            $estructura->setMunicipio($municipio);
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
        $persona->setProvincia($provincia);
        $persona->setMunicipio($municipio);
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

        $tbn_estado_programa = ['Nuevo', 'En revisión', 'Aprobado'];
        foreach ($tbn_estado_programa as $value) {
            $estado_programa = new EstadoPrograma();
            $estado_programa->setNombre($value);
            $manager->persist($estado_programa);
        }

        $tbn_modalidad_programa = ['Tiempo completo', 'Tiempo paracial'];
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
        $manager->flush();
    }

}