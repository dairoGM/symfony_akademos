<?php


namespace App\EventListener;

use App\Entity\BaseEntity;
use App\Entity\Personal\ClasificacionPersona;
use App\Services\TraceManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class DoctrineListener
{

    private $_container;

    public function __construct($_container)
    {
        $this->_container = $_container;
    }

    /** @ORM\PrePersist */
    public function prePersist(LifecycleEventArgs $args)
    {
        $serialize = new Serializer(array(new GetSetMethodNormalizer()), array(new JsonEncoder()));
       // pr($serialize->serialize($args->getObject()->toSerialize(), 'json'));
    }

    /** @ORM\PostPersist() */
    public function preFlush(LifecycleEventArgs $args)
    {
        pr('PreFlush');
        $object = $args->getObject();
        if ($object instanceof BaseEntity || $this->isTrazeabled($args->getObject())) {
            $this->doTrace($object, 'Modificar');
        }
    }

    /** @ORM\PreUpdate */
    public function preUpdate(LifecycleEventArgs $args)
    {
        pr('PreUpdate');
        $object = $args->getObject();
        if ($object instanceof BaseEntity || $this->isTrazeabled($args->getObject())) {
            $this->doTrace($object, 'Modificar');
        }
    }

    /** @ORM\PostRemove */
    public function postRemove(LifecycleEventArgs $args)
    {
        pr('PostRemove');
        $object = $args->getObject();
        if ($object instanceof BaseEntity || $this->isTrazeabled($args->getObject())) {
            $this->doTrace($object, 'Eliminar');
        }
    }

    /** @ORM\PostLoad */
    public function postLoad(LifecycleEventArgs $args)
    {
        $object = $args->getObject();
        if ($object instanceof BaseEntity || $this->isTrazeabled($args->getObject())) {
            $this->doTrace($object, 'Buscar');
        }
    }

    /** @ORM\PostPersist */
    public function postPersist(LifecycleEventArgs $args)
    {
        pr('PostPersist');
        $object = $args->getObject();
        if ($object instanceof BaseEntity || $this->isTrazeabled($args->getObject())) {
            $operation = $object->isNew() ? 'Registrar' : 'Modificar';
            $action = $operation;
            $this->doTrace($object, $action);
        }
    }

    private function doTrace($object, $action)
    {
        pr(123);
        $serialize = new Serializer(array(new GetSetMethodNormalizer()), array(new JsonEncoder()));
        $businessTrace = $this->_container->get('session')->get('business_trace');

        if (empty($businessTrace))
            return;
        $functionalTrace = $this->_container->get('admin.trace.register_trace')->registerFunctionalTrace($businessTrace['business_trace_id'], $action, get_class($object), $object->getPrimaryKey(), $serialize->serialize($object->toSerialize(), 'json'));
        if ($this->_container->get('session')->has('functional_trace')) {
            if (!$this->_container->get('session')->get('functional_trace'))
                $this->_container->get('session')->set('functional_trace', !empty($functionalTrace));
        } else {
            $this->_container->get('session')->set('functional_trace', !empty($functionalTrace));
        }
    }

    private function isTrazeabled($object)
    {
        $var = get_class($object);
        return strpos($var, 'SecurityBundle\Entity') !== FALSE && is_callable(array($object, 'getPrimaryKey'));
    }

}
