<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckBoxUpdateType extends AbstractType
{
    const IS_FORM_UPDATE = 'isFormUpdate';
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
    public function getParent()
    {
        return CheckboxType::class;
    }
}
/*
add to container
$formModifier = function (FormInterface $form, $ar) {
if ($ar) {

}
};

$builder->addEventListener(
FormEvents::PRE_SET_DATA,
function (FormEvent $event) use ($formModifier) {
$data = $event->getData();
$data && $formModifier($event->getForm(), $data->getAr());
}
);

$builder->get('ar')->addEventListener(
FormEvents::POST_SUBMIT,
function (FormEvent $event) use ($formModifier) {
// It's important here to fetch $event->getForm()->getData(), as
// $event->getData() will get you the client data (that is, the ID)
$ar = $event->getForm()->getData();
$formParent = $event->getForm()->getParent();
$formParent && $formModifier($formParent, $ar);
}
);
 */